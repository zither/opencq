<?php

namespace Xian\Handlers;

use player\TaskInfo;
use Xian\AbstractHandler;
use Xian\Condition;
use Xian\Helper;
use Xian\Object\Location;

class Task extends AbstractHandler
{
    public function showTask()
    {
        $db = $this->game->db;
        $encode = $this->encoder;
        $rwid = $this->params['rwid'];
        $nid = $this->params['nid'] ?? 0;

        $uid = $this->session['uid'];
        $player = \player\getPlayerById($db, $this->uid());
        $data = [];
        $task = \player\getTaskById($this->db(), $rwid);
        $playerTask = \player\getPlayerTaskById($this->db(), $rwid, $uid);

        $jieshourw = $encode->encode("cmd=accept-task&nid=$nid&rwid=$rwid&p=-1");
        $tijiaorw = $encode->encode("cmd=complete-task&nid=$nid&rwid=$rwid&p=-1");

        list($items, $targets) = $this->getTaskItems($task);

        $taskDescription = $task->fromDesc;
        if ($playerTask->id && $playerTask->status == 1 && !empty($task->toDesc) && ($task->toId == $nid || $task->type == 4)) {
            $taskDescription = $task->toDesc;
        }

        if (strpos($taskDescription, '=====') !== false) {
            $descArr = explode('=====', $taskDescription);
            $p = $this->params['p'] ?? 1;
            if (isset($descArr[$p - 1])) {
                $taskDescription = $descArr[$p - 1];
            }
            $data['p'] = $p >= count($descArr) ? -1 : ++$p;
        }

        $taskDescription = $this->parseDesc($taskDescription, $task, $player);

        if ($playerTask->id && $task->type != 3 && $playerTask->status != 3) {
            $data['conditions'] = \player\getPlayerTaskConditionsById($this->db(), $player->id, $playerTask->id, $task->type);
        }

        $data['task'] = $task;
        $data['ptask'] = $playerTask;
        $data['task_description'] = $taskDescription;
        $data['items'] = $items;
        $data['targets'] = $targets;
        $data['tijiaorw'] = $tijiaorw;
        $data['jieshourw'] = $jieshourw;
        $data['nid'] = $nid;

        if ($task->type == 4) {
            $this->display('story', $data);
        }

        $this->display('task', $data);
    }

    protected function parseDesc(string $desc, TaskInfo $task, \player\Player $player)
    {
        preg_match_all('/{(?:u|f|t)\.\w+}/', $desc, $matches);
        if (empty($matches)) {
            return $desc;
        }
        $replacements = [];
        foreach ($matches[0] as $v) {
            if (isset($replacements[$v])) {
                continue;
            }
            list($o, $p) = explode('.', trim($v, '{}'));
            if ($o === 'u' && property_exists($player, $p)) {
                $replacements[$v] = $player->$p;
            } else if ($o === 'f') {
                $npc = \player\getOverrideNpc($player->id, $task->fromId, $this->game->db);
                if (property_exists($npc, $p)) {
                    $replacements[$v] = $npc->$p;
                }
            } else if ($o === 't') {
                $npc = \player\getOverrideNpc($player->id, $task->toId, $this->game->db);
                if (property_exists($npc, $p)) {
                    $replacements[$v] = $npc->$p;
                }
            }
        }
        if (empty($replacements)) {
            return $desc;
        }
        return str_replace(array_keys($replacements), array_values($replacements), $desc);
    }


