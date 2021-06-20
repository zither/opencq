<?php

namespace Xian\Handlers;

use Xian\AbstractHandler;
use Xian\Condition;
use Xian\Event;
use Xian\Helper;
use function player\changeAllPlayerTaskConditionsByItemId;
use function player\getEquip;
use function player\getMedicine;
use function player\getPlayerEquip;
use function player\updateTaskStatusWhenFinished;

class Daoju extends AbstractHandler
{
    use CommonTrait;

    public function djInfo()
    {
        $db = $this->game->db;
        $djid = $this->params['djid'] ?? 0;
        $player = \player\getPlayerById($db, $this->uid(), true);
        $gonowmidcmd = $this->encode("cmd=gomid");

        $item = \player\getItem($this->db(), $djid);

        $playerItem = \player\getPlayerItem($this->db(), $djid, $player->id);

        $items = [];
        if ($item->isPackage) {
            $packageItems = explode(',', $item->packageItems);
            foreach ($packageItems as $v) {
                $str = trim($v);
                list($itemType, $itemId, $itemNum) = explode('|', $str);
                switch ($itemType) {
                    case 5:
                        $target = \player\getpeifang($itemId, $db);
                        $items[] = [
                            'name' => $target['name'],
                            'class' => 'pfys'
                        ];
                        break;
                }
            }
        }

        $operations = [];
        if (!empty($item->operations)) {
            $ops = explode(',', $item->operations);
            $operations = $this->db()->select('operation', '*', ['id' => $ops]);
            $checker = new Condition($this->db(), $this->uid());
            foreach ($operations as $k => $v) {
                if (!empty($v['condition'])) {
                    $condition = $this->db()->get('condition', '*', ['id' => $v['condition']]);
                    list ($r, $message) = $checker->validate($condition);
                    if ($r) {
                        continue;
                    }
                    unset($operations[$k]);
                }
            }
            $operations = array_values($operations);
        }

        $data = [];

        // 道具是功法秘籍
        if ($item->type == 1 && $item->subType == 1) {
            $data['manual'] = $this->db()->get('manual', '*', ['id' => $item->extra]);
        }
        if ($item->type == 1 && $item->subType == 2) {
            $data['skill'] = $this->db()->get('skills', '*', ['id' => $item->extra]);
        }

        if ($item->type == 2) {
            $equip = getEquip($this->db(), $djid);
            $data['tool'] = $this->equipTypes[$equip->equipType];
            $data['attributes'] = $this->attributes;
            $data['manuals'] = [6 => '战士', 7 => '法师', 8 => '道士'];
            $data['zhuangbei'] = $equip;
        } else if ($item->type == 3) {
            $data['yaopin'] = getMedicine($this->db(), $djid);
        }

        $data['useCmd'] = $this->encode("cmd=use-djinfo&djid=$djid");
        $data['package'] = $items;
        $data['operations'] = $operations;
        $data['gonowmid'] = $gonowmidcmd;
        $data['item'] = $item;
        $data['playerItem'] = $playerItem;
        $this->display('sys_djinfo', $data);
    }

    public function sellDjInfo()
    {
        $db = $this->game->db;
        $playerItemId = $this->params['id'] ?? 0;
        $count = Helper::filterVar($this->postParam('count'), 'INT');
        $price = Helper::filterVar($this->postParam('price'), 'INT');
        $playerItem = \player\getPlayerItemById($this->db(), $playerItemId, $this->uid());
        if (!$count || !$price|| !$playerItem->id) {
            $this->flash->set('message', '请填写数量和价格');
            $this->doRawCmd($this->lastAction());
        }
        try{
            $db->pdo->setAttribute(\PDO::ATTR_ERRMODE,  \PDO::ERRMODE_EXCEPTION);
            $db->pdo->beginTransaction();
            if ($playerItem->type != 2) {
                $res = $db->update('player_item', [
                    'amount[-]' => $count,
                ], [
                    'id' => $playerItemId,
                    'amount[>=]' => $count,
                    'uid' => $this->uid(),
                ]);
            } else {
                $res = $this->db()->update('player_item', ['uid' => 0], [
                    'id' => $playerItemId,
                    'uid' => $this->uid()
                ]);
                $count = 1;
            }
            if (!$res ->rowCount()) {
                throw new \PDOException("你身上的道具不足");
            }

            $name = $playerItem->uiName ?: $playerItem->name;
            if ($playerItem->type == 2) {
                $playerEquip = getPlayerEquip($this->db(), $playerItemId);
                $name = Helper::getPlayerEquipName($playerEquip);
            }
            $res = $db->insert('market_item', [
                'name' => $name,
                'uid' => $this->uid(),
                'player_item_id' => $playerItem->id,
                'item_id' => $playerItem->itemId,
                'price' => abs($price),
                'amount' => $count,
                'item_type' => $playerItem->type,
                'quality' => isset($playerEquip) ? $playerEquip->quality : 0,
                // @FIXME 货币类型暂时只支持游戏币
                'currency' => 1,
            ]);
            if (!$res->rowCount()) {
                throw new \PDOException("寄售失败");
            }
            $message =  "寄售成功！";
            $db->pdo->commit();//交易成功就提交

            // 更新道具对应的未完成任务条件
            changeAllPlayerTaskConditionsByItemId($db, $this->uid(), $playerItem->itemId, -1);
            // 更新任务状态
            updateTaskStatusWhenFinished($db, $this->uid());

        } catch (\PDOException $e) {
            $message = $e->getMessage();
            $db->pdo->rollBack();
        }
        $this->flash->set('message', $message);
        $this->doRawCmd($this->lastAction());
    }

    public function useDjInfo()
    {
        $db = $this->game->db;
        $player = \player\getPlayerById($db, $this->uid());
        $djid = $this->params['djid'] ?? 0;
        $back = $this->encode(sprintf('cmd=djinfo&djid=%d', $djid));


        $daoju = \player\getPlayerItem($this->db(), $djid, $player->id);
        $ydaoju = \player\getItem($this->db(), $djid);
        if (!$daoju->id) {
            $this->flash->set('message', '无效的道具');
            $this->doCmd($back);
        }
        try {
            $db->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $db->pdo->beginTransaction();
            if ($daoju->amount <= 0) {
                throw new \Exception('道具数量不足，无法使用');
            }
            // 礼包处理逻辑
            if ($ydaoju->isPackage) {
                $package = explode(',', $ydaoju->packageItems);
                foreach ($package as $item) {
                    $item = trim($item);
                    list($itemType, $itemId, $itemNum) = explode('|', $item);
                    switch ($itemType) {
                        case 5:
                            try {
                                \player\addPlayerPF($player->id, $itemId, $db);
                            } catch (\Exception $e) {
                                break;
                            }
                            break;
                        default:
                            throw new \Exception('物品无法使用');
                    }
                }
            }
            \player\deledjsum($this->db(), $ydaoju->id, 1, $player->id);

            $db->pdo->commit();
            $message = "物品使用成功";
        } catch (\Exception $e) {
            $message = $e->getMessage();
            $db->pdo->rollBack();
        }
        $this->flash->set('message', $message);
        $this->doCmd($back);
    }
}