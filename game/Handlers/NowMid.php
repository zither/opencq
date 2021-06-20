<?php
namespace Xian\Handlers;

use Medoo\Medoo;
use player\Player;
use Xian\AbstractHandler;
use Xian\Condition;
use Xian\Helper;
use Xian\Object\Location;
use Xian\Object\PlayerPartyMember;
use Xian\Player\PrivateItem;
use function player\getPlayerById;

class NowMid extends AbstractHandler
{
    use CommonTrait;

    public function displayNowMid()
    {
        $db = $this->game->db;
        //获取玩家信息
        $player = \player\getPlayerById($db, $this->uid());
        $encode = $this->encoder;
        //模板变量
        $data = [];
        $clmid = \player\getmid($player->nowmid,$db); //获取地图信息
        $data['pvphtml'] = $clmid->ispvp? '危险' : '安全';
        $data['gonowmid'] = $encode->encode("cmd=gomid&newmid=$clmid->mid");
        $data['player'] = $player;
        $location = Location::get($db, $clmid->mid);
        $directions = [
            '北' => $location->up > 0 ? Location::get($db, $location->up) : '',
            '西' => $location->left > 0 ? Location::get($db, $location->left) : '',
            '东' => $location->right > 0 ? Location::get($db, $location->right) : '',
            '南' => $location->down > 0 ? Location::get($db, $location->down) : '',
        ];
        foreach ($directions as $k => $v) {
            if (empty($v)) {
                unset($directions[$k]);
                continue;
            }
            switch ($k) {
                case '北':
                    $v->name .= ' ↑';
                    break;
                case '西':
                    $v->name .= ' ←';
                    break;
                case '东':
                    $v->name .= ' →';
                    break;
                case '南':
                    $v->name .= ' ↓';
                    break;

            }
        }
        // 刷新怪物
        $this->refresh($player, $clmid, $db);
        $cxallguaiwu = $db->select('midguaiwu', ['[>]guaiwu' => ['gid' => 'id']], [
            'midguaiwu.id',
            'midguaiwu.gid',
            'guaiwu.type',
            'guaiwu.name',
            'guaiwu.mingzhong',
            'guaiwu.is_aggressive',

        ], [
            'OR' => [
                'AND #public' => [
                    'mid' => $player->nowmid,
                    'uid' => 0
                ],
                'AND #private' => [
                    'mid' => $player->nowmid,
                    'uid' => $player->id,
                ]
            ]
        ]);
        $data['gw_array'] = [];
        for ($i = 0;$i < count($cxallguaiwu); $i++){
            $gwcmd = $encode->encode("cmd=getginfo&gid=".$cxallguaiwu[$i]['id']."&gyid=".$cxallguaiwu[$i]['gid']."&nowmid=$player->nowmid");
            $data['gw_array'][] = [
                'cmd' => $gwcmd,
                'id' => $cxallguaiwu[$i]['id'],
                'name' => $cxallguaiwu[$i]['name'],
                'type' => $cxallguaiwu[$i]['type'],
                'mingzhong' => $cxallguaiwu[$i]['mingzhong'],
                'is_aggressive' => $cxallguaiwu[$i]['is_aggressive'],
            ];
        }

        // 队员不触发遇怪事件
        if ($this->isFollower) {
            foreach ($data['gw_array'] as $v) {
                // 命中率的5%作为遇怪率
                $rate = ($v['mingzhong'] / $player->shanbi) * 5;
                if ($v['is_aggressive'] || rand(1, 100) <= $rate) {
                    $this->doRawCmd("cmd=begin-pve&gid={$v['id']}");
                }
            }
        }
        $cxallplayer = $db->select('game1', '*', [
            'nowmid' => $player->nowmid,
            'sfzx' => 1,
            'id[!]' => $player->id
        ]);
        $data['players'] = [];
        if (!empty($cxallplayer)){
            $nowdate = date('Y-m-d H:i:s');
            for ($i = 0;$i<count($cxallplayer);$i++){
                $currentPlayer = [];
                if ($cxallplayer[$i]['name']!=""){
                    $cxtime = $cxallplayer[$i]['endtime'];
                    $cxuid = $cxallplayer[$i]['id'];
                    $cxuname = $cxallplayer[$i]['name'];
                    $vip = $cxallplayer[$i]['vip'];
                    $second=floor((strtotime($nowdate)-strtotime($cxtime))%86400);//获取刷新间隔
                    if ($second > 300){
                        $db->update('game1', ['sfzx' => 0], ['id' => $cxuid]);
                    }else{
                        $clubp = \player\getclubplayer_once($db, $cxuid);
                        if ($clubp){
                            $club = \player\getclub($clubp->clubid,$db);
                            $club->clubname ="[$club->clubname]";
                        }else{
                            $club = new \player\club();
                            $club->clubname ="";
                        }
                        $currentPlayer['uid'] = $cxuid;
                        $currentPlayer['club'] = $club->clubname;
                        $currentPlayer['name'] = $cxuname;
                        $currentPlayer['vip'] = $vip;
                    }
                }
                if (!empty($currentPlayer)) {
                    $data['players'][] = $currentPlayer;
                }
            }
        }
        $data['taskcount'] = \player\countPlayerUnfinishedTasks($db, $player->id);
        $data['npc'] = [];
        if ($clmid->mnpc !=""){
            $mnpc = explode(',', $clmid->mnpc);
            $cxnpcall = \player\getAllValidNpc($player->id, $clmid->mid, $mnpc, $this->game->db);
            // 获取当前所有npc的任务
            $npcIds = [];
            foreach ($cxnpcall as $v) {
                $npcIds[] = $v['id'];
            }
            $npcTasks = \player\getNpcTasks($db, $npcIds);
;
            for ($i=0;$i < count($cxnpcall);$i++){
                $currentNpc = [];
                $currentNpc['name'] = $nname = $cxnpcall[$i]['name'];
                $currentNpc['id'] = $nid = $cxnpcall[$i]['id'];
                $currentNpc['cmd'] = $npccmd = $encode->encode("cmd=npc&nid=$nid&sid=$player->sid");
                $currentNpc['tasks'] = [];
                // 判断任务
                if (!empty($npcTasks)) {
                    foreach ($npcTasks as $v) {
                        // 过滤与当前NPC无关的任务
                        if ($v['from_id'] != $nid && $v['to_id'] != $nid) {
                            continue;
                        }
                        // 玩家等级未达到或者超过任务等级都直接跳过
                        if ($player->level < $v['level'] || ($v['max_level'] > 0 && $player->level > $v['max_level'])) {
                            continue;
                        }

                        $playerTask = \player\getPlayerTaskById($this->db(), $v['id'], $player->id);
                        // 任务已完成，但是任务属于循环任务，日常，周长，判断完成
                        if ($playerTask->id && $playerTask->status == 3 && $v['is_loop']) {
                            switch ($v['mode']) {
                                case 3:
                                    // 完成日期为当天，直接跳过
                                    if (date('Ymd') === date('Ymd', strtotime($playerTask->updatedAt))) {
                                        continue 2;
                                    }
                                    // 删除之前的任务信息
                                    \player\deletePlayerTaskById($this->db(), $playerTask->id, $this->uid());
                                    $playerTask->id = 0;
                                    break;
                                default:
                                    continue 2;
                            }
                        }

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
                        $currentNpc['tasks'][] = !$playerTask->id ? 'tan' : 'wen';
                    }
                }
                $data['npc'][] = $currentNpc;
            }
        }

        $data['notifications'] = $this->getImMessages($player);
        $data['directions'] = $directions;
        $data['location'] = $location;
        $data['clmid'] = $clmid;
        if (!empty($clmid->ornaments)) {
            $ornaments = $db->select('ornament', '*', ['id' => explode(',', $clmid->ornaments)]);
            $checker = new Condition($this->db(), $this->uid());
            foreach ($ornaments as $k => $v) {
                if (empty($v['show_condition'])) {
                    continue;
                }
                $con = $db->get('condition', '*', ['id' => $v['show_condition']]);
                if (empty($con)) {
                    continue;
                }
                list($r, $info) = $checker->validate($con);
                if (!$r) {
                    unset($ornaments[$k]);
                }
            }
            $data['ornaments'] = $ornaments;
        }
        $data['isAdmin'] = $this->session['is_admin'];
        $this->display('nowmid', $data);
    }

