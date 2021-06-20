<?php
namespace Xian\Job;

use Xian\Helper;
use Xian\Object\Location;
use function player\getItem;
use function player\getPlayerById;

class LootItem extends BaseJob
{
    public function perform($args)
    {
        $mid = $args['mid'] ?? 0;
        $uid = $args['uid'] ?? 0;
        $itemId = $args['item_id'] ?? 0;
        $amount = $args['item_amount'] ?? 1;
        $monsterName = $args['monster_name'] ?? '未知';
        // 没有足够的信息，无法继续任务
        if (!$uid || !$itemId) {
            echo 'LootItem: 信息不足，无法获得物品';
            throw new \Exception('LootItem: 信息不足，无法获得物品');
        }

        $item = getItem($this->db, $itemId);
        $player = getPlayerById($this->db, $uid);
        $mid = $mid ?: $player->nowmid;
        $loc = Location::get($this->db, $mid);

        $v = ['id' => $item->id, 'type' => $item->type];
        if ($item->type == 2) {
            $amount = 1;
            $source = [
                'location' => $loc->name,
                'monster' => $monsterName,
                'player' => $player->name,
            ];
            // 开启随机属性
            $status = \player\addPlayerEquip($this->db, $player->id, $v, $source, true);
        } else {
            $status = \player\addPlayerStackableItem($this->db, $player->id, $v, $amount);
        }

        $messages = [];
        $tasks = \player\getUnfinishedTasks($this->db, $player->id);
        if (!empty($tasks)) {
            //$taskIds = [];
            //\player\changeTaskItemCondition($this->db, $taskIds, $itemId, $amount, $player->id);
            $conArr = \player\getPlayerUnfinishedTasksConditions($this->db, $player->id, $itemId, 1);
            foreach ($conArr as $v) {
                $messages[] = [
                    'uid' => 0,
                    'tid' => $uid,
                    'type' => 2,
                    'content' => sprintf('%s/%s(%d/%d)', $v['task_name'], $v['target_name'], $v['amount'], $v['required_amount']),
                ];
            }
        }

        // 发出通知
        if (!empty($messages)) {
            $this->db->insert('im', $messages);
        }
    }
}