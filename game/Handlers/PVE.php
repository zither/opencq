<?php

namespace Xian\Handlers;

use Medoo\Medoo;
use player\MidGuaiwu;
use player\Player;
use Xian\AbstractHandler;
use Xian\Combat\Attacker;
use Xian\Combat\BaseEffect;
use Xian\Combat\Combat;
use Xian\Combat\Item;
use Xian\Combat\MonsterAttacker;
use Xian\Combat\PetAttacker;
use Xian\Combat\PlayerAttacker;
use Xian\Combat\Shortcut;
use Xian\Combat\Skill;
use Xian\Event;
use Xian\Game;
use Xian\Helper;
use Xian\Job\CheckTaskMonsterCondition;
use Xian\Job\LootExpAndMoney;
use Xian\Job\LootItem;
use Xian\Object\CombatStatus;
use Xian\Object\Location;
use Xian\Object\Pet;
use Xian\Object\PlayerParty;
use Xian\Player\PrivateItem;
use function player\istupo;
use Resque;

class PVE extends AbstractHandler
{
    use CombatTrait;

    use CommonTrait;

    /**
     * 战斗胜利
     */
    const PVE_VICTORY = 1;

    /**
     * 战斗失败，角色死亡
     */
    const PVE_DIED = 0;

    /**
     * 重伤状态，兼容编号
     */
    const PVE_INJURED = -1;

    /**
     * 逃跑
     */
    const PVE_RUNAWAY = -2;

    /**
     * 抢怪失败
     */
    const PVE_ATTACK_FAILED = -3;

    public function __construct(Game $game, array $params)
    {
        parent::__construct($game, $params);
        $this->template->registerFunction('hp', function (Attacker $attacker){
            return $this->session['combat_hps'][$attacker->getUniqueId()] ?? $attacker->hp;
        });
    }

    public function showPve()
    {
        $db = $this->game->db;
        $id = $this->params['id'];
        $player =  \player\getPlayerById($db, $this->uid());

        // 视图数据
        $data = [];
        $combatStatus = CombatStatus::get($this->db(), $id);
        if (!$combatStatus->id) {
            $this->event()->remove();
            $this->doRawCmd("cmd=gomid");
        }
        $guaiwu = \player\getMidGuaiwu($db, $combatStatus->defenderId);
        if (empty($guaiwu->gid)) {
            $this->event()->remove();
            $this->doRawCmd("cmd=gomid");
        }

        $skills = [
            ['name' => '技能键', 'type' => 1, 'id' => $player->jn1],
            ['name' => '技能键', 'type' => 1, 'id' => $player->jn2],
            ['name' => '技能键', 'type' => 1, 'id' => $player->jn3],
        ];
        for ($i = 1; $i <= 3; $i++) {
            $k = "jn$i";
            if ($player->$k != 0) {
                $jineng = \player\getPlayerSkillById($player->$k, $player->id, $db);
                if (!empty($jineng)) {
                    $skills[$i-1]['name'] = $jineng['name'];
                    if ($jineng['manual_id'] != $player->manualId) {
                        $skills[$i-1]['type'] = 0;
                    } else {
                        $skills[$i-1]['type'] = 2;
                    }
                }
            }
        }

        $yaopins = [
            ['name' => '物品键', 'type' => 1, 'id' => $player->yp1],
            ['name' => '物品键', 'type' => 1, 'id' => $player->yp2],
            ['name' => '物品键', 'type' => 1, 'id' => $player->yp3],
        ];
        for ($i = 1; $i <= 3; $i++) {
            $k = "yp$i";
            if ($player->$k != 0) {
                $yaopin = \player\getPlayerMedicine($this->db(), $player->$k);
                if ($yaopin->id) {
                    $yaopins[$i-1]['name'] = $yaopin->name;
                    if ($yaopin->amount > 0) {
                        $yaopins[$i - 1]['type'] = 2;
                    } else {
                        $yaopins[$i - 1]['type'] = 0;
                    }
                }
            }
        }
        try {
            $combat = $combatStatus->getCombat();
        } catch (\RuntimeException $e) {
            $combatStatus->delCombat();
            $this->flash->error('战斗数据损害，自动退出战斗');
            $this->event()->remove();
            $this->doRawCmd("cmd=gomid");
        }
        $pgjcmd = $this->encode("cmd=do-pve&id=$id");
        $fastcmd = $this->encode("cmd=do-pve&id=$id&fast=1");

        $data['combat'] = $combat;
        $data['combatStatus'] = $combatStatus;
        $data['attackers'] = $combat->attackers;
        $data['defenders'] = $combat->defenders;
        $data['logs'] = json_decode($combatStatus->logs, true);
        $data['guaiwu'] = $guaiwu;
        $data['player'] = $player;
        $data['pgjcmd'] = $pgjcmd;
        $data['fastcmd'] = $fastcmd;
        $data['skills'] = $skills;
        $data['medicines'] = $yaopins;
        $data['is_member'] = $this->isFollower;
        $data['notifications'] = $this->getImMessages($player);

        $this->display('pve', $data);
    }

