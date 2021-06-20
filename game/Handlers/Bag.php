<?php

namespace Xian\Handlers;

use Xian\AbstractHandler;
use Xian\Condition;
use function player\changeAllPlayerTaskConditionsByItemId;
use function player\getEquip;
use function player\getMedicine;
use function player\getPlayerBoundProperty;
use function player\updateTaskStatusWhenFinished;

class Bag extends AbstractHandler
{
    use CommonTrait;

    public function showDaoju()
    {
        $page = $this->params['page'] ?? 1;
        $size = 10;
        $offset = ($page - 1) * $size;
        $total = $this->db()->count('player_item', ['[>]item' => ['item_id' => 'id']], [
            'player_item.id',
        ], [
            'player_item.uid' => $this->uid(),
            'player_item.storage' => 1,
            'player_item.amount[>]' => 0,
            'item.type' => 1,
        ]);
        $lastPage = ceil($total / $size);
        $previousPage = $page > 1 ? $page - 1 : null;
        $nextPage = $page < $lastPage ? $page + 1 : null;
        $db = $this->game->db;
        $player = \player\getPlayerById($db, $this->uid(), true);
        $uid = $player->id;
        $data = [];
        $items = $db->select('player_item', ['[>]item' => ['item_id' => 'id']], [
            'player_item.id',
            'item.name',
            'item.ui_name',
            'item.type',
            'player_item.item_id',
            'player_item.sub_item_id',
            'player_item.uid',
            'player_item.amount',
            'item.quality',
            'item.price',
            'item.is_package',
            'item.package_items',
            'item.is_bound',
            'item.is_sellable',
        ], [
            'player_item.uid' => $uid,
            'player_item.storage' => 1,
            'player_item.amount[>]' => 0,
            'item.type' => 1,
            'LIMIT' => [$offset, $size],
        ]);
        foreach ($items as &$v) {
            $v['info_link'] = $this->encode(sprintf('cmd=show-my-item&djid=%d', $v['item_id']));
            $v['sell_one'] = $this->encode(sprintf('cmd=sell-daoju&djid=%d&num=1', $v['item_id']));
            $v['sell_five'] = $this->encode(sprintf('cmd=sell-daoju&djid=%d&num=5', $v['item_id']));
            $v['sell_ten'] = $this->encode(sprintf('cmd=sell-daoju&djid=%d&num=10', $v['item_id']));
            $v['amount'] = $v['amount'] > 999 ? '999+' : $v['amount'];
        }
        $data['items'] = $items;
        $data['getbagzbcmd'] = $this->encode("cmd=getbagzb");
        $data['getbagypcmd'] = $this->encode("cmd=getbagyp");
        $data['getbagjncmd'] = $this->encode("cmd=getbagjn");
        $data['getbagpfcmd'] = $this->encode("cmd=getbagpf");
        $data['player'] = $player;
        $data['count'] = $this->getLiftingCapacity();
        $data['previous_page'] = $previousPage;
        $data['next_page'] = $nextPage;

        $this->display('bag/daoju', $data);
    }

    public function sellDaoju()
    {
        $djid = $this->params['djid'] ?? 0;
        $num = $this->params['num'] ?? 1;
        $db = $this->game->db;
        $player = \player\getPlayerById($db, $this->uid(), true);

        $daoju = \player\getItem($this->db(), $djid);
        // 检查道具是否可以出售
        if (!$daoju->isSellable) {
            $this->flash->error("{$daoju->name}无法出售");
            $this->doRawCmd($this->game->event->lastAction());
        }
        $ret = \player\deledjsum($this->db(), $djid, $num, $player->id);
        if ($ret) {
            $income = floor(($daoju->price / 2) * $num);
            \player\changeyxb($db, 1, $income, $this->uid());
            $this->flash->set('message', "卖出成功，获得{$income}金币");
        }
        $this->doRawCmd($this->game->event->lastAction());
    }

