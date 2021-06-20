<?php

namespace Xian\Handlers;

use player\Player;
use Xian\AbstractHandler;
use Xian\Combat\Skill;
use Xian\Helper;
use function player\getPlayerBoundProperty;
use function player\getPlayerById;
use function player\getPlayerItemById;
use function player\getPlayerSkillById;

class Setting extends AbstractHandler
{
    use CommonTrait;

    public function showShortcuts()
    {
        $db = $this->game->db;
        $player = \player\getPlayerById($db, $this->uid(), true);
        $gonowmid = $this->encode("cmd=gomid");

        $items = [];
        $itemIds = array_filter([$player->yp1, $player->yp2, $player->yp3], function ($v) {
            return $v > 0;
        });
        if (!empty($itemIds)) {
            // @todo 药品部分需要重构
            $arr = \player\getPlayerYaopinByIds($this->db(), $itemIds, $player->id, true);
            $map = [];
            foreach ($arr as $v) {
                $map[$v['id']] = $v;
            }
            foreach ([$player->yp1, $player->yp2, $player->yp3] as $k => $v) {
                if ($v == 0) {
                    $items[$k + 1] = 0;
                } else {
                    $items[$k + 1] = $map[$v] ?? 0;
                }
            }
        }

        $skills = [];
        $skillIds= array_filter([$player->jn1, $player->jn2, $player->jn3], function ($v) {
            return $v > 0;
        });
        if (!empty($skillIds)) {
            $skills = $db->select('player_skill', [
                '[>]skills' => ['skill_id' => 'id']
            ], [
                'player_skill.id',
                'player_skill.skill_id',
                'player_skill.level',
                'player_skill.score',
                'skills.name',
                'skills.manual_id',
            ], [
                'player_skill.uid' => $player->id
            ]);
            $skillsMap = [];
            foreach ($skills as $v) {
                $skillsMap[$v['id']] = $v;
            }
            foreach ([$player->jn1, $player->jn2, $player->jn3] as $k => $v) {
                if ($v == 0 || !isset($skillsMap[$v])) {
                    $skills[$k + 1] = 0;
                } else {
                    $skills[$k + 1] = $skillsMap[$v];
                }
            }
        }

        $data = [];
        $data['skills'] = $skills;
        $data['items'] = $items;
        $data['gonowmid'] = $gonowmid;
        $this->display('shortcuts', $data);
    }

    public function setShortcut()
    {
        $db = $this->game->db;
        $type = $this->params['type'] ?? null;
        $n = $this->params['n'] ?? 0;
        $value = $this->params['value'] ?? 0;

        if (!$type || !$n) {
            $this->flash->error('无效操作');
            $this->doRawCmd("cmd=show-shortcuts");
        }

        $shortcut = $type === 'skill' ? "jn$n" : "yp$n";
        $db->update('game1', [$shortcut => (int)$value], ['id' => $this->uid()]);
        $this->doRawCmd("cmd=show-shortcuts");
    }

    public function selectShortcutSkill()
    {
        $db = $this->game->db;
        $player = \player\getPlayerById($db, $this->uid());
        $n = $this->params['n'];

        $skills = $db->select('player_skill', [
            '[>]skills' => ['skill_id' => 'id']
        ], [
            'player_skill.id',
            'player_skill.skill_id',
            'player_skill.level',
            'player_skill.score',
            'skills.name',
            'skills.manual_id',
        ], [
            'player_skill.uid' => $player->id
        ]);

        $data = [];
        $data['skills'] = $skills;
        $data['n'] = $n;
        $data['gonowmid'] = $this->encode("cmd=gomid&newmid=$player->nowmid");

        $this->display('shortcuts/select_skill', $data);
    }

    public function selectShortcutItem()
    {
        $db = $this->game->db;
        $player = \player\getPlayerById($db, $this->uid());
        $n = $this->params['n'];
        $items = \player\getplayeryaopinall($this->db(), $player->id);
        $data = [];
        $data['items'] = $items;
        $data['n'] = $n;
        $data['gonowmid'] = $this->encode("cmd=gomid&newmid=$player->nowmid");
        $this->display('shortcuts/select_item', $data);
    }

    public function showCombatCondition()
    {
        $player = getPlayerById($this->db(), $this->uid(), true);
        $combatConditions = $this->db()->select('player_combat_condition', '*', [
            'uid' => $this->uid(),
            'ORDER' => ['sequence' => 'ASC']
        ]);
        foreach ($combatConditions as &$v) {
            $desc = $this->describeCombatCondition($player,  $v);
            $v['desc'] = empty($desc) ? '无效策略' : $desc;
            $v['is_valid'] = empty($desc) ? 0 : 1;
        }
        $data = [
            'conditions' => $combatConditions,
            'is_bound' => getPlayerBoundProperty($this->db(), $this->uid()),
        ];
        $this->display('shortcuts/show_combat_condition', $data);
    }

    public function showAddCombatCondition()
    {
        $target = $this->params['target'] ?? 0;
        $targets = ['系统', '自身', '队友', '敌人'];
        if ($target < 1) {
            $properties = [
                'round' => '指定回合',
                'loop' => '间隔回合'
            ];
        } else {
            $properties = $this->attributes;
        }
        $types = [1 => '使用技能', 2 => '使用物品'];
        $operations = ['>', '=', '<', '<=', '>='];
        $skills = $this->db()->select('player_skill', ['[>]skills' => ['skill_id' => 'id']], [
            'player_skill.id',
            'skills.name',
        ], ['player_skill.uid' => $this->uid()]);
        $items = $this->db()->select('player_item', [
            '[>]item' => ['item_id' => 'id']
        ], [
            'player_item.id',
            'item.name',
        ], [
            'player_item.uid' => $this->uid(),
            'player_item.amount[>]' => 0,
            'item.type' => 3,
            'player_item.storage' => 1,
        ]);
        $data = [
            'target' => $target,
            'targets' => $targets,
            'properties' =>  $properties,
            'operations' => $operations,
            'types' => $types,
            'skills' => $skills,
            'items' => $items,
        ];
        $this->display('shortcuts/add_combat_condition', $data);
    }