    public function selectCombatSkill()
    {
        $db = $this->game->db;
        $player = \player\getPlayerById($db, $this->uid(), true);

        $manual = $db->get('player_manual', [
            'player_manual.id',
            'player_manual.uid',
            'player_manual.manual_id',
        ], [
            'player_manual.id' => $player->playerManualId,
        ]);

        $skills = $db->select('player_skill', [
            '[>]skills' => ['skill_id' => 'id']
        ], [
            'player_skill.id',
            'player_skill.skill_id',
            'player_skill.level',
            'player_skill.score',
            'skills.name',
            'skills.manual_id',
            'skills.equip_type',
        ], [
            'OR' => [
                'AND #manual_skills' => [
                    'player_skill.uid' => $player->id,
                    'skills.manual_id' => $manual['manual_id'],
                    'skills.in_combat' => 1,
                ],
                'AND #common_skills' =>[
                    'player_skill.uid' => $player->id,
                    'skills.manual_id' => 0,
                    'skills.in_combat' => 1,
                ],
            ]
        ]);

        $data = [];
        $data['skills'] = $skills;
        $data['player'] = $player;
        $data['cid'] = $this->params['cid'] ?? 0;
        $data['from'] = $this->params['from'] ?? 0;
        $data['is_member'] = $this->isFollower;

        $this->display('combat/select_skill', $data);
    }

    public function selectCombatItem()
    {
        $db = $this->game->db;
        $player = \player\getPlayerById($db, $this->uid(), true);
        $items = \player\getplayeryaopinall($this->db(), $player->id);
        $data = [];
        $data['items'] = $items;
        $data['player'] = $player;
        $data['cid'] = $this->params['cid'] ?? 0;
        $data['from'] = $this->params['from'] ?? 1;
        $data['is_member'] = $this->isFollower;
        $this->display('combat/select_item', $data);
    }

    public function beginPve()
    {
        $db = $this->game->db;
        $gid = $this->params['gid'] ?? 0;
        $player =  \player\getPlayerById($db, $this->uid());
        // 检查组队操作
        if ($this->isFollower) {
            $this->flash->error('你正处于跟随状态，无法自由战斗');
            $this->doRawCmd($this->event()->lastAction());
        }
        $players = [];
        if ($this->isLeader) {
            $members = $this->getValidPartyMembers($player->partyId);
            foreach ($members as $v) {
                $players[] = \player\getPlayerById($this->db(), $v->uid);
            }
        } else {
            $players = [$player];
        }
        $guaiwu = \player\getMidGuaiwu($this->db(), $gid);

        if ($guaiwu->mid != $player->nowmid) {
            $this->flash->error('怪物未出现在当前地点');
            $this->doRawCmd('cmd=gomid');
        }
        // 抢怪失败
        if (empty($guaiwu->id) || ($guaiwu->uid != $player->id && $guaiwu->uid != 0)) {
            $this->flash->error('怪物已被击杀');
            $this->doRawCmd('cmd=gomid');
        }
        if ($guaiwu->isGroup) {
            $group = $db->select('midguaiwu', ['[>]guaiwu' => ['gid' => 'id']], ['midguaiwu.id'], [
                'midguaiwu.mid' => $guaiwu->mid,
                'midguaiwu.uid' => 0,
                'guaiwu.is_group' => 1,
            ]);
            if (!empty($group)) {
                $groupMonsterIds = array_map(function ($v) {
                    return $v['id'];
                }, $group);
                $db->update('midguaiwu', ['uid' => $player->id], ['mid' => $guaiwu->mid, 'id' => $groupMonsterIds]);
            }
        } else {
            $db->update('midguaiwu', ['uid' => $player->id], ['id' => $gid]);
        }

        // 战斗前回血
        foreach ($players as $p) {
            if ($p->level <= ($p->vip ? 20 : 15)) {
                \player\changeplayersx('hp', $p->maxhp, $p->id, $db);
                $p->hp = $p->maxhp;
            }
        }

        // 宠物加血
        $petIds = [];
        foreach ($players as $p) {
            if ($p->cw) {
                $petIds[] = $p->cw;
            }
        }
        $petsMap = [];
        if (!empty($petIds)) {
            $this->db()->update('player_pet', [
                'hp' => Medoo::raw('maxhp')
            ], ['id' => $petIds]);
            $petsData = $this->db()->select('player_pet', '*', ['id' => $petIds, 'hp[>]' => 0]);
            foreach ($petsData as $v) {
                $petsMap[$v['id']] = Pet::fromArray($v);
            }
        }
        $attackers = [];
        foreach ($players as $p) {
            $attackers[] = $p;
            if ($p instanceof Player && $p->cw && isset($petsMap[$p->cw])) {
                $p->pet = $petsMap[$p->cw];
                $attackers[] = $petsMap[$p->cw];
            }
        }

        // 生成 PVE 战斗数据
        $combat = new Combat();
        foreach ($attackers as $attacker) {
            if ($attacker instanceof Player) {
                $currentAttacker = PlayerAttacker::fromPlayer($attacker);
                // 将装备附带的效果添加到角色
                $this->addEquipKeywordEffects($currentAttacker);
            } else if ($attacker instanceof Pet) {
                $currentAttacker = PetAttacker::fromPet($attacker);
            }
            $combat->addAttacker($currentAttacker);
        }
        // 添加防御方
        if ($guaiwu->isGroup) {
            $defenders = \player\getGroupMonsters($player->nowmid, $player->id, $db);
        } else {
            $defenders = [$guaiwu];
        }
        foreach ($defenders as $defender) {
            $currentAttacker = MonsterAttacker::fromMonster($defender);
            $combat->addDefender($currentAttacker);
        }
        $data = serialize($combat);
        $this->db()->insert('combat', [
            'attacker_id' => $player->id,
            'defender_id' => $guaiwu->id,
            'data' => $data,
            'type' => 1,
            'result_type' => 0,
        ]);
        $combatStatusId = $this->db()->id();

        foreach ($players as $p) {
            // 事件白名单
            $whiteList = [
                'do-pve',
                'do-member-pve',
                'runaway',
                'select-combat-item',
                'select-combat-skill',
            ];
            $this->event()->set($p->id, sprintf('cmd=pve&id=%d&nowmid=%d', $combatStatusId, $p->nowmid), $whiteList);
        }

        $this->doRawCmd('cmd=gomid');
    }