    public function showZhuangbei()
    {
        $db = $this->game->db;
        $player = \player\getPlayerById($db, $this->uid(), true);
        $yeshu = $this->params['yeshu'] ?? null;
        if (!isset($yeshu)){
            $yeshu = 1;
        }
        $size = 10;
        $offset = ($yeshu - 1) * $size;
        $retzb = \player\getMultiPlayerEquips($db, $player->id, 1, $offset, $size);
        $zbcount = \player\countPlayerEquips( $db, $player->id, 1);
        $pagenavi = [];
        if ($yeshu > 1) {
            $shangcanshu = $yeshu - 1;
            $pagenavi['prev'] = $this->encode("cmd=getbagzb&yeshu=$shangcanshu");
        }
        if ($offset + $size < $zbcount) {
            $xiacanshu= $yeshu + 1;
            $pagenavi['next'] = $this->encode("cmd=getbagzb&yeshu=$xiacanshu");
        }
        $arr = [
            $player->tool1,
            $player->tool2,
            $player->tool3,
            $player->tool4,
            $player->tool5,
            $player->tool6,
            $player->tool7,
            $player->tool8,
            $player->tool9,
            $player->tool10,
            $player->tool11,
            $player->tool12,
        ];
        $zhuangbei = [];
        foreach ($retzb as $v) {
            $zbnowid = $v['id'];
            $zbqh = 0;
            if (in_array($zbnowid, $arr)) {
                continue;
            }
            $zhuangbei[] = [
                'id' => $zbnowid,
                'name' => $v['ui_name'] ?: $v['name'],
                'qhs' => $zbqh,
                'shengxing' => $v['shengxing'],
                'qianghua' => $v['qianghua'],
                'is_wearing' => in_array($zbnowid, $arr),
                'is_sellable' => $v['is_sellable'],
                'quality_color' => $this->getQualityColor($v['quality'])
            ];
        }
        $data = [];
        $data['zhuangbei'] = $zhuangbei;
        $data['pagenavi'] = $pagenavi;
        $data['player'] = $player;
        $data['count'] = $this->getLiftingCapacity();

        $this->display('bag/zhuangbei', $data);
    }

    public function sellZhuangbei()
    {
        $db = $this->game->db;
        $zbnowid = $this->params['zbnowid'] ?? null;
        $playerEquip = \player\getPlayerEquip($db, $zbnowid);
        if ($playerEquip->id) {
            $db->delete('player_item', ['id' => $zbnowid, 'uid' => $this->uid()]);
            $db->delete('player_equip_info', ['id' => $playerEquip->subItemId]);
            if ($playerEquip->price > 0) {
                $price = floor($playerEquip->price / 2);
                \player\changeyxb($db, 1, $price, $this->uid());
                $this->flash->success("卖出{$playerEquip->name}成功，获得{$price}金币");
                // 更新道具对应的未完成任务条件
                changeAllPlayerTaskConditionsByItemId($db, $this->uid(), $playerEquip->itemId, -1);
                // 更新任务状态
                updateTaskStatusWhenFinished($db, $this->uid());

            } else {
                $this->flash->success("卖出{$playerEquip->name}成功");
            }
        }
        $this->doRawCmd($this->game->event->lastAction());
    }

    public function deleteZhuangbei()
    {
        $db = $this->game->db;
        $zbnowid = $this->params['zbnowid'] ?? null;
        $zhuangbei = \player\getPlayerEquip($this->db(), $zbnowid);
        $fjls = $zhuangbei->qianghua * 20 + 20;
        $ret = \player\changeyxb($this->db(), 2, $fjls, $this->uid());
        if ($ret) {
            $db->delete('player_item', ['id' => $zbnowid, 'uid' => $this->uid()]);
            $db->delete('player_equip_info', ['id' => $zhuangbei->subItemId]);
            $qhs = round($zhuangbei->qianghua * $zhuangbei->qianghua);
            $sjs = mt_rand(1, 100);
            if ($sjs <= 30) {
                $sjs = mt_rand(1,100);
                if ($sjs > 90) {
                    $qhs = $qhs + 3;
                } else if ($sjs > 80) {
                    $qhs = $qhs + 2;
                }else if ($sjs > 70) {
                    $qhs = $qhs + 1;
                }
            }
            $item = \player\getItem($this->db(), 1);
            \player\adddj($this->db(), $this->uid(), $item, $qhs);
            $tishi = '分解成功!';
            if ($qhs > 0) {
                $tishi .= " 获得强化石:$qhs!";
            }
            // 更新道具对应的未完成任务条件
            changeAllPlayerTaskConditionsByItemId($db, $this->uid(), $zhuangbei->itemId, -1);
            // 更新任务状态
            updateTaskStatusWhenFinished($db, $this->uid());

            $this->flash->success($tishi);
        } else {
            $this->flash->error('灵石不足!');
        }
        $this->doRawCmd($this->lastAction());
    }