    /**
     * 移动到新的地点
     */
    public function moveToMid()
    {
        $player = getPlayerById($this->db(), $this->uid(), true);
        $newmid = $this->params['mid'] ?? 0;
        if (!$newmid || $player->nowmid == $newmid) {
            $this->doRawCmd($this->game->event->lastAction());
        }
        // 获取团队成员
        if ($this->isParty) {
            $members = $this->getValidPartyMembers($player->partyId);
        }
        $lastmid = $player->nowmid;
        if ($player->hp <= 0) {
            // 重伤状态不显示突破和 PVP 信息
            unset($this->data['tupo'], $this->data['pvp']);
            $retmid = \player\getmid($player->nowmid, $this->db());
            $retqy = \player\getqy($retmid->mqy, $this->db());
            $newmid = $retqy->mid;
        } else {
            if ($this->isFollower) {
                $this->flash->error('你正处于跟随状态，无法自由移动');
                $this->doRawCmd($this->game->event->lastAction());
            }
            //获取即将走的地图信息
            $clmid = \player\getmid((int)$newmid, $this->db());
            // 检查进入地图的条件
            if (!empty($clmid->enterCondition)) {
                $con = $this->db()->get('condition', '*', ['id' => $clmid->enterCondition]);
                if (!$this->isParty || ($this->isParty && !$this->isLeader && !$this->isFollower)) {
                    $checker = new Condition($this->db(), $this->uid());
                    list($success, $info) = $checker->validate($con);
                    if (!$success) {
                        if (!empty($info)) {
                            $this->flash->error($info);
                        }
                        $this->doRawCmd($this->game->event->lastAction());
                    }
                } else {
                    // 如果时组队状态，则需要检查每个队员的条件
                    foreach ($members ?? [] as $v) {
                        $checker = new Condition($this->db(), $v->uid);
                        list($success, $info) = $checker->validate($con);
                        if (!$success) {
                            if (!empty($info)) {
                                $this->flash->error($info);
                            }
                            $this->doRawCmd($this->game->event->lastAction());
                        }
                    }
                }
            }

            // 获取目前的地点信息
            $previousMid = \player\getmid($lastmid, $this->db());
            // 切换区域时需要判断是否进入副本
            if ($previousMid->mqy != $clmid->mqy) {
                $area = $this->db()->get('qy', ['type'], ['qyid' => $clmid->mqy]);
                // 传送副本时需要处理一些前置条件
                if ($area['type'] == 3) {
                    $this->processDungeon($player, $clmid->mqy, $this->isLeader ? $members : []);
                }
            }
            $playerinfo = $player->name . "向{$clmid->mname}走去";
            if ($playerinfo != $clmid->playerinfo) {
                $this->db()->update('mid', ['playerinfo' => $playerinfo], ['mid' => $lastmid]);
            }
        }

        $this->changeUserMid($player, $newmid, $this->isLeader ? $members : []);
        $this->doRawCmd('cmd=gomid');
    }