    public function doPve()
    {
        $db = $this->game->db;
        $id = $this->params['id'] ?? 0;
        $gid = $this->params['gid'] ?? 0;
        $begin = $this->params['begin'] ?? null;
        $fastAttack = isset($this->params['fast']) && $this->params['fast'];
        $player =  \player\getPlayerById($db, $this->uid(), true);
        // 检查组队操作
        if ($this->isFollower) {
            $this->flash->error('你正处于跟随状态，无法自由战斗');
            $this->doRawCmd('cmd=gomid');
        }

        $end = false;
        $zdjg = -1;
        $huode = [];

        $combatStatus = CombatStatus::get($this->db(), $id);
        if (!$combatStatus->id) {
            $this->doRawCmd("cmd=gomid");
        }
        $combat = $combatStatus->getCombat();
        $result = $this->combat($player, $combatStatus, $combat, $fastAttack);
        if (isset($result['zdjg'])) {
            $zdjg = $result['zdjg'];
        }
        $end = $result['end'];
        $huode = $result['loot'] ?? [];

        if ($end && isset($zdjg)) {
            $players = [];
            $attackersInfo = [];
            foreach ($combat->attackers as $a) {
                $object = $a->getRawObject();
                $attackersInfo[] = sprintf('%d|%s', $object->id, $object->name);
                if ($a instanceof PlayerAttacker) {
                    $players[] = $a->getRawObject();
                }
            }
            $defendersInfo = [];
            foreach ($combat->defenders as $d) {
                $object = $d->getRawObject();
                $defendersInfo[] = sprintf('%d|%s', $object->id, $object->name);
            }
            switch ($zdjg) {
                case 1:
                    $db->insert('pve_logs', [
                        'attackers' => implode(',', $attackersInfo),
                        'defenders' => implode(',', $defendersInfo),
                        'mid' => $player->nowmid,
                        'status' => self::PVE_VICTORY,
                        'notes' => json_encode($huode, JSON_UNESCAPED_UNICODE)
                    ]);
                    $logId = $db->id();
                    // 只有胜利的时候才检查任务条件
                    foreach ($players as $p) {
                        \player\updateTaskStatusWhenFinished($this->db(), $p->id);
                    }
                    break;
                case 0:
                    $db->insert('pve_logs', [
                        'attackers' => implode(',', $attackersInfo),
                        'defenders' => implode(',', $defendersInfo),
                        'mid' => $player->nowmid,
                        'status' => self::PVE_DIED,
                    ]);
                    $logId = $db->id();
                    break;
                case -1:
                    $logId = 1;
                    break;
            }
            // 战斗结束，删除战斗记录
            $combatStatus->delCombat();
            $logCmd = sprintf('cmd=pve-log&lid=%d', $logId);
            foreach ($players as $p) {
                $this->event()->remove($p->id);
                $this->event()->set($p->id, $logCmd, [], true);
            }
            $this->doRawCmd('cmd=gomid');
        }

        $this->doRawCmd(sprintf('cmd=pve&id=%d', $id));
    }