    public function showPeifang()
    {
        $db = $this->game->db;
        $player = \player\getPlayerById($db, $this->uid(), true);

        $peifang = \player\getPlayerPeifangAll($player->id, $db);
        if (!empty($peifang)) {
            foreach ($peifang as &$v){
                $id = $v['peifang_id'];
                $v['info_link'] = $this->encode("cmd=pfinfo&pfid=$id");
            }
        }
        $data = [];
        $data['peifang'] = $peifang;
        $data['getbagzbcmd'] = $this->encode("cmd=getbagzb");
        $data['getbagdjcmd'] = $this->encode("cmd=getbagdj");
        $data['getbagypcmd'] = $this->encode("cmd=getbagyp");
        $data['getbagjncmd'] = $this->encode("cmd=getbagjn");
        $data['getbagpfcmd'] = $this->encode("cmd=getbagpf");

        $this->display('bag/peifang', $data);
    }

    public function showYaopin()
    {
        $db = $this->game->db;
        $player = \player\getPlayerById($db, $this->uid(), true);

        $yaopin = \player\getplayeryaopinall($db, $player->id, 1);
        if (!empty($yaopin)) {
            foreach ($yaopin as $k => &$v){
                $ypid = $v['item_id'];
                $v['info_link'] = $this->encode("cmd=ypinfo&ypid=$ypid");
            }
        }
        $data = [];
        $data['yaopin'] = array_values($yaopin);
        $data['player'] = $player;
        $data['count'] = $this->getLiftingCapacity();

        $this->display('bag/yaopin', $data);
    }

    public function showMyItem()
    {
        $db = $this->game->db;
        $djid = $this->params['djid'] ?? 0;
        $player = \player\getPlayerById($db, $this->uid(), true);

        $item = \player\getItem($db, $djid);

        $playerItem = \player\getPlayerItem($db, $djid, $player->id);

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
            $conditionChecker = new Condition($this->db(), $this->uid());
            $ops = explode(',', $item->operations);
            $operations = $this->db()->select('operation', '*', ['id' => $ops]);
            foreach ($operations as $k => $v) {
                if (!empty($v['condition'])) {
                    $condition = $this->db()->get('condition', '*', ['id' => $v['condition']]);
                    list ($r, $message) = $conditionChecker->validate($condition);
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
            $data['manual'] = $this->game->db->get('manual', '*', ['id' => $item->extra]);
        }
        if ($item->type == 1 && $item->subType == 2) {
            $data['skill'] = $this->game->db->get('skills', '*', ['id' => $item->extra]);
        }

        if ($item->type == 2) {
            $equip = getEquip($this->db(), $djid);
            $tools = array("不限定","武器","头饰","衣服","腰带","首饰","鞋子","宝石");
            $data['tool'] = $tools[$equip->equipType];
            $data['attributes'] = [
                'hp' => '气血',
                'mp' => '灵气',
                'baqi' => '威压',
                'wugong' => '物攻',
                'fagong' => '法攻',
                'wufang' => '物防',
                'fafang' => '法防',
                'mingzhong' => '命中',
                'shanbi' => '闪避',
                'baoji' => '暴击',
                'shenming' => '神明'
            ];
            $data['zhuangbei'] = $equip;
        } else if ($item->type == 3) {
            $data['yaopin'] = getMedicine($this->db(), $djid);
        }

        $data['useCmd'] = $this->encode("cmd=use-djinfo&djid=$djid");
        $data['package'] = $items;
        $data['operations'] = $operations;
        $data['item'] = $item;
        $data['playerItem'] = $playerItem;
        $data['is_bound'] = $playerItem->isBound ?: getPlayerBoundProperty($this->db(), $this->uid());
        $this->display('djinfo', $data);
    }

    protected function getLiftingCapacity()
    {
        return $this->game->db->count('player_item', [
            'uid' => $this->uid(),
            'storage' => 1,
            'amount[>]' => 0
        ]);
    }
}