<?php

namespace Xian\Handlers;

use Medoo\Medoo;
use player\Player;
use Xian\AbstractHandler;
use Xian\Combat\Attacker;
use Xian\Combat\Combat;
use Xian\Combat\Item;
use Xian\Combat\PetAttacker;
use Xian\Combat\PlayerAttacker;
use Xian\Combat\Skill;
use Xian\Helper;
use Xian\Object\CombatStatus;
use Xian\Object\Pet;

class PVP extends AbstractHandler
{
    use CombatTrait;

    public function showPvp()
    {
        $db = $this->game->db;

        $id = $this->params['id'] ?? 0;
        $player = \player\getPlayerById($db, $this->uid());
        $gonowmid = $this->encode("cmd=gomid&newmid=$player->nowmid");

        $cxmid = \player\getmid($player->nowmid, $db);
        $cxqy = \player\getqy($cxmid->mqy, $db);

        $combatStatus = CombatStatus::get($this->db(), $id);

        if (!$combatStatus->id) {
            $this->game->event->remove();
            $this->doRawCmd('cmd=gomid');
        }

        if ($combatStatus->resultType != 0) {
            $this->game->event->remove();
            $this->doRawCmd("cmd=pvp-log&id=$id");
        }
        $pvper = \player\getplayer1($combatStatus->defenderId, $db);
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
                    if ($jineng['manual_id'] !== $player->manualId) {
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

        $combat = $combatStatus->getCombat();
        $pgjcmd = $this->encode("cmd=do-pvp&id={$id}&uid={$pvper->id}");
        $data = [];
        $data['attackers'] = $combat->attackers;
        $data['defenders'] = $combat->defenders;
        $data['player'] = $player;
        $data['pvper'] = $pvper;
        $data['gonowmid'] = $gonowmid;
        $data['pgjcmd'] = $pgjcmd;
        $data['skills'] = $skills;
        $data['medicines'] = $yaopins;
        $data['logs'] = json_decode($combatStatus->logs, true);
        $data['combatStatus'] = $combatStatus;
        unset($this->data['pvp']);

        $this->display('pvp', $data);
    }

    public function showDefense()
    {
        $db = $this->game->db;

        $id = $this->params['id'] ?? 0;
        $player = \player\getPlayerById($db, $this->uid());
        $gonowmid = $this->encode("cmd=gomid&newmid=$player->nowmid");

        $cxmid = \player\getmid($player->nowmid, $db);
        $cxqy = \player\getqy($cxmid->mqy, $db);

        $combatStatus = CombatStatus::get($this->db(), $id);

        if (!$combatStatus->id) {
            $this->game->event->remove();
            $this->doRawCmd('cmd=gomid');
        }

        if ($combatStatus->resultType != 0) {
            $this->game->event->remove();
            $this->doRawCmd("cmd=pvp-log&id=$id&type=2");
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
                    if ($jineng['manual_id'] !== $player->manualId) {
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

        $combat = $combatStatus->getCombat();
        $data = [];
        $data['attackers'] = $combat->defenders;
        $data['defenders'] = $combat->attackers;
        $data['player'] = $player;
        $data['gonowmid'] = $gonowmid;
        $data['skills'] = $skills;
        $data['medicines'] = $yaopins;
        $data['logs'] = json_decode($combatStatus->logs, true);
        $data['combatStatus'] = $combatStatus;
        unset($this->data['pvp']);

        $this->display('pvp_defense', $data);
    }

    public function doPvp()
    {
        $db = $this->game->db;
        $player = \player\getPlayerById($db, $this->uid());

        $uid = $this->params['uid'] ?? 0;
        $id = $this->params['id'] ?? null;
        $action = $this->params['action'] ?? null;
        $aid = $this->params['aid'] ?? 0;
        $begin = $this->params['begin'] ?? null;

        $cxmid = \player\getmid($player->nowmid, $db);
        $cxqy = \player\getqy($cxmid->mqy, $db);
        $gorehpmid = $this->encode("cmd=gomid&newmid=$cxqy->mid");
        $gonowmid = $this->encode("cmd=gomid&newmid=$player->nowmid");

        if ($begin) {
            $pvper = \player\getPlayerById($db, $uid);

            if ($player->partyId || $pvper->partyId) {
                $this->flash->error('目前不支持组队PK');
                $this->doCmd($gonowmid);
            }
            if ($cxmid->ispvp == 0){
                \player\changeplayersx("ispvp",0, $this->uid(), $db);
                $this->flash->error('当前地图不允许PK');
                $this->doCmd($gonowmid);
            }
            if ($pvper->sfzx == 0){
                \player\changeplayersx("ispvp",0,$this->uid(),$db);
                $this->flash->error('该玩家没有在线');
                $this->doCmd($gonowmid);
            }
            if ($pvper->sfxl == 1){
                \player\changeplayersx("ispvp",0,$this->uid(),$db);
                $this->flash->error('该玩家修炼中，无法接受PK');
                $this->doCmd($gonowmid);
            }
            if ($pvper->nowmid != $player->nowmid){
                \player\changeplayersx("ispvp",0,$this->uid(),$db);
                $this->flash->error('该玩家没在该地图');
                $this->doCmd($gonowmid);
            }

            if ($player->hp<=0){
                \player\changeplayersx("ispvp",0,$this->uid(),$db);
                $this->flash->error('你已是重伤之身,无法进行战斗');
                $this->doCmd($gorehpmid);
            }
            if ($pvper->hp<=0){
                \player\changeplayersx("ispvp",0,$this->uid(),$db);
                $this->flash->error('该玩家已经死亡');
                $this->doCmd($gonowmid);
            }
            $combat = new Combat();

            // 宠物加血
            $petIds = [];
            if ($player->cw) {
                $petIds[] = $player->cw;
            }
            if ($pvper->cw) {
                $petIds[] = $pvper->cw;
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

            // 添加攻击方
            $attackers = [$player];
            if ($player->cw && isset($petsMap[$player->cw])) {
                $player->pet = $petsMap[$player->cw];
                $attackers[] = $petsMap[$player->cw];
            }

            foreach ($attackers as $attacker) {
                if ($attacker instanceof Player) {
                    if ($attacker->cw && isset($petsMap[$attacker->cw])) {
                        $attacker->pet = $petsMap[$attacker->cw];
                        $attackers[] = $petsMap[$attacker->cw];
                    }
                    $currentAttacker = PlayerAttacker::fromPlayer($attacker);
                    $this->addEquipKeywordEffects($currentAttacker);
                } else if ($attacker instanceof Pet) {
                    $currentAttacker = PetAttacker::fromPet($attacker);
                }
                $combat->addAttacker($currentAttacker);
            }

            // 添加防御方
            $defenders = [$pvper];
            if ($pvper->cw && isset($petsMap[$pvper->cw])) {
                $pvper->pet = $petsMap[$pvper->cw];
                $defenders[] = $petsMap[$pvper->cw];
            }
            foreach ($defenders as $defender) {
                if ($defender instanceof Player) {
                    if ($defender->cw && isset($petsMap[$defender->cw])) {
                        $defender->pet = $petsMap[$defender->cw];
                        $attackers[] = $petsMap[$defender->cw];
                    }
                    $currentAttacker = PlayerAttacker::fromPlayer($defender);
                    $this->addEquipKeywordEffects($currentAttacker);
                } else if ($defender instanceof Pet) {
                    $currentAttacker = PetAttacker::fromPet($defender);
                }
                $combat->addDefender($currentAttacker);
            }

            $data = serialize($combat);
            $this->db()->insert('combat', [
                'attacker_id' => $player->id,
                'defender_id' => $pvper->id,
                'data' => $data,
                'type' => 2,
                'result_type' => 0,
            ]);
            $combatStatusId = $this->db()->id();

            // 保存战斗编号到攻防双方字段
            $this->db()->update('game1', ['ispvp' => $combatStatusId], ['id' => [$player->id, $pvper->id]]);

            // 攻方加入主动战斗事件
            $whiteList = [
                'do-pvp',
                'runaway',
                'select-combat-item',
                'select-combat-skill',
            ];
            $this->game->event->set($player->id, sprintf('cmd=pvp&id=%d&nowmid=%d', $combatStatusId, $player->nowmid), $whiteList);

            //　防方加入被动战斗事件
            $whiteList = [
                'runaway',
                'do-defense',
                'select-combat-item',
                'select-combat-skill',
            ];
            $this->game->event->set($pvper->id, sprintf('cmd=pvp-defense&id=%d&nowmid=%d', $combatStatusId, $player->nowmid), $whiteList);

            $back = $this->encode(sprintf('cmd=pvp&id=%d&nowmid=%d', $combatStatusId, $player->nowmid));
            $this->doCmd($back);
        }

        $back = $this->encode(sprintf('cmd=pvp&id=%d&nowmid=%d', $id, $player->nowmid));
        $combatStatus = CombatStatus::get($this->db(), $id);

        $seconds = time() - strtotime($combatStatus->lastTurnTimestamp);
        $wait = 3;
        if ($seconds < 3) {
            $wait -= $seconds;
            $this->flash->error("PVP 战斗每回合需等待3秒，请等待{$wait}秒后继续操作");
            $this->doCmd($back);
        }

        $combat = $combatStatus->getCombat();

        // 默认普通攻击，分为空手和武器
        $skillId = $player->tool1 ? 5 : 6;
        $skillArr = $db->get('skills', '*', ['id' => $skillId]);
        $skillEffects = $db->select('skill_effects', '*', ['skill_id' => $skillArr['id']]);
        $defaultSkill = new Skill($skillArr, $skillEffects);

        $skillArr = $db->get('skills', '*', ['id' => 2]);
        $skillEffects = $db->select('skill_effects', '*', ['skill_id' => 2]);
        $bite = new Skill($skillArr, $skillEffects);


        $attackerGroups = [$combat->attackers, $combat->defenders];
        foreach ($attackerGroups as $attackers) {
            // 设置攻击方技能
            foreach ($attackers as $attacker) {
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
                    $attacker->setSkillStatus($aid, $combat->currentRound);
                    //@todo delete
                } else if ($action == 'item') {
                    $playeryp = \player\getPlayerMedicine($this->db(), $aid);
                    if ($playeryp->amount <= 0) {
                        $this->flash->set('message', sprintf('药品%s数量不足', $playeryp->name));
                    } else {
                        \player\delYaopinById($role->id, $aid, 1, $db);
                        $itemEffects = $db->select('medicine_effects', '*', ['item_id' => $playeryp->itemId]);
                        $item = new Item((array)$playeryp, $itemEffects);
                        $attacker->currentItem = $item;
                        $attacker->setItemStatus($aid, $combat->currentRound);
                    }
                }
                if (is_null($attacker->currentSkill) && is_null($attacker->currentItem)) {
                    $attacker->currentSkill = $defaultSkill;
                }
            }
        }

        $combat->fight(false);
        $logs = $combat->logs();

        $end = $combat->end;
        // 保存角色状态
        /** @var Attacker $attacker */
        foreach (array_merge($combat->attackers, $combat->defenders) as $attacker) {
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

        if (!$combat->end) {
            $combat->clearLogs();
            $this->db()->update('combat', [
                'data' => serialize($combat),
                'logs' => json_encode($logs, JSON_UNESCAPED_UNICODE),
                'last_turn_timestamp' => date('Y-m-d H:i:s'),
            ], ['id' => $combatStatus->id]);
            $this->doCmd($back);
        }


        if ($end) {
            // 保存战斗结果
            $resultType = $combat->won() ? 1 : 2;
            $this->db()->update('combat', ['result_type' => $resultType, 'is_end' => 1], ['id' => $combatStatus->id]);
            $this->db()->update('game1', ['ispvp' => 0], [
                'id' => [$combatStatus->attackerId, $combatStatus->defenderId]
            ]);
            $this->game->event->remove($combatStatus->attackerId);
            $this->game->event->remove($combatStatus->defenderId);
        } else {
            $this->doCmd($back);
        }

        // 显示战斗结果
        $this->doRawCmd("cmd=pvp-log&id=$id");
    }

    public function doDefense()
    {
        $db = $this->game->db;
        $player = \player\getPlayerById($db, $this->uid());

        $id = $this->params['id'] ?? null;
        $action = $this->params['action'] ?? null;
        $aid = $this->params['aid'] ?? 0;

        $combatStatus = CombatStatus::get($this->db(), $id);

        if (!$combatStatus->id) {
            $this->game->event->remove();
            $this->doRawCmd('cmd=gomid');
        }
        $combat = $combatStatus->getCombat();

        // 默认普通攻击，分为空手和武器
        $skillId = $player->tool1 ? 5 : 6;
        $skillArr = $db->get('skills', '*', ['id' => $skillId]);
        $skillEffects = $db->select('skill_effects', '*', ['skill_id' => $skillArr['id']]);
        $defaultSkill = new Skill($skillArr, $skillEffects);

        /** @var Attacker $defender */
        foreach ($combat->defenders as $defender) {
            if ($defender->getRawObject()->id != $player->id) {
                continue;
            }
            if ($action === 'skill' && $defender->cd == 0) {
                $skillArr = \player\getPlayerSkillById($aid, $player->id, $db);
                $skillEffects = $db->select('skill_effects', '*', ['skill_id' => $skillArr['skill_id']]);
                $skill = new Skill($skillArr, $skillEffects);
                $defender->currentSkill = $skill;
                // 设置技能调息时间
                $defender->cd = $skill->cd;
                $this->flash->success("设置下回合技能{$skill->name}成功");
            } else if ($action == 'item') {
                $playeryp = \player\getPlayerMedicine($this->db(), $aid);
                if ($playeryp->amount <= 0) {
                    // 使用药品失败，换回默认技能
                    $this->flash->set('message', sprintf('药品%s数量不足', $playeryp->name));
                    $defender->currentSkill = $defaultSkill;
                } else {
                    //@todo 逻辑有问题，如果攻方没有后续动作，药物还是被消耗了
                    \player\delYaopinById($this->uid(), $aid, 1, $db);
                    $itemEffects = $db->select('medicine_effects', '*', ['item_id' =>$playeryp->itemId]);
                    $item = new Item((array)$playeryp, $itemEffects);
                    $defender->currentItem = $item;
                    $this->flash->success("设置下回合物品{$item->name}成功");
                }
            } else {
                $defender->currentSkill = $defaultSkill;
            }
            // 清除物品使用标识
            if ($action != 'item') {
                $defender->currentItem = null;
            }
        }

        // 更新战斗状态
        $this->db()->update('combat', [
            'data' => serialize($combat),
        ], ['id' => $combatStatus->id]);

        $this->doRawCmd("cmd=pvp-defense&id={$combatStatus->id}");
    }

    public function showPvpLog()
    {
        $type = $this->params['type'] ?? 1;
        $id = $this->params['id'] ?? 0;
        $combatStatus = CombatStatus::get($this->db(), $id);
        $player = \player\getPlayerById($this->db(), $this->uid());
        if ($type == 1) {
            $attacker = $player;
            $defender = \player\getplayer1($combatStatus->defenderId, $this->db());
        } else {
            $attacker = \player\getplayer1($combatStatus->attackerId, $this->db());
            $defender = $player;
        }
        $data = [];
        $data['attacker'] = $attacker;
        $data['defender'] = $defender;
        $data['combatStatus'] = $combatStatus;
        $data['gonowmid'] = $this->encode("cmd=gomid&newmid=$player->nowmid");
        $this->display('pvp_log', $data);
    }
}