    protected function combat(Player $player, CombatStatus $combatStatus, Combat $combat, bool $fastAttack = false): array
    {
        $db = $this->game->db;
        $action = $this->params['action'] ?? null;
        $aid = $this->params['aid'] ?? null;

        // 挥砍
        $skillArr = $db->get('skills', '*', ['id' => 5]);
        $skillEffects = $db->select('skill_effects', '*', ['skill_id' => $skillArr['id']]);
        $armedSkill = new Skill($skillArr, $skillEffects);

        // 普通攻击
        $skillArr = $db->get('skills', '*', ['id' => 6]);
        $skillEffects = $db->select('skill_effects', '*', ['skill_id' => $skillArr['id']]);
        $normalSkill = new Skill($skillArr, $skillEffects);

        $skillArr = $db->get('skills', '*', ['id' => 2]);
        $skillEffects = $db->select('skill_effects', '*', ['skill_id' => 2]);
        $bite = new Skill($skillArr, $skillEffects);

        // 设置攻击方技能
        /** @var Attacker $attacker */
        foreach ($combat->attackers as $attacker) {
            // 设置宠物技能
            if ($attacker instanceof PetAttacker) {
                $petSkill = $this->selectPetSkill($attacker);
                $attacker->currentSkill = $petSkill ?: $bite;
                continue;
            }
            // 处理队员操作逻辑
            if ($attacker->getRawObject()->id != $player->id) {
                if (is_null($attacker->currentItem) && is_null($attacker->currentItem)) {
                    $action = 'auto';
                } else {
                    $action = null;
                }
                $aid = null;
            }
            // 未指定操作时尝试根据战斗策略选择
            if ($action == 'auto') {
                $selection = $this->autoCombatSelection($attacker, $combat);
                // 有对应的条件
                if ($selection['type']) {
                    $action = $selection['type'] == 1 ? 'skill' : 'item';
                    $aid = $selection['selected_id'];
                }
            }
            $role = $attacker->getRawObject();
            // 指定战斗操作
            if ($action === 'skill' && $attacker->cd == 0) {
                $skillArr = \player\getPlayerSkillById($aid, $role->id, $db);
                $skillEffects = $db->select('skill_effects', '*', ['skill_id' => $skillArr['skill_id']]);
                // 根据技能等级，修正效果参数
                foreach ($skillEffects as &$v) {
                    $v['amount'] = ceil($v['amount'] * (1 + Helper::SKILL_LEVEL_RATE * ($skillArr['level'] - 1)));
                }
                $skill = new Skill($skillArr, $skillEffects);
                $attacker->currentSkill = $skill;
                // 设置技能调息时间
                $attacker->cd = $skill->cd;
                // 技能升级
                $this->upSkillExp($skillArr);
                $attacker->setSkillStatus($aid, $combat->currentRound);
                //@todo delete
            } else if ($action == 'item') {
                $playeryp = \player\getPlayerMedicine($this->db(), $aid);
                if ($playeryp->amount <= 0) {
                    $this->flash->set('message', sprintf('药品%s数量不足', $playeryp->name));
                } else {
                    \player\delYaopinById($role->id, $aid, 1, $db);
                    $itemEffects = $db->select('medicine_effects', '*', ['item_id' =>$playeryp->itemId]);
                    $item = new Item((array)$playeryp, $itemEffects);
                    $attacker->currentItem = $item;
                    $attacker->setItemStatus($aid, $combat->currentRound);
                }
            }
            if (is_null($attacker->currentSkill) && is_null($attacker->currentItem)){
                $attacker->currentSkill = $role->tool1 ? $armedSkill : $normalSkill;
            }
        }

        /** @var Attacker $defender */
        foreach ($combat->defenders as $defender) {
            $skillIds = $defender->getRawObject()->skills;
            if (empty($skillIds)) {
                $defender->currentSkill = $bite;
                continue;
            }
            $idArr = explode(',', $skillIds);
            // 如果当前在技能 CD 中，使用默认技能，即技能列表第一个
            if ($defender->cd > 0) {
                $defender->currentSkill = $this->getSkillById($idArr[0]);
                continue;
            }
            // 暂时随机选取技能
            shuffle($idArr);
            $defender->currentSkill = $this->getSkillById($idArr[0]);
            $defender->cd = $defender->currentSkill->cd;
        }

        $combat->fight($fastAttack);

        $end = $combat->end;
        // 保存角色状态
        /** @var Attacker $attacker */
        foreach ($combat->attackers as $attacker) {
            $object = $attacker->getRawObject();
            if ($attacker instanceof PlayerAttacker) {
                $db->update('game1', ['hp' => $object->hp], ['id' => $object->id]);
                // 清除上次操作物品
                $attacker->currentSkill = null;
                $attacker->currentItem = null;
            } else if ($attacker instanceof PetAttacker) {
                $db->update('player_pet', ['hp' => $object->hp], ['id' => $object->id]);
            }
        }
        if ($end) {
            $loot = [];
            if ($combat->won()) {
                foreach ($combat->defenders as $defender) {
                    $guaiwu = $defender->getRawObject();
                    if (!isset($loot[$guaiwu->id])) {
                        $loot[$guaiwu->id] = [];
                    }
                    if ($guaiwu instanceof MidGuaiwu) {
                        // 检查怪物是否为限定怪，击杀数量之后不再刷新
                        if ($guaiwu->isOnce()) {
                            $exists = $db->get('player_private_items', ['id'], [
                                'uid' => $player->id,
                                'type' => PrivateItem::TYPE_MONSTER_KILLED,
                                'k' => $guaiwu->gid,
                            ]);
                            if ($exists) {
                                $db->update('player_private_items', ['v[+]' => 1], ['id' => $exists['id']]);
                            } else {
                                $db->insert('player_private_items', [
                                    'uid' => $player->id,
                                    'type' => PrivateItem::TYPE_MONSTER_KILLED,
                                    'k' => $guaiwu->gid,
                                    'v' => 1
                                ]);
                            }
                        }
                    }
                }
                $loot = $this->searchLoot($player, $combat);
            }
            // 删除已死亡的怪物
            $gids = array_map(function ($g) {
                if ($g->hp > 0) {
                    return 0;
                }
                return $g->getRawObject()->id;
            }, $combat->defenders);
            $gids = array_filter($gids, function ($id) {
                return $id > 0 ;
            });
            if (!empty($gids)) {
                $db->delete('midguaiwu', ['id' => $gids, 'uid' => $player->id]);
            }

            // 删除已死亡的宠物
            $petIds = [];
            // 全部成员编号
            $allMemberIds = [];
            // 战死成员编号
            $deadMemberIds = [];
            // 队员编号
            $otherMemberIds = [];
            foreach ($combat->attackers as $a) {
                if ($a instanceof PlayerAttacker) {
                    $roleId = $a->getRawObject()->id;
                    $allMemberIds[] = $roleId;
                    if ($a->hp < 1) {
                        $deadMemberIds[] = $roleId;
                    }
                    if ($roleId != $this->uid()) {
                        $otherMemberIds[] = $roleId;
                    }
                }
                // 战死宠物编号
                if ($a instanceof PetAttacker && $a->hp < 1) {
                    $petIds[] = $a->getRawObject()->id;
                }
            }
            if (!empty($petIds)) {
                $db->delete('player_pet', ['id' => $petIds]);
            }

            // 处理战死回城逻辑
            if (!empty($deadMemberIds)) {
                $mid = $db->get('mid', ['[>]qy' => ['mqy' => 'qyid']], [
                    'mid.mid(id)',
                    'qy.mid'
                ], [
                    'mid.mid' => $player->nowmid
                ]);
                // 队长战死
                if (in_array($this->uid(), $deadMemberIds)) {
                    $db->update('game1', ['nowmid' => $mid['mid']], ['id' => $allMemberIds]);
                    $idsToRelease = $otherMemberIds;
                } else {
                    $db->update('game1', ['nowmid' => $mid['mid']], ['id' => $deadMemberIds]);
                    $idsToRelease = $deadMemberIds;
                }

                if (!empty($idsToRelease)) {
                    // 把所有死亡队友标记为非跟随状态
                    $db->update('player_party_member', ['status' => 1], [
                        'party_id' => $player->partyId,
                        'status' => 2,
                        'uid' => $idsToRelease
                    ]);
                }
            }

            // 保存战斗结果
            if ($combat->result === Combat::COMBAT_FAILED) {
                $resultType = 2;
            } else {
                $resultType = 1;
            }
            $this->db()->update('combat', ['result_type' => $resultType], ['id' => $combatStatus->id]);
        } else {
            foreach ($combat->defenders as $defender) {
                $guaiwu = $defender->getRawObject();
                $db->update('midguaiwu', ['hp' => $guaiwu->hp], ['id' => $guaiwu->id, 'uid' => $player->id]);
            }

            // 保存战斗状态
            $logs = $combat->logs();
            $combat->clearLogs();
            $this->db()->update('combat', [
                'data' => serialize($combat),
                'logs' => json_encode($logs, JSON_UNESCAPED_UNICODE),
            ], ['id' => $combatStatus->id]);
        }

        $data['end'] = $end;
        if ($end) {
            $data['zdjg'] = $combat->result;
            $data['loot'] = $loot;
        }

        return $data;
    }