    public function acceptTask()
    {
        $db = $this->game->db;
        $rwid = $this->params['rwid'];
        $nid = $this->params['nid'];

        $player = \player\getPlayerById($db, $this->uid());

        $task = \player\getTaskById($this->db(), $rwid);
        $playerTask = \player\getPlayerTaskById($this->db(), $rwid, $player->id);
        //$back = $this->encode("cmd=mytaskinfo&nid=$nid&rwid=$rwid");
        $back = $this->encode("cmd=gomid");

        if ($playerTask->id){
            $this->flash->set('error', '请不要重复接取任务');
            $this->doCmd($back);
        }

        $day = 0;
        if ($task->mode == 3) {
            $day = date('d');
        }

        $db->insert('player_task', [
            'uid' => $player->id,
            'task_id' => $rwid,
            'task_info_id' => $task->taskInfoId,
            'status' => $task->type == 3 ? 1 : 2,
        ]);
        $playerTaskId = $db->id();

        //@todo 插入物品收集数量统计和怪物击杀统计记录
        $records = [];
        $itemIdMap = [];
        $monsterIdMap = [];
        // 新增 Lua 动态条件，适用于动态条件，比如随机道具日常
        if (!empty($task->lua)) {
            // 优先从 session 中取值
            $key = "lua_items_{$task->id}";
            if (!empty($this->session[$key])) {
                $tmpItems = $this->session[$key];
                // 接受任务后删除对应的缓存
                unset($this->session[$key]);
            } else {
                $exe = new Condition($this->db(), $this->uid());
                $tmpItems = $exe->execute($task->lua);
                unset($exe);
            }

            if (is_array($tmpItems) && !empty($tmpItems)) {
                foreach ($tmpItems as $v) {
                    if (count($v) < 2) {
                        continue;
                    }
                    if ($task->type == 1) {
                        $itemIdMap[$v[1]] = $v[2];
                    } else {
                        $monsterIdMap[$v[1]] = $v[2];
                    }
                }
            }
        }

        if ($task->type == 1) {
            $hasEnoughItems = true;
            $arr = explode(',', $task->item);
            foreach ($arr as $v) {
                if (empty($v)) {
                    continue;
                }
                $a = explode('|', $v);
                $itemIdMap[$a[0]] = $a[1] ?? $task->itemCount;
            }
            // 获取背包已有的道具数量
            $items = \player\getPlayerItemsByItemIds($this->db(), array_keys($itemIdMap), $player->id);
            $itemsMap = [];
            foreach ($items as $v) {
                $itemsMap[$v['item_id']] = $v['amount'];
            }
            // 插入正确的道具数量
            foreach ($itemIdMap as $k =>  $v) {
                $amount = 0;
                if (isset($itemsMap[$k])) {
                    $amount = $itemsMap[$k] >= $v ? $v : $itemsMap[$k];
                }
                // 接受任务时没有收集完全
                if ($amount < $v) {
                    $hasEnoughItems = false;
                }
                $records[] = [
                    'uid' => $player->id,
                    'player_task_id' => $playerTaskId,
                    'task_id' => $task->id,
                    'task_info_id' => $task->taskInfoId,
                    'condition_id' => $k,
                    'amount' =>  $amount,
                    'required_amount' => $v,
                    'type' => 1,
                ];
            }
        }  elseif ($task->type == 2) {
            $arr = explode(',', $task->monster);
            $monsterIdMap = [];
            foreach ($arr as $v) {
                if (empty($v)) {
                    continue;
                }
                $a = explode('|', $v);
                $monsterIdMap[$a[0]] = $a[1] ?? $task->monsterCount;
            }
            foreach ($monsterIdMap as $k => $v) {
                $records[] = [
                    'uid' => $player->id,
                    'player_task_id' => $playerTaskId,
                    'task_id' => $task->id,
                    'task_info_id' => $task->taskInfoId,
                    'condition_id' => $k,
                    'amount' => 0,
                    'required_amount' => $v,
                    'type' => 2,
                ];
            }
        }
        if (!empty($records)) {
            $db->insert('player_task_condition', $records);
        }
        $this->flash->set('message', '接受成功');

        // 身上道具数量已满足，直接修改任务状态
        if ($task->type == 1 && $hasEnoughItems) {
            $db->update('player_task', ['status' => 1], ['uid' => $player->id, 'task_id' => $task->id]);
        }

        // 覆盖 NPC 状态
        if (!empty($task->npcOverride)) {
            $overrides = explode(',', $task->npcOverride);
            foreach ($overrides as $v) {
                list ($nid, $overrideId, $newMid) = explode('|', trim($v));
                \player\setOverride($player->id, $nid, $overrideId, $newMid, $db);
            }
        }
        $this->doCmd($back);
    }