    /**
     * 刷新地图怪物
     * 区分地图公共怪物和私有怪物，私有怪物为玩家独占
     *
     * @param $player
     * @param $clmid
     * @param Medoo $db
     */
    protected function refresh($player, $clmid, Medoo $db)
    {
        $areaMonster = $this->db()->get('area_monster', '*', ['area_id' => $clmid->mqy]);
        // 当前地图没有怪物
        if (empty($clmid->mgid) && empty($areaMonster)) {
            return;
        }
        $nowdate = date('Y-m-d H:i:s');
        $retgw = explode(",", $clmid->mgid);
        // 区域类型
        $area = $this->db()->get('qy', ['type'], ['qyid' => $clmid->mqy]);
        // 刷怪的 ID 和数量
        $midMonsters = [];
        if (!empty($clmid->mgid)) {
            foreach ($retgw as $v) {
                list($vid, $vCount) = explode('|', $v);
                $midMonsters[$vid] = $vCount;
            }
        }
        // 增加区域怪物
        if (!empty($areaMonster)) {
            $areaMonsters = explode(',', $areaMonster['monster_ids']);
            foreach ($areaMonsters as $v) {
                if (!isset($midMonsters[$v])) {
                    $midMonsters[$v] = 5;
                }
            }
        }

        // 获取怪物数据，并区分共有和私有
        $publicMonsterIds = [];
        $privateMonsterIds = [];
        $monsters = $db->select('guaiwu', '*', ['id' => array_keys($midMonsters)]);
        $map = [];
        foreach ($monsters as $v) {
            $map[$v['id']] = $v;
            if ($v['flags'] & \player\Guaiwu::FLAG_PRIVATE) {
                $privateMonsterIds[] = $v['id'];
            } else {
                $publicMonsterIds[] = $v['id'];
            }
        }

        // 需要刷新的怪物 ID
        $needRefresh = [];
        // 是否刷新公共怪物
        $refreshPublicMonsters = false;
        // 是否刷新私有怪物
        $refreshPrivateMonsters = false;

        //  检查公共怪刷新时间
        if (!empty($publicMonsterIds)) {
            $publicTime = floor((strtotime($nowdate) - strtotime($clmid->mgtime)) % 86400);
            if ($publicTime > $clmid->ms) {
                $count = $db->count('midguaiwu', [
                    'OR' => [
                        'AND #public' => [
                            'mid' => $player->nowmid,
                            'uid' => 0,
                            'gid' => $publicMonsterIds,
                        ],
                        'AND #private' => [
                            'mid' => $player->nowmid,
                            'uid' => $player->id,
                            'gid' => $publicMonsterIds,
                        ]
                    ]
                ]);
                if ($count === 0) {
                    $refreshPublicMonsters = true;
                    $needRefresh = array_merge($needRefresh, $publicMonsterIds);
                }
            }
        }
        // 私有怪不共享刷新时间
        if (!empty($privateMonsterIds)) {
            $condition = [
                'uid' => $player->id,
                'type' => PrivateItem::TYPE_MONSTER_FRESH_TIME,
                'k' => $clmid->mid,
                'area_id' => $area['type'] == 3 ? $clmid->mqy : 0,
            ];
            $freshTime = $db->get('player_private_items', '*', $condition);

            // 副本怪物只刷新一次，即　$area['type'] == 3 && !empty($freshTime) 时不刷新
            if ($area['type'] != 3 || empty($freshTime)) {
                $freshDate = empty($freshTime) ? '1977-01-01 00:00:00' : $freshTime['v'];
                // 86400 = 24 * 60 * 60
                $privateTime = floor((strtotime($nowdate) - strtotime($freshDate)) % 86400);//获取刷新间隔
                if ($privateTime > $clmid->ms) {
                    $count = $db->count('midguaiwu', [
                        'mid' => $player->nowmid,
                        'uid' => $player->id,
                        'gid' => $privateMonsterIds,
                    ]);
                    if ($count === 0) {
                        $refreshPrivateMonsters = true;
                        $needRefresh = array_merge($needRefresh, $privateMonsterIds);
                    }
                }
            }
        }

        // 总刷新数量
        $total = 5;
        shuffle($needRefresh);

        // 添加地图怪物
        foreach ($needRefresh as $k => $gid) {
            // 达到总数，直接退出
            if ($total <= 0) {
                break;
            }
            $guaiwu = $map[$gid];
            if ($guaiwu['flags'] & \player\Guaiwu::FLAG_ONCE) {
                // 是否限定数量
                if ($guaiwu['max_amount'] > 0) {
                    $count = $db->get('player_private_items', ['v'], [
                        'uid' => $player->id,
                        'type' => PrivateItem::TYPE_MONSTER_KILLED,
                        'k' => $gid
                    ]);
                    // 限定怪击杀后不再刷新
                    if ($count && $count['v'] >= $guaiwu['max_amount']) {
                        continue;
                    }
                }
            }

            if ($k + 1 == count($needRefresh)) {
                $currentCount = min($total, $midMonsters[$gid]);
            } else {
                $currentCount = rand(1, $midMonsters[$gid]);
                $currentCount = min($total, $currentCount);
            }
            $total -= $currentCount;

            for ($n = 0; $n < $currentCount; $n++) {
                $columns = [
                    'gid' => $guaiwu['id'],
                    'mid' => $player->nowmid,
                    'name' => $guaiwu['name'],
                    'level' => $guaiwu['level'],
                    'hp' => $guaiwu['hp'],
                    'maxhp' => $guaiwu['hp'],
                    'mp' => $guaiwu['mp'],
                    'maxmp' => $guaiwu['mp'],
                    'baqi' => $guaiwu['baqi'],
                    'wugong' => $guaiwu['wugong'],
                    'wufang' => $guaiwu['wufang'],
                    'fagong' => $guaiwu['fagong'],
                    'fafang' => $guaiwu['fafang'],
                    'mingzhong' => $guaiwu['mingzhong'],
                    'shanbi' => $guaiwu['shanbi'],
                    'baoji' => $guaiwu['baoji'],
                    'shenming' => $guaiwu['shenming'],
                    'exp' => $guaiwu['exp'],
                ];
                // 检查怪物是否是私有怪
                if ($guaiwu['flags'] & \player\Guaiwu::FLAG_PRIVATE) {
                    $columns['uid'] = $player->id;
                }
                $db->insert('midguaiwu', $columns);
            }
        }

        // 更新公共刷新时间
        if ($refreshPublicMonsters) {
            $db->update('mid', ['mgtime' => $nowdate], ['mid' => $player->nowmid]);
        }

        // 更新私有刷新时间
        if ($refreshPrivateMonsters) {
            if (!empty($freshTime)) {
                $db->update('player_private_items', ['v' => $nowdate], [
                    'uid' => $player->id,
                    'type' => PrivateItem::TYPE_MONSTER_FRESH_TIME,
                    'k' => $clmid->mid,
                    'area_id' => $area['type'] == 3 ? $clmid->mqy : 0,
                ]);
            } else {
                $db->insert('player_private_items', [
                    'uid' => $player->id,
                    'type' => PrivateItem::TYPE_MONSTER_FRESH_TIME,
                    'k' => $clmid->mid,
                    'v' => $nowdate,
                    'area_id' => $area['type'] == 3 ? $clmid->mqy : 0,
                ]);
            }
        }
    }

    protected function processDungeon(Player $player, int $areaId, array $partyMembers = [])
    {
        $ids = [];
        if (!empty($partyMembers)) {
            foreach ($partyMembers as $v) {
                $ids[] = $v->uid;
            }
        } else {
            $ids[] = $player->id;
        }
        // 删除副本相关的记录
        $this->db()->delete('player_private_items', [
            'uid' => $ids,
            'area_id' => $areaId,
        ]);
    }

    protected function getDirection($num)
    {
        $dir = [
            '西北','北','东北','西','中','东','西南','南','东南'
        ];
        return $dir[$num];
    }

    /**
     * 修改人物位置
     * @param Player $player
     * @param int $mid
     * @param PlayerPartyMember[] $members
     */
    protected function changeUserMid(Player $player, int $mid, array $members = [])
    {
        $ids = [];
        if (!empty($members)) {
            foreach ($members as $v) {
                $ids[] = $v->uid;
            }
        }
        if (!in_array($player->id, $ids)) {
            $ids[] = $player->id;
        }
        $this->db()->update('game1', ['nowmid' => $mid], ['id' => $ids]);
    }
}