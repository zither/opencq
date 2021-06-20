<?php

namespace Xian\Handlers;

use player\Player;
use Xian\AbstractHandler;
use Xian\Event;
use Xian\Helper;
use Xian\Object\Location;
use function player\addPlayerEquip;
use function player\addPlayerStackableItem;
use function player\getEquip;
use function player\getItem;
use function player\getMedicine;

class Npc extends AbstractHandler
{
    const WITH_TASKS = 0x01;
    const NO_TASKS = 0x02;

    /**
     * @var Player;
     */
    public $player;

    public function handle()
    {
        $data = $this->npcInfo();
        $npc = $data['npc'];
        $functions = [];
        if ($npc->muban != ''){
            $mubanitem =  explode(',',$npc->muban);
            foreach ($mubanitem as $muban){
                $f = $this->mubanInfo($muban);
                if (!empty($f)) {
                    $functions[] = $f;
                }
            }
        }

        $data['functions'] = $functions;
        $this->display('npc', $data);
    }

    /**
     * 治疗 NPC 模板
     */
    public function showHeal()
    {
        $data = $this->npcInfo(self::NO_TASKS);
        $npc = $data['npc'];

        $queries = [
            'cmd' => 'do-heal',
            'nid' => $npc->id,
        ];
        $xiaohao = round($this->player->level * 15.2);
        if (isset($this->params['pro'])) {
            $queries['pro'] = 1;
            $xiaohao = round($this->player->level*(8.2 + $this->player->level/2));
        }

        $data['xiaohao'] = $xiaohao;
        $data['cmd'] = $this->encoder->encode(http_build_query($queries));
        $this->display('npc/heal', $data);
    }

    public function doHeal()
    {
        $db = $this->game->db;
        $nid = $this->params['nid'];
        $player = \player\getPlayerById($db, $this->uid());
        $npc = \player\getOverrideNpc($player->id, $nid, $db);

        $queries = [
            'cmd' => 'show-heal',
            'nid' => $npc->id,
        ];
        $xiaohao = round($player->level * 15.2);
        if (isset($this->params['pro'])) {
            $queries['pro'] = 1;
            $xiaohao = round($player->level*(8.2 + $player->level/2));
        }
        $redirect = $this->encoder->encode(http_build_query($queries));

        if ($player->vip) {
            \player\changeplayersx('hp', $player->maxhp, $this->uid(), $db);
            $this->flash->push('message', "你的生命值已经恢复了！");
        } else {
            // 20级以下恢复全部生命
            if ($player->level <= 20) {
                $hp = $player->maxhp;
            } else if ($player->hp >= ($player->maxhp / 2)) {
                // 血量超过最大生命值一半时无需治疗
                $hp = 0;
            } else {
                // 其他情况最多恢复一半生命值
                $hp = $player->maxhp / 2;
            }
            $recovered = $hp - $player->hp;
            if ($hp > 0 && $recovered > 0) {
                \player\changeyxb($this->db(), 2, $xiaohao, $this->uid());
                \player\changeplayersx('hp', $hp, $this->uid(), $db);
                $this->flash->push('message', sprintf("你成功恢复了%d", $recovered));
            } else {
                $this->flash->error("你现在无需治疗");
            }
        }

        $this->doCmd($redirect);
    }

    /**
     * 显示药品商店列表
     */
    public function showShop()
    {
        $data = $this->npcInfo(self::NO_TASKS);
        $nid = $this->params['nid'];
        $type = (int)($this->params['type'] ?? 3);
        $subType = (int)($this->params['sub_type'] ?? 0);
        $condition = [
            'npc_shop_item.npc_id' => $nid,
            'npc_shop_item.item_type' => $type,
            'npc_shop_item.item_sub_type' => $subType,
            'npc_shop_item.is_launched' => 1,
        ];
        $yaopin = $this->db()->select('npc_shop_item', ['[>]item' => ['item_id' => 'id']], [
            'item.id',
            'item.name',
            'item.price',
            'item.recharge_price',
            'item.quality',
            'item.type',
        ], $condition);
        $data['yaopin'] = $yaopin;
        $this->display('npc/yaopin', $data);
    }

