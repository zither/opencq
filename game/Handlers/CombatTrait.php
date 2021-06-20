<?php


namespace Xian\Handlers;

use Xian\Combat\BaseEffect;
use Xian\Combat\Combat;
use Xian\Combat\PetAttacker;
use Xian\Combat\PlayerAttacker;
use Xian\Combat\Skill;
use Xian\Combat\Attacker;

trait CombatTrait
{
    protected function addEquipKeywordEffects(PlayerAttacker $attacker)
    {
        $player = $attacker->getRawObject();
        $equipIds = [];
        for ($i = 1; $i <= 7; $i++) {
            $tool = "tool$i";
            if ($player->$tool) {
                $equipIds[] = $player->$tool;
            }
        }

        if (empty($equipIds)) {
            return ;
        }

        $keywords = $this->db()->select('player_equip_keyword', '*', [
            'item_id' => $equipIds,
            'target' => 2,
            'is_column' => 0,
        ]);
        foreach ($keywords as $v) {
            // 将目标修改为战斗目标中的己方单位
            $v['target'] = 2;
            // 有效回合设置为最大
            $v['turns'] = 99999;
            // 当前回合生效
            $v['effect_turn'] = 1;
            $effect = new BaseEffect($v, BaseEffect::ORIGIN_TYPE_ITEM);
            $effect->fromAttacker = $attacker;
            $attacker->effects->attach($effect);
        }
    }

    /**
     * 战斗策略选择
     * @param Attacker $attacker
     * @param Combat $combat
     * @return int[]
     */
    protected function autoCombatSelection(Attacker $attacker, Combat $combat): array
    {
        $role = $attacker->getRawObject();

        $skillStatus = $attacker->skillStatus;
        $itemStatus = $attacker->itemStatus;

        $selection = ['type' => 0, 'selected_id' => 0];
        $combatConditions = $this->db()->select('player_combat_condition', '*', [
            'uid' => $role->id,
            'ORDER' => ['sequence' => 'ASC']
        ]);
        foreach ($combatConditions as $v) {
            $aid = $v['selection_id'];

            // 自身属性条件
            if ($v['target'] == 1 && isset($role->{$v['target_property']})) {
                $property = $role->{$v['target_property']};
                $res = $this->compare($property, $v['operation'], $v['num']);
                if ($res) {
                    $selection['type'] = $v['selection_type'];
                    $selection['selected_id'] = $v['selection_id'];
                    return $selection;
                }
            }

            // 系统条件
            if ($v['target'] == 0) {
                // 指定战斗回合操作
                if ($v['target_property'] == 'round') {
                    // 任意回合数执行一次
                    if ($v['num'] == 0) {
                        if ($v['selection_type'] == 1 && isset($skillStatus[$v['selection_id']])) {
                            continue;
                        }
                        if ($v['selection_type'] == 2 && isset($itemStatus[$v['selection_id']])) {
                            continue;
                        }
                    } else {
                        // 指定回合执行一次
                        $property = $combat->currentRound;
                        $res = $this->compare($property, $v['operation'], $v['num']);
                        if (!$res) {
                            continue;
                        }
                    }
                    $selection['type'] = $v['selection_type'];
                    $selection['selected_id'] = $v['selection_id'];
                    return $selection;
                }

                // 循环执行操作
                if ($v['target_property'] == 'loop') {
                    if ($v['selection_type'] == 1 && isset($skillStatus[$aid])) {
                        $gap = $combat->currentRound - $skillStatus[$aid];
                        // 相隔回合不满足条件
                        if ($gap < $v['num']) {
                            continue;
                        }
                    }
                    if ($v['selection_type'] == 2 && isset($itemStatus[$aid])) {
                        $gap = $combat->currentRound - $itemStatus[$aid];
                        // 相隔回合不满足条件
                        if ($gap < $v['num']) {
                            continue;
                        }
                    }
                    $selection['type'] = $v['selection_type'];
                    $selection['selected_id'] = $v['selection_id'];
                    return $selection;
                }

                // 无条件选择
                if (empty($v['target_property']) && !empty($v['selection_id'])) {
                    $selection['type'] = $v['selection_type'];
                    $selection['selected_id'] = $v['selection_id'];
                    return $selection;
                }
            }

            //比较对象为友方或者敌方时
            if ($v['target'] == 2 || $v['target'] == 3) {
                $targets = $v['target'] == 2 ? $combat->attackers : $combat->defenders;
                $matched = 0;
                foreach ($targets as $t) {
                    $object = $t->getRawObject();
                    if (
                        !isset($object->{$v['target_property']})
                        ||  !$this->compare($object->{$v['target_property']}, $v['operation'], $v['num']))
                    {
                        continue;
                    }
                    $matched++;
                }

                if ($this->compare($matched, $v['target_num_op'], $v['target_num'])) {
                    $selection['type'] = $v['selection_type'];
                    $selection['selected_id'] = $v['selection_id'];
                    return $selection;
                }
            }
        }

        return $selection;
    }

    protected function compare($property, $operation, $num)
    {
        switch ($operation) {
            case '=':
                return $property == $num;
            case '<':
                return $property < $num;
            case '>':
                return $property > $num;
            case '>=':
                return $property >= $num;
            case '<=':
                return $property <= $num;
        }
        return false;
    }

    /**
     * @param PetAttacker $attacker
     * @return Skill|null
     */
    protected function selectPetSkill(PetAttacker $attacker)
    {
        $skills = $attacker->getRawObject()->skills;
        if (empty($skills)) {
            return null;
        }
        $ids = explode(',', $skills);
        shuffle($ids);
        $id = $ids[0];
        // 挥砍
        $skillArr = $this->db()->get('skills', '*', ['id' => $id]);
        $skillEffects = $this->db()->select('skill_effects', '*', ['skill_id' => $skillArr['id']]);
        return new Skill($skillArr, $skillEffects);
    }
}