    protected function getSkillById(int $id)
    {
        $skillArr = $this->game->db->get('skills', '*', ['id' => $id]);
        $skillEffects = $this->game->db->select('skill_effects', '*', ['skill_id' => $id]);
        return new Skill($skillArr, $skillEffects);
    }

    protected function setCombatInitHps(Combat $combat)
    {
        $hps = [];
        /** @var Attacker $attacker */
        foreach (array_merge($combat->attackers, $combat->defenders) as $attacker) {
            $hps[$attacker->getUniqueId()] = $attacker->hp;
        }
        $this->session['combat_hps'] = $hps;
    }

    protected function getShortcutSlot(Player $player)
    {
        $action = $this->params['action'] ?? null;
        if ($action == 'usejn') {
            $id = $this->params['jnid'] ?? null;
            if (empty($id)) {
                return 0;
            }
            if ($player->jn1 == $id) {
                return 1;
            }
            if ($player->jn2 == $id) {
                return 2;
            }
            if ($player->jn3 == $id) {
                return 3;
            }
        } else if ($action == 'useyp') {
            $id = $this->params['ypid'] ?? null;
            if (empty($id)) {
                return 0;
            }
            if ($player->yp1 == $id) {
                return 4;
            }
            if ($player->yp2 == $id) {
                return 5;
            }
            if ($player->yp3 == $id) {
                return 6;
            }
        }
        return 0;
    }