    public function completeTask()
    {
        $db = $this->game->db;
        $rwid = $this->params['rwid'];
        $nid = $this->params['nid'];
        $player = \player\getPlayerById($db, $this->uid());

        $task = \player\getTaskById($this->db(), $rwid);
        $ptask = \player\getPlayerTaskById($this->db(), $rwid, $player->id);
        //$back = $this->encode("cmd=mytaskinfo&nid=$nid&rwid=$rwid");
        $back = $this->encode("cmd=gomid");

        if ($ptask->taskId != $rwid || $ptask->status != 1) {
            $this->doCmd($back);
        }

        if ($task->type != 3) {
            $condition = $this->getTaskCondition($ptask->id, $task->type);
            $playerCondition = $db->select('player_task_condition', '*', ['uid' => $player->id, 'task_id' => $task->id]);
            $playerConditionMap = [];
            foreach ($playerCondition as $v) {
                $playerConditionMap[$v['condition_id']] = $v['amount'];
            }

            foreach ($condition as $k => $v) {
                if (!isset($playerConditionMap[$k]) || $playerConditionMap[$k] < $v) {
                    $this->flash->error('任务条件未完成');
                    $this->doCmd($back);
                }
            }
        }
        $db->update('player_task', ['status' => 3], ['uid' => $player->id, 'task_id' => $rwid]);
        \player\changeexp($this->db(), $player->id, $task->exp);
        \player\changeyxb($this->db(), 1, $task->money, $player->id);
        if ($task->type == 1 && !empty($condition)) {
            $items = $this->db()->select('item', ['id', 'type'], ['id' => array_keys($condition)]);
            $itemsMap = [];
            foreach ($items as $v) {
                $itemsMap[$v['id']] = $v['type'];
            }
            foreach ($condition as $k => $v) {
                if ($itemsMap[$k] == 1) {
                    \player\deledjsum($this->db(), $k, $v, $player->id);
                } else if ($itemsMap[$k] == 2) {
                    while ($v > 0) {
                        \player\deleteOnePlayerEquipByItemId($this->db(), $k, $this->uid());
                        $v--;
                    }
                }
            }
        }

        $toItems = explode(',', $task->toItem);

        // 增加角色物品
        if (!empty($task->toItem)) {
            foreach ($toItems as $v) {
                $arr = explode('|', $v);
                $djid = $arr[0];
                $djcount = $arr[1] ?? $task->toItemCount;
                $item = \player\getItem($this->db(), $djid);

                if ($item->type == 2) {
                    $loc = Location::get($this->db(), $player->nowmid);
                    $source = [
                        'location' => $loc->name,
                        'monster' => '任务',
                        'player' => $player->name
                    ];
                    \player\adddj($this->db(), $player->id, $item, $djcount, $source);
                } else {
                    \player\adddj($this->db(),  $player->id, $item, $djcount);
                }
            }
        }

        $this->flash->set('success', '任务完成，成功获得奖励');
        // 更新 NPC 覆盖状态
        if (!empty($task->updateNpcOverride)) {
            $overrides = explode(',', $task->updateNpcOverride);
            foreach ($overrides as $v) {
                list ($nid, $overrideId, $newMid) = explode('|', trim($v));
                \player\updateOverride($player->id, $nid, $overrideId, $newMid, $db);
            }
        }

        // 触发条件
        if (!empty($task->toOperation)) {
            $op = Helper::operate($this->db(), $task->toOperation, $player);
            if (!empty($op['message'])) {
                $this->flash->success($op['message']);
            }
            if (!empty($op['cmd'])) {
                $this->doRawCmd($op['cmd']);
            }
        }

        $this->doCmd($back);
    }

    protected function getTaskCondition(int $playerTaskId, int $type)
    {
        if ($type != 1 && $type != 2) {
            return [];
        }
        $conditions = \player\getPlayerTaskConditionsById($this->db(), $this->uid(), $playerTaskId, $type);
        $ret = [];
        foreach ($conditions as $v) {
            $ret[$v['condition_id']] = $v['required_amount'];
        }
        return $ret;
    }

    public function showMyTasks()
    {
        $taskType = $this->params['type'] ?? 1;
        $player = \player\getPlayerById($this->game->db, $this->uid());
        $gonowmid = $this->encode("cmd=gomid&newmid=$player->nowmid");

        if ($taskType == 3) {
            $playertask = \player\getPlayerFinishedTasks($this->db(), $player->id);
        } else {
            $playertask = \player\getPlayerUnfinishedTasks($this->db(), $player->id);
        }
        $tasks = [];
        for ($n = 0; $n < count($playertask); $n++) {
            $task = $playertask[$n];
            $rwid = $task['task_id'];
            $mytaskinfo = $this->encode("cmd=mytaskinfo&rwid=$rwid&no_op=1");
            $rwname = $task['name'];
            $rwlx = $task['mode'];
            if ($task['type'] == 4) {
                $type ='剧情';
            } else if ($rwlx == 3) {
                $type ='日常';
            } else if ($rwlx == 1) {
                $type ='主线';
            } else {
                $type ='普通';
            }
            $current = [
                'name' => $rwname,
                'info_link' => $mytaskinfo,
                'image' => $task['status'] == 1 ? 'wen' : '',
                'type' => $type,
                'task_id' => $rwid,
            ];
            $tasks[] = $current;
        }
        $data['gonowmid'] = $gonowmid;
        $data['tasks'] = $tasks;
        $data['type'] = $taskType;
        $this->display('player_task', $data);
    }