    public function showShopItemInfo()
    {
        $id = $this->params['id'] ?? 0;
        $data = [];
        $item = getItem($this->db(), $id);
        if ($item->type == 2) {
            $equip = getEquip($this->db(), $id);
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
            $data['yaopin'] = getMedicine($this->db(), $id);
        }
        $data['item'] = $item;
        $this->display('shop_item_info', $data);
    }

    public function doShop()
    {
        $id = $this->params['id'] ?? null;
        $count = $this->params['count'] ?? null;
        if (empty($count)) {
            $count = Helper::filterVar($this->postParam('count'), 'INT');
        }
        if (!$id || empty($count)) {
            $this->flash->error('非法请求');
            $this->doRawCmd($this->lastAction());
        }
        $player = \player\getPlayerById($this->db(), $this->uid(), true);
        $item = getItem($this->db(), $id);

        $price = $item->price * $count;
        // VIP 会员折扣
        if ($player->vip > 0) {
            $price = floor($price * 0.7);
        }
        $ret = \player\changeyxb($this->db(), 2,$price, $this->uid());
        if ($ret) {
            switch ($item->type) {
                case 2:
                    $loc = Location::get($this->db(), $player->nowmid);
                    $source = [
                        'location' => $loc->name,
                        'monster' => '商店',
                        'player' => $player->name,
                    ];
                    // 装备
                    $max = $count;
                    while ($max > 0) {
                        addPlayerEquip($this->db(), $this->uid(), ['id' => $item->id], $source);
                        $max--;
                    }
                    break;
                default:
                    //其他物品
                    addPlayerStackableItem($this->db(), $this->uid(), ['id' => $item->id, 'type' => $item->type], $count);
            }
            $message = sprintf("购买 %s(x%d) 成功", $item->name, $count);
        }else{
            $message = "灵石数量不足";
        }
        $this->flash->success($message);
        $this->doRawCmd($this->lastAction());
    }

    /**
     * 创建门派页面
     */
    public function showMenpai()
    {
        $data = $this->npcInfo(self::NO_TASKS);
        $data['action'] = $this->encoder->encode(sprintf('cmd=do-menpai&nid=%d', $this->params['nid']));
        $this->display('npc/menpai', $data);
    }

    /**
     * 创建门派
     */
    public function doMenpai()
    {
        $name = $this->postParam('clubname');
        $info = $this->postParam('clubinfo');
        $back = $this->encoder->encode(sprintf('cmd=show-menpai&nid=%d', $this->params['nid']));
        if (!$name || !$info) {
            $this->flash->set('message', '请完整填写门派信息');
            $this->doCmd($back);
        }
        $clubCmd = $this->encoder->encode("cmd=club");
        $player = \player\getPlayerById($this->game->db, $this->uid());
        $clubPlayer = \player\getclubplayer_once($this->db(), $player->id);
        if ($clubPlayer){
            $this->doCmd($clubCmd);
        }
        $clubName = htmlspecialchars($name);
        $clubInfo = htmlspecialchars($info);
        if (strlen($clubName) < 6 || strlen($clubName) > 12) {
            $this->flash->set('message', '名称过长或过短');
            $this->doCmd($back);
        }
        $this->game->db->insert('club', [
            'clubname' => $clubName,
            'clubinfo' => $clubInfo,
            'clublv' => 1,
            'clubexp' => 0,
            'clubno1' => $player->id,
        ]);
        $clubId = $this->game->db->id();
        $this->game->db->insert('clubplayer', [
            'clubid' => $clubId,
            'uid' => $player->id,
            'uclv' => 1
        ]);
        $this->doCmd($clubCmd);
    }