    protected function getPlayerShortcuts(Player $player): array
    {
        $db = $this->game->db;
        $shortcuts = array_fill(0, 6, []);
        $skillIds = array_filter([$player->jn1, $player->jn2, $player->jn3], function ($id) {
            return $id > 0;
        });
        if (!empty($skillIds)) {
            $skills = $db->select('playerjineng', [
                'id',
                'jnname(name)',
                'jngj(e_attack)',
                'jnfy(e_defense)',
                'jnbj(e_critical_chance)',
                'jnxx(e_vampire_chance)',
                'jncount(count)'
            ], ['id' => $skillIds]);
            $all = [];
            foreach ($skills as $v) {
                $v['type'] = Shortcut::TYPE_SKILL;
                $all[$v['id']] = $v;
            }
            if ($player->jn1) {
                $shortcuts[0] = $all[$player->jn1];
            }
            if ($player->jn2) {
                $shortcuts[1] = $all[$player->jn2];
            }
            if ($player->jn3) {
                $shortcuts[2] = $all[$player->jn3];
            }
        }
        $itemIds = array_filter([$player->yp1, $player->yp2, $player->yp3], function ($id) {
            return $id > 0;
        });
        if (!empty($itemIds)) {
            $items = $db->select('playeryaopin', [
                'id',
                'ypid',
                'ypname(name)',
                'yphp(e_hp)',
                'ypgj(e_attack)',
                'ypfy(e_defense)',
                'ypbj(e_critical_chance)',
                'ypxx(e_vampire_chance)',
                'ypsum(count)'
            ], ['id' => $itemIds]);
            $ypIds = array_map(function($v){
                return $v['ypid'];
            }, $items);

            // 获取药品的效果
            $effects = $db->select('yaopin_effects', '*', ['yid' => $ypIds, 'is_attribute' => 1]);
            $effectsMap = [];
            foreach ($effects as $effect) {
                $yid = $effect['yid'];
                if (!isset($effectsMap[$yid])) {
                    $effectsMap[$yid] = [];
                }
                $effectsMap[$yid][] = $effect;
            }
            $all = [];
            foreach ($items as $v) {
                $arr = [
                    'id' => $v['id'],
                    'type' => Shortcut::TYPE_ITEM,
                    'name' => $v['name'],
                    'count' => $v['count'],
                ];
                $vEffects = $effectsMap[$v['ypid']];
                foreach ($vEffects as $e) {
                    switch ($e['k']) {
                        case 'uhp':
                            $arr['e_hp'] = $e['v'];
                            break;
                        case 'ugj':
                            $arr['e_attack'] = $e['v'];
                            break;
                        case 'ufy':
                            $arr['e_defense'] = $e['v'];
                            break;
                        case 'ubj':
                            $arr['e_critical_chance'] = $e['v'];
                            break;
                        case 'uxx':
                            $arr['e_vampire_chance'] = $e['v'];
                            break;
                    }
                }
                $all[$v['id']] = $arr;
            }
            if ($player->yp1) {
                $shortcuts[3] = $all[$player->yp1];
            }
            if ($player->yp2) {
                $shortcuts[4] = $all[$player->yp2];
            }
            if ($player->yp3) {
                $shortcuts[5] = $all[$player->yp3];
            }
        }

        return $shortcuts;
    }

    /**
     * 怪物装备道具掉落逻辑
     * @param Player $player
     * @param Combat $combat
     * @return array
     */
    protected function searchLoot(Player $player, Combat $combat)
    {
        $resque = $this->game->container->get('resque');
        $db = $this->game->db;

        $allLoots = [];
        // 一次性获取怪物掉落所有信息
        $gids = [];
        foreach ($combat->defenders as $defender) {
            if ($defender instanceof MonsterAttacker) {
                $gids[] = $defender->getRawObject()->gid;
            }
        }
        $gids = array_unique($gids);
        // 没有相关的怪物编号，直接跳过后面的所有逻辑
        if (empty($gids)) {
            return;
        }

        $area = $this->getAreaInfoByMid($player->nowmid);
        $conditions = ['loot.monster_id' => $gids];
        if (!empty($area)) {
            // 添加区域的公共掉落
            $conditions = [
                'OR' => [
                    'loot.monster_id' => $gids,
                    'AND' => [
                        'loot.area_id' => $area['area_id'],
                        'loot.monster_id' => 0,
                    ]
                ]
            ];
        }
        $loots = $this->db()->select('loot', ['[>]item' => ['item_id' => 'id']], [
            'loot.area_id',
            'loot.monster_id',
            'loot.item_id',
            'loot.range',
            'loot.chance',
            'loot.min_amount',
            'loot.max_amount',
            'item.name',
            'item.ui_name',
            'item.type',
        ], $conditions);

        $commonLoots = [];
        // 掉落根据系统怪物编号分类
        $lootsMap = [];
        foreach ($loots as $v) {
            if ($v['area_id'] && !$v['monster_id']) {
                $commonLoots[] = $v;
            } else {
                if (!isset($lootsMap[$v['monster_id']])) {
                    $lootsMap[$v['monster_id']] = [];
                }
                $lootsMap[$v['monster_id']][] = $v;
            }
        }

        // 所有怪物的总金钱
        $totalMoney = 0;
        // 所有怪物的总经验
        $totalExp = 0;
        // 宠物技能熟练度
        $totalPetScore = 0;

        /** @var Attacker $defender */
        foreach ($combat->defenders as $defender) {
            // 跳过不是怪物的防御者
            if (!$defender instanceof MonsterAttacker) {
                continue;
            }

            // 获取地图怪物对象
            $midGuaiwu = $defender->getRawObject();
            if (!isset($allLoots[$midGuaiwu->id])) {
                $allLoots[$midGuaiwu->id] = [];
            }

            foreach ($combat->attackers as $attacker) {
                // 过滤所有非玩家攻击者
                if (!$attacker instanceof PlayerAttacker) {
                    continue;
                }
                $role = $attacker->getRawObject();
                $huode = ["{$role->name}获得："];
                // 更新怪物相关的任务信息
                $resque->push(CheckTaskMonsterCondition::class, ['uid' => $role->id, 'gid' => $midGuaiwu->gid]);
                // 当前怪物所有掉落
                $loots = $lootsMap[$midGuaiwu->gid] ?? [];
                // 合并区域公共掉落
                $loots = $this->mergeLoots($loots, $commonLoots);

                if (!empty($loots)) {
                    // 从掉落中抽取道具
                    $got = [];
                    foreach ($loots as $item) {
                        $random = rand(1, $item['range']);
                        if ($random > $item['chance']) {
                            continue;
                        }
                        if ($item['type'] == 2) {
                            $djsum = 1;
                        } else {
                            $djsum = rand($item['min_amount'], $item['max_amount']);
                        }
                        $djname = $item['ui_name'] ?? $item['name'];
                        $got[] = "<div>$djname x$djsum</div>";
                        // 将获取物品任务发送到队列
                        $resque->push(LootItem::class, [
                            'mid' => $role->nowmid,
                            'uid' => $role->id,
                            'item_id' => $item['item_id'],
                            'item_amount' => $djsum,
                            'monster_name' => $midGuaiwu->name,
                        ]);
                    }
                    if (!empty($got)) {
                        $huode = array_merge($huode, $got);
                    }
                }

                $yxb = $midGuaiwu->level * 5;
                // 未获得金币时不显示。
                if ($yxb > 0) {
                    $totalMoney += $yxb;
                    $huode[] = "<div>金币 x{$yxb}</div>";
                }
                $totalExp += $midGuaiwu->exp;
                $huode[] = '<div>经验 x' . $midGuaiwu->exp . '</div>';
                // 如果该玩家召唤了宝宝，有5%的机率获得召唤熟练度
                if ($role->pet && $role->pet->hp > 0 && $role->pet->playerSkillId && rand(1, 100) <= 20) {
                    $totalPetScore += 1;
                    $huode[] = "<div>召唤熟练度 x1</div>";
                }
                $allLoots[$midGuaiwu->id][] = $huode;
            }
        }

        // 统一发放经验金币和宠物熟练度
        foreach ($combat->attackers as $attacker) {
            if ($attacker instanceof PlayerAttacker) {
                $role = $attacker->getRawObject();
                $args = [
                    'uid' => $role->id,
                    'total_exp' => $totalExp,
                    'total_money' => $totalMoney,
                    'total_score' => $totalPetScore,
                ];
                if ($totalPetScore && $role->pet && $role->pet->hp > 0 && $role->pet->playerSkillId) {
                    $args['player_skill_id'] = $role->pet->playerSkillId;
                }
                $resque->push(LootExpAndMoney::class, $args);
            }
        }

        return $allLoots;
    }