    public function addCombatCondition()
    {
        $maxCount = empty($this->session['vip']) ? 2 : 5;
        $count = $this->db()->count('player_combat_condition', ['uid' => $this->uid()]);
        if ($count >= $maxCount) {
            $this->flash->error('策略数量已达到上限');
            $this->doRawCmd('cmd=show-combat-condition');
        }

        $prev = $this->db()->get('player_combat_condition', '*', [
            'uid' => $this->uid(),
            'ORDER' => ['sequence' => 'DESC'],
            'LIMIT' => 1,
        ]);

        $target = Helper::filterVar($this->postParam('target'), 'INT') ?: 0;
        $targetNum = Helper::filterVar($this->postParam('target_num'), 'INT') ?: 0;
        $targetNumOp = $this->postParam('target_num_op') ?? '';
        $targetProperty = Helper::filterVar($this->postParam('target_property')) ?: '';
        $operation = $this->postParam('operation') ?? '';
        $num = Helper::filterVar($this->postParam('num'), 'INT') ?: 0;
        $selectionType = Helper::filterVar($this->postParam('selection_type'), 'INT') ?: 0;
        $selectionId = Helper::filterVar($this->postParam('selection_id'), 'INT') ?: 0;

        $this->db()->insert('player_combat_condition', [
            'uid' => $this->uid(),
            'target' => $target,
            'target_num' => $targetNum,
            'target_num_op' => $targetNumOp,
            'target_property' => $targetProperty,
            'operation' => $operation,
            'num' => $num,
            'selection_type' => $selectionType,
            'selection_id' => $selectionId,
            'sequence' => empty($prev) ? 1 : $prev['sequence'] + 1,
        ]);
        $this->flash->success('策略添加成功');
        $this->doRawCmd('cmd=show-combat-condition');
    }

    public function deleteCombatCondition()
    {
        $id = $this->params['id'] ?? 0;
        if (empty($id)) {
            $this->flash->error('无效策略编号');
            $this->doRawCmd('cmd=show-combat-condition');
        }
        $this->db()->delete('player_combat_condition', ['id' => $id, 'uid' => $this->uid()]);
        $this->flash->success('删除策略成功');
        $this->doRawCmd('cmd=show-combat-condition');
    }

    public function upCombatCondition()
    {
        $id = $this->params['id'] ?? 0;
        if (empty($id)) {
            $this->flash->error('无效策略编号');
            $this->doRawCmd('cmd=show-combat-condition');
        }
        $current = $this->db()->get('player_combat_condition', ['id', 'sequence'], ['id' => $id]);
        $prev = $this->db()->get('player_combat_condition', ['id', 'sequence'], [
            'uid' => $this->uid(),
            'sequence[<]' => $current['sequence'],
            'ORDER' => ['sequence' => 'DESC'],
            'LIMIT' => 1,
        ]);
        if (empty($prev)) {
            $this->flash->error('策略已位于第一，无法移动');
            $this->doRawCmd('cmd=show-combat-condition');
        }
        $this->db()->update('player_combat_condition', ['sequence' => $prev['sequence']], ['id' => $id]);
        $this->db()->update('player_combat_condition', ['sequence' => $current['sequence']], ['id' => $prev['id']]);

        $this->flash->success('上移成功');
        $this->doRawCmd('cmd=show-combat-condition');
    }

    protected function describeCombatCondition(Player $player, array $condition): string
    {
        $typeText = $condition['selection_type'] == 1 ? '技能' : '道具';
        if ($condition['selection_type'] == 1) {
            $skill = getPlayerSkillById($condition['selection_id'], $this->uid(), $this->db());
            $item = new Skill($skill, []);
        } else {
            $item = getPlayerItemById($this->db(), $condition['selection_id'], $this->uid());
        }

        // 自身属性条件
        if ($condition['target'] == 1 && isset($player->{$condition['target_property']})) {
            $property = $this->attributes[$condition['target_property']];
            return sprintf(
                '当自身%s%s%d时使用%s%s',
                $property,
                $condition['operation'],
                $condition['num'],
                $typeText,
                $item->name
            );
        }

        // 系统条件判断
        if ($condition['target'] == 0) {
            // 指定战斗回合操作
            if ($condition['target_property'] == 'round') {
                // 任意回合数执行一次
                if ($condition['num'] == 0) {
                    return sprintf('任意回合使用一次%s%s', $typeText, $item->name);
                } else {
                    return sprintf('在第%d回合使用一次%s%s', $condition['num'], $typeText, $item->name);
                }
            }

            // 循环执行操作
            if ($condition['target_property'] == 'loop') {
                return sprintf('每间隔%d回合使用一次%s%s', $condition['num'], $typeText, $item->name);
            }

            // 无条件选择
            if (!empty($condition['selection_id'])) {
                return sprintf('无条件使用%s%s', $typeText, $item->name);
            }
        }

        //比较对象为友方或者敌方时
        if ($condition['target'] == 2 || $condition['target'] == 3) {
            $target = $condition['target'] == 2 ? '友方' : '敌方';
            $property = $this->attributes[$condition['target_property']];
            return sprintf(
                '在%s中%s%d名成员的%s%s%d时，使用%s%s',
                $target,
                $condition['target_num_op'],
                $condition['target_num'],
                $property,
                $condition['operation'],
                $condition['num'],
                $typeText,
                $item->name
            );
        }

        return '';
    }
}