    /**
     * 配方列表
     */
    public function showPeifang()
    {
        $data = $this->npcInfo(self::NO_TASKS);
        $db = $this->game->db;
        $nid = $this->params['nid'];

        $data['peifang'] = [];
        $peifang = $this->game->db->select('peifang', '*');
        foreach ($peifang as $k => $v){
            $cur = [];
            $cur['id'] = $v['id'];
            $cur['name'] = $v['name'];
            $cur['info_link'] = $this->encoder->encode("cmd=pfinfo&pfid={$v['id']}");
            $cur['study_link'] = $this->encoder->encode("cmd=do-peifang&nid=$nid&pfid={$v['id']}");
            $pf = \player\getplayerpeifang($this->player->id, $v['id'], $db);
            $cur['done'] = !empty($pf);
            $data['peifang'][] = $cur;
        }

        $this->display('npc/peifang', $data);
    }

    /**
     * 学习配方
     */
    public function doPeifang()
    {
        $nid = $this->params['nid'];
        $back = $this->encoder->encode("cmd=show-peifang&nid=$nid");
        try {
            $pfid = $this->params['pfid'];
            $player = \player\getPlayerById($this->game->db, $this->uid(), true);
            \player\addPlayerPF($player->id, $pfid, $this->game->db);
            $pf = \player\getplayerpeifang($player->id, $pfid, $this->game->db);
            $message = sprintf('学习%s成功', $pf['name']);
        } catch (\Exception $e) {
            $message = $e->getMessage();
        }
        $this->flash->set('message', $message);
        $this->doCmd($back);
    }

    public function showConversation()
    {
        $data = $this->npcInfo(self::NO_TASKS);

        $nid = $this->params['nid'];
        $questions = $this->game->db->select('conversations', '*', [
            'type' => 1,
            'npc_id' => $nid,
            'npc_override_id' => $data['npc']->overrideId
        ]);
        $data['questions'] = [];
        if (!empty($questions)) {
            foreach ($questions as $q) {
                $q['cmd'] = $this->encode(sprintf('cmd=do-conversation&type=1&cid=%d&nid=%d', $q['id'], $nid));
                $data['questions'][] = $q;
            }
        }

        $data['free_cmd'] = $this->encode(sprintf('cmd=do-conversation&type=3&nid=%d', $nid));

        $this->display('npc/conversation', $data);
    }

    public function doConversation()
    {
        $db = $this->game->db;
        $data = $this->npcInfo(self::NO_TASKS);
        /** @var \player\Npc $npc */
        $npc = $data['npc'];


        $back = $this->encode(sprintf('cmd=show-conversation&nid=%d', $npc->id));
        $overrideId = $npc->overrideId ?: 0;
        $type = $this->params['type'] ?? null;
        if (empty($type) || $type == 2) {
            $this->doCmd($back);
        }
        // 自言自语
        if ($type == 3) {
            $conversations = $db->select('conversations', '*', [
                'type' => 3,
                'npc_id' => $npc->id,
                'npc_override_id' => $overrideId
            ]);
            if (empty($conversations)) {
                $this->doCmd($back);
            }
            $index = rand(0, count($conversations) - 1);
            $this->flash->set('conversation', $conversations[$index]['content']);
            $this->doCmd($back);
        }
        $questionId = $this->params['cid'] ?? null;
        if (empty($questionId)) {
            $this->doCmd($back);
        }
        $answer = $db->get('conversations', '*', [
            'parent_id' => $questionId,
            'type' => 2,
            'npc_id' => $npc->id,
            'npc_override_id' => $overrideId
        ]);
        if (empty($answer)) {
            $this->doCmd($back);
        }

        $this->flash->set('conversation', $answer['content']);
        $this->doCmd($back);
    }