    /**
     * 合并掉落
     * @param array $loots
     * @param array $common
     * @return array
     */
    protected function mergeLoots(array $loots, array $common)
    {
        $ret = [];
        foreach ($loots as $v) {
            $id = $v['item_id'];
            if (!isset($ret[$id])) {
                $ret[$id] = $v;
            }
        }
        foreach ($common as $v) {
            $id = $v['item_id'];
            if (!isset($ret[$id])) {
                $ret[$id] = $v;
            }
        }
        return array_values($ret);
    }

    protected function gainExp(int $attackerLv, int $defenderLv, int $defenderExp)
    {
        return $defenderExp;
        $lvc = $attackerLv - $defenderLv;
        if ($lvc <= 0) {
            $lvc = 0;
        }
        $exp = round($defenderExp / ($lvc + 1), 0);
        return $exp < 3 ? 3: $exp;
    }

    /**
     * 战斗逃跑
     */
    public function runaway()
    {
        $cid = $this->params['cid'] ?? 0;
        $player = \player\getPlayerById($this->game->db, $this->uid());

        $resultType = 0;
        if ($player->ispvp) {
            do {
                $allUids = [];
                $combatStatus = CombatStatus::get($this->db(), $player->ispvp);
                if (!$combatStatus->id) {
                    break;
                }
                $combat = $combatStatus->getCombat();
                foreach ($combat->attackers as $attacker) {
                    if (!$attacker instanceof PlayerAttacker) {
                        continue;
                    }
                    $allUids[] = $attacker->getRawObject()->id;
                    if ($player->id == $attacker->getRawObject()->id) {
                        $resultType = 3;
                        break 2;
                    }
                }
                foreach ($combat->defenders as $defender) {
                    if (!$defender instanceof PlayerAttacker) {
                        continue;
                    }
                    $allUids[] = $defender->getRawObject()->id;
                    if ($player->id == $defender->getRawObject()->id) {
                        $resultType = 4;
                        break 2;
                    }
                }
                $this->db()->update('combat', ['result_type' => $resultType, 'is_end' => 1], ['id' => $combatStatus->id]);
            } while (false);
            if (empty($allUids)) {
                $allUids[] = $this->uid();
            }
            $allUids = array_unique($allUids);
            $this->db()->update('game1', ['ispvp' => 0], ['id' => $allUids]);
            foreach ($allUids as $uid) {
                $this->game->event->remove($uid);
            }
            $this->doRawCmd("cmd=gomid");
        }

        //@FIXME pvp和pve操作分开

        $combatStatus = CombatStatus::get($this->db(), $cid);

        $combat = $combatStatus->getCombat();
        if ($player->partyId) {
            $playerParty = PlayerParty::get($this->db(), $player->partyId);
            // 队长逃跑
            if ($playerParty->uid == $player->id) {
                // 删除所有队员的战斗事件
                foreach ($combat->attackers as $attacker) {
                    if ($attacker instanceof PlayerAttacker) {
                        $this->game->event->remove($attacker->getRawObject()->id);
                    }
                }
                $this->removeDeadPetFromCombat($combat);
            } else {
                $this->game->event->remove();
                // 队员逃跑，直接将队员设置为自由
                $this->changePartyMemberStatus($player, 1);
                $this->removeDeadPetFromCombat($combat, $player->id);
            }
        } else {
            // 移除战斗事件
            $this->game->event->remove();
            $this->removeDeadPetFromCombat($combat, $player->id);
        }

        // PVE队友逃跑不删除战斗状态
        if (!($this->isFollower && !$player->ispvp)) {
            $combatStatus->delCombat();
        }
        $this->doRawCmd("cmd=gomid");
    }

