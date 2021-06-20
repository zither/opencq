<?php
namespace Xian\Job;

use Xian\Helper;

class CheckTaskMonsterCondition extends BaseJob
{
    public function perform($args)
    {
        $gid = $args['gid'] ?? 0;
        $uid = $args['uid'] ?? 0;
        // 没有足够的信息，无法继续任务
        if (!$uid || !$gid) {
            echo 'LootItem: 信息不足，无法检查怪物相关任务状态';
            throw new \Exception('LootItem: 信息不足，无法检查怪物相关任务状态');
        }
        $messages = [];
        // 更新怪物相关的任务信息
        $tasks = \player\getUnfinishedTasks($this->db, $uid);
        if (!empty($tasks)) {
            $taskIds = [];
            foreach ($tasks as $v) {
                $taskIds[] = $v['task_id'];
            }
            \player\changeTaskMonsterCondition($this->db, $taskIds, $gid, $uid);
            $conArr = \player\getPlayerUnfinishedTasksConditions($this->db, $uid, $gid, 2);
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