    /**
     * 显示单个任务信息
     */
    public function myTask()
    {
        $db = $this->game->db;
        $encode = $this->encoder;
        $rwid = $this->params['rwid'];
        $player = \player\getPlayerById($db, $this->uid());

        $data = [];

        $task = \player\getTaskById($this->db(), $rwid);
        $ptask= \player\getPlayerTaskById($this->db(), $rwid, $player->id);

        list($items, $targets) = $this->getTaskItems($task);

        if ($ptask->id) {
            $data['conditions'] = \player\getPlayerTaskConditionsById($this->db(), $player->id, $ptask->id, $task->type);
            if ($task->type == 1 || $task->type == 2) {
                $targets = [];
                foreach ($data['conditions'] as $v) {
                    $targets[] = [
                        'name' => $v['target_name'],
                        'count' => $v['required_amount'],
                    ];
                }
            }
        }

        $data['task'] = $task;
        $data['ptask'] = $ptask;
        $data['task_description'] = $task->fromDesc;
        $data['items'] = $items;
        $data['targets'] = $targets;
        $data['no_op'] = true;
        $this->display('task-summary', $data);
    }

    /**
     * @param $task
     * @return array
     */
    protected function getTaskItems($task): array
    {
        $db = $this->game->db;
        $items = [];

        if ($task->toItem != ''){
            foreach (explode(',', $task->toItem) as $v) {
                $itemArr = explode('|', $v);
                $itemId = $itemArr[0];
                $itemCount = $itemArr[1] ?? $task->toItemCount;
                $rwdj = \player\getItem($this->db(), $itemId);
                $djinfo = $this->encode("cmd=djinfo&djid=$rwdj->id");
                $items[] = [
                    'name' => $rwdj->name,
                    'ui_name' => $rwdj->uiName,
                    'info_link' => $djinfo,
                    'class' => '',
                    'link_class' => sprintf('quality-%d', $rwdj->quality),
                    'count' => $itemCount
                ];
            }
        }
        if ($task->exp > 0){
            $items[] = [
                'name' => '经验',
                'count' => $task->exp
            ];
        }
        if ($task->money > 0){
            $items[] = [
                'name' => '金币',
                'count' => $task->money
            ];
        }
        $targets = [];
        $itemIdMap = [];
        $monsterIdMap = [];
        // 新增 Lua 动态条件，适用于动态条件，比如随机道具日常
        if (!empty($task->lua)) {
            // 优先从 session 中取值
            $key = "lua_items_{$task->id}";
            if (!empty($this->session[$key])) {
                $tmpItems = $this->session[$key];
            } else {
                $exe = new Condition($this->db(), $this->uid());
                $tmpItems = $exe->execute($task->lua);
                $this->session[$key] = $tmpItems;
                unset($exe);
            }
            if (is_array($tmpItems) && !empty($tmpItems)) {
                foreach ($tmpItems as $v) {
                    if (count($v) < 2) {
                        continue;
                    }
                    if ($task->type == 1) {
                        $itemIdMap[$v[1]] = $v[2];
                    } else {
                        $monsterIdMap[$v[1]] = $v[2];
                    }
                }
            }
        }
        switch ($task->type){
            case 1://收集
                foreach (explode(',', $task->item) as $v) {
                    if (empty($v)) {
                        continue;
                    }
                    $arr = explode('|', $v);
                    $itemIdMap[$arr[0]] = $arr[1] ?? $task->itemCount;
                }
                foreach ($itemIdMap as $k => $v) {
                    $rwyq = \player\getItem($this->db(), $k);
                    $targets[] = [
                        'id' => $k,
                        'name' => $rwyq->name,
                        'count' => $v
                    ];
                }
                break;
            case 2://打怪
                foreach (explode(',', $task->monster) as $v) {
                    if (empty($v)) {
                        continue;
                    }
                    $arr = explode('|', $v);
                    $monsterIdMap[$arr[0]] = $arr[1] ?? $task->monsterCount;
                }
                foreach ($monsterIdMap as $k => $v) {
                    $rwyq = \player\getGuaiwu($k,$db);
                    $targets[] = [
                        'id' => $k,
                        'name' => $rwyq->name,
                        'count' => $v
                    ];
                }
                break;
            case 3://对话
                $tjnpc = \player\getnpc($task->toId,$db);
                $targets[] = ['name' => $tjnpc->name];
                break;
        }

        return [$items, $targets];
    }
}