    protected function removeDeadPetFromCombat(Combat $combat, int $uid = 0)
    {
        // 战死宠物编号
        $deadPetIds = [];
        foreach ($combat->attackers as $a) {
            if (!($a instanceof PetAttacker) || $a->hp > 0) {
                continue;
            }
            // 删除全部
            if ($uid == 0) {
                $deadPetIds[] = $a->getRawObject()->id;
            } else if ($a->getRawObject()->uid == $uid) {
                // 删除指定角色的宠物
                $deadPetIds[] = $a->getRawObject()->id;
            }
        }
        if (!empty($deadPetIds)) {
            $this->db()->delete('player_pet', ['id' => $deadPetIds]);
        }
    }

    /**
     * 显示 PVE 记录
     */
    public function showPveLog()
    {
        $lid = $this->params['lid'];
        $db = $this->game->db;
        $data = [];
        $log = $db->get('pve_logs', '*', ['id' => $lid]);
        $player =  \player\getPlayerById($db, $this->uid());
        if ($player->hp <= 0) {
            $cxmid = \player\getmid($player->nowmid, $db);
            $cxqy = \player\getqy($cxmid->mqy, $db);
            $data['gonowmid'] = $this->encode("cmd=gomid&newmid=$cxqy->mid");
        } else {
            $data['gonowmid'] = $this->encoder->encode("cmd=gomid&newmid=$player->nowmid");
        }

        if ($log['status'] >= 0) {
            $attackers = [];
            foreach (explode(',', $log['attackers']) as $attacker) {
                list ($id, $name) = explode('|', $attacker);
                $attackers[$id] = $name;
            }

            foreach (explode(',', $log['defenders']) as $defender) {
                list ($id, $name) = explode('|', $defender);
                $defenders[$id] = $name;
            }
            $data['attackers'] = $attackers;
            $data['defenders'] = $defenders;
            $data['notes'] = json_decode($log['notes'], true);
        }

        $data['log'] = $log;

        $this->display('pve_log', $data);
    }

    public function deletePveLog()
    {
        $id = $this->params['id'];
        // @FIXME 需要定期删除日志
        //$this->$this->db()->delete('pve_logs', ['id' => $id]);
        $this->doRawCmd('cmd=gomid');
    }

    public function doMemberPve()
    {
        $db = $this->game->db;
        $player = \player\getPlayerById($db, $this->uid());
        $name = Helper::getVipName($player);

        $id = $this->params['id'] ?? null;
        $action = $this->params['action'] ?? null;
        $aid = $this->params['aid'] ?? 0;

        $combatStatus = CombatStatus::get($this->db(), $id);
        $combat = $combatStatus->getCombat();

        /** @var Attacker $defender */
        foreach ($combat->attackers as $attacker) {
            if ($attacker->getRawObject()->id != $player->id) {
                continue;
            }
            if ($action === 'skill' && $attacker->cd == 0) {
                $skillArr = \player\getPlayerSkillById($aid, $player->id, $db);
                $skillEffects = $db->select('skill_effects', '*', ['skill_id' => $skillArr['skill_id']]);
                $skill = new Skill($skillArr, $skillEffects);
                $attacker->currentSkill = $skill;
                // 设置技能调息时间
                $attacker->cd = $skill->cd;
                $this->flash->success("设置下回合技能{$skill->name}成功");
                $this->db()->insert('im', [
                    'uid' => 0,
                    'tid' => $player->partyId,
                    'type' => 3,
                    'content' => "玩家{$name}设置下回合技能{$skill->name}成功！",
                ]);
            } else if ($action == 'item') {
                $playeryp = \player\getPlayerMedicine($this->db(), $aid);
                if ($playeryp->amount <= 0) {
                    // 使用药品失败，换回默认技能
                    $this->flash->set('message', sprintf('药品%s数量不足', $playeryp->name));
                    $attacker->currentSkill = null;
                    $attacker->currentItem = null;
                } else {
                    //@todo 逻辑有问题，如果攻方没有后续动作，药物还是被消耗了
                    \player\delYaopinById($this->uid(), $aid, 1, $db);
                    $itemEffects = $db->select('medicine_effects', '*', ['item_id' =>$playeryp->itemId]);
                    $item = new Item((array)$playeryp, $itemEffects);
                    $attacker->currentItem = $item;
                    $this->flash->success("设置下回合物品{$item->name}成功");
                    $this->db()->insert('im', [
                        'uid' => 0,
                        'tid' => $player->partyId,
                        'type' => 3,
                        'content' => "玩家{$name}设置下回合物品{$item->name}成功！",
                    ]);
                }
            } else {
                $attacker->currentSkill = null;
                $attacker->currentItem = null;
            }
            break;
        }

        // 更新战斗状态
        $this->db()->update('combat', [
            'data' => serialize($combat),
        ], ['id' => $combatStatus->id]);

        $this->doRawCmd("cmd=pve&id={$combatStatus->id}&nowmid={$player->nowmid}");
    }
}