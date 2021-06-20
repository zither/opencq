<?php
namespace Xian\Handlers;

use Xian\AbstractHandler;
use Xian\Helper;
use function player\addPlayerStackableItem;
use function player\getItem;
use function player\getMedicine;
use function player\getPlayerBoundProperty;
use function player\getPlayerEquip;

class Fangshi extends AbstractHandler
{

    use CommonTrait;

    public function showDaoju()
    {
        $type = $this->params['type'] ?? 0;
        $page = $this->params['page'] ?? 1;
        $size = 15;
        $offset = $size * ($page - 1);
        $conditions = [
            'LIMIT' => [$offset, $size],
            'ORDER' => ['id' => 'DESC'],
        ];
        if ($type) {
            $conditions['item_type'] = $type;
        }
        $marketItems = $this->db()->select('market_item', '*', $conditions);
        if ($type) {
            $count = $this->db()->count('market_item', ['item_type' => $type]);
        } else {
            $count = $this->db()->count('market_item');
        }
        $data = [
            'items' => $marketItems,
            'type' => $type,
            'page' => $page,
            'prev_page' => $page > 1 ? $page - 1 : 0,
            'next_page' => $size * $page < $count ? $page + 1: 0,
        ];
        $this->display('market/item_list', $data);
    }

    public function showItemInfo()
    {
        $id = $this->params['id'] ?? 0;
        $data = [];
        $marketItem = $this->db()->get('market_item', '*', ['id' => $id]);
        if ($marketItem['item_type'] == 2) {
            $equip = getPlayerEquip($this->db(), $marketItem['player_item_id']);
            $data['tool'] = $this->equipTypes[$equip->equipType];
            $data['attributes'] = $this->attributes;
            $data['quality_color'] = $this->getQualityColor($equip->quality);
            $data['zhuangbei'] = $equip;
            $data['manuals'] = [6 => '战士', 7 => '法师', 8 => '道士'];
        } else if ($marketItem['item_type'] == 3) {
            $data['yaopin'] = getMedicine($this->db(), $id);
        }
        $data['item'] = getItem($this->db(), $marketItem['item_id']);
        $data['marketItem'] = $marketItem;
        $data['is_bound'] = getPlayerBoundProperty($this->db(), $this->uid());

        $this->display('market/item_info', $data);
    }

    public function buyDaoju()
    {
        $id = $this->params['id'] ?? 0;
        $count = Helper::filterVar($this->postParam('count'), 'INT');
        if ($count < 0) {
            $this->flash->error("无效购买数量");
            $this->doRawCmd($this->lastAction());
        }
        $marketItem = $this->db()->get('market_item', '*', ['id' => $id]);
        if (empty($marketItem) || $marketItem['amount'] < $count) {
            $this->flash->error("坊市道具数量不足，购买失败");
            $this->doRawCmd($this->lastAction());
        }
        $db = $this->db();
        $uid = $this->uid();
        try{
            $db->pdo->beginTransaction();
            // 买家扣除货币
            $price = $count * $marketItem['price'];
            if ($marketItem['currency'] == 1)  {
                $db->update('game1', ['uyxb[-]' => $price], ['uyxb[>=]' => $price, 'id' => $uid]);
            } else {
                $db->update('game1', ['uczb[-]' => $price], ['uczb[>=]' => $price, 'id' => $uid]);
            }
            // 扣除坊市道具
            if ($marketItem['item_type'] == 2) {
                $db->delete('market_item', ['id' => $marketItem['id']]);
            } else {
                if ($count < $marketItem['amount']) {
                    $db->update('market_item', ['amount[-]' => $count], ['id' => $marketItem['id']]);
                } else {
                    $db->delete('market_item', ['id' => $marketItem['id']]);
                }
            }
            // 买家获得物品
            if ($marketItem['item_type'] == 2) {
                // 装备直接修改 UID
                $db->update('player_item', ['uid' => $uid], ['id' => $marketItem['player_item_id']]);
            } else {
                addPlayerStackableItem($this->db(), $uid, ['id' => $marketItem['item_id'], 'type' => $marketItem['item_type']], $count);
            }
            // 卖家收款
            if ($marketItem['currency'] == 1)  {
                $db->update('game1', ['uyxb[+]' => $price], ['id' => $uid]);
            } else {
                $db->update('game1', ['uczb[+]' => $price], ['id' => $uid]);
            }
            // 交易成功就提交
            $db->pdo->commit();
            $this->flash->success( sprintf("购买 %s(x%d) 成功", $marketItem['name'], $count));
        }catch (\Exception $e) {
            $this->flash->error('装备购买失败(500)');
            $db->pdo->rollBack();
        }
        if ($count < $marketItem['amount']) {
            $this->doRawCmd($this->lastAction());
        } else {
            $this->doRawCmd('cmd=fangshi-daoju');
        }
    }
}