    /**
     * 获取 NPC 相关信息
     * @param int $flag
     * @return array
     */
    protected function npcInfo(int $flag = self::WITH_TASKS): array
    {
        $db = $this->game->db;
        $encode = $this->encoder;
        $nid = $this->params['nid'] ?? null;
        $this->player = $player = \player\getPlayerById($db, $this->uid(), true);
        $npc = \player\getOverrideNpc($player->id, $nid, $db);

        if ($flag === self::WITH_TASKS) {
            $tasks = [];
            $npcTasks = \player\getNpcTasks($this->db(), [$nid]);
            if (!empty($npcTasks)) {
                foreach ($npcTasks as $v) {
                    // 玩家等级未达到或者超过任务等级都直接跳过
                    if ($player->level < $v['level'] || ($v['max_level'] > 0 && $player->level > $v['max_level'])) {
                        continue;
                    }
                    $playerTask = \player\getPlayerTaskById($this->db(), $v['id'], $player->id);
                    // 已接任务，但不可提交或者已完成都跳过
                    if ($playerTask->id && $playerTask->status != 1) {
                        continue;
                    }
                    // 已接任务，但该NPC是不是提交者
                    if ($playerTask->id && $playerTask->status == 1&& $v['to_id'] != $nid) {
                        continue;
                    }
                    // 未接任务
                    if (!$playerTask->id) {
                        // 该任务不是发布者，直接跳过
                        if ($v['from_id'] != $nid) {
                            continue;
                        }
                        // 检查前置任务
                        if (!empty($v['previous_task_ids'])) {
                            $previousIds = explode(',', $v['previous_task_ids']);
                            $count = \player\countPlayerCompletedTasksByIds($this->db(), $previousIds, $player->id);
                            // 跳过前置任务未完成的任务
                            if ($count < count($previousIds)) {
                                continue;
                            }
                        }
                    }

                    // 剩余情况需要显示任务，但仍需根据是否接取显示不同的图片
                    $rwcmd = $encode->encode("cmd=task&nid=$nid&rwid={$v['id']}");
                    $reztarr = array('主线', '支线', '日常', '周常');
                    $rwzttext = $reztarr[$v['mode'] - 1];
                    $image = $playerTask->id ? 'wen' : 'tan';
                    $tasks[] = [
                        'type' => $rwzttext,
                        'cmd' => $rwcmd,
                        'image' => $image,
                        'name' => $v['name']
                    ];
                }
            }
            $data['tasks'] = $tasks;
        }

        $gonowmid = $encode->encode("cmd=gomid&newmid=$player->nowmid");
        $data['gonowmid'] = $gonowmid;
        $data['npc'] = $npc;

        return $data;
    }

    /**
     * @param string $path
     * @return array
     */
    protected function mubanInfo(string $path): array
    {
        $nid = $this->params['nid'];
        switch ($path) {
            case '治疗':
                return [
                    'cmd'=> $this->encoder->encode("cmd=show-heal&nid=$nid"),
                    'text' => '基础治疗'
                ];
            case '治疗_级别1':
                return [
                    'cmd'=> $this->encoder->encode("cmd=show-heal&nid=$nid&pro=1"),
                    'text' => '高级治疗'
                ];
            case '配方':
                return [
                    'cmd' => $this->encoder->encode("cmd=show-peifang&nid=$nid"),
                    'text' => '学习配方',
                ];
            case '商店':
                return [
                    'cmd' => $this->encoder->encode("cmd=show-shop&nid=$nid&type=3"),
                    'text' => '购买药品',
                ];
            case '书店':
                return [
                    'cmd' => $this->encoder->encode("cmd=show-shop&nid=$nid&type=1&sub_type=2"),
                    'text' => '购买书籍',
                ];
            case '杂货铺':
                return [
                    'cmd' => $this->encoder->encode("cmd=show-shop&nid=$nid&type=1"),
                    'text' => '购买道具',
                ];
            case '武器店':
                return [
                    'cmd' => $this->encoder->encode("cmd=show-shop&nid=$nid&type=2"),
                    'text' => '购买装备',
                ];
            case '门派管理员':
                return [
                    'insert' => 'includes/club',
                    'cmd' => $this->encoder->encode("cmd=show-menpai&nid=$nid"),
                    'text' => '创建门派',
                ];
            case '对话':
                return [
                    'cmd' => $this->encoder->encode("cmd=show-conversation&nid=$nid"),
                    'text' => '对话',
                ];
        }
        return [];
    }
}