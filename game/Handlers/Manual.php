<?php
namespace Xian\Handlers;

use Xian\Object\Pet;
use function player\deledjsum;
use Xian\AbstractHandler;
use Xian\Event;
use Xian\Helper;
use function player\getgameconfig;
use function player\getplayer;
use function player\getPlayerById;
use function player\getPlayerItem;

class Manual extends AbstractHandler
{
    use CommonTrait;

    public function learnManual()
    {
        $id = $this->params['id'] ?? 0;
        $itemId = $this->params['item_id'];
        $player = \player\getPlayerById($this->game->db, $this->uid());
        if ($player->manualId != 3) {
            $this->flash->error('你已选择职业，无法更改!');
            $this->doCmd($this->encode($this->lastAction()));
        }
        $manual = $this->db()->get('manual', '*', ['id' => $id]);
        if (empty($manual)) {
            $this->flash->error('无效职业');
            $this->doCmd($this->encode($this->lastAction()));
        }

        // 删除道具
        $success = deledjsum($this->db(), $itemId, 1, $player->id);
        if (!$success) {
            $this->flash->error('你没有获得物品');
            $this->doCmd($this->encode($this->lastAction()));
        }

        $minLevel = $this->db()->get('manual_level', '*', [
            'manual_id' => $id,
            'is_min_exp' => 1,
            'ORDER' => ['level' => 'ASC'],
            'LIMIT' => 1,
        ]);
        $playerManual = $this->db()->get('player_manual', ['id'], [
            'manual_id' => $id,
            'uid' => $player->id,
        ]);
        if (empty($playerManual)) {
            // 插入玩家功法
            $this->db()->insert('player_manual', [
                'uid' => $player->id,
                'manual_id' => $id,
                'manual_level_id' => $minLevel['id'],
                'level' => 1
            ]);
            $manualId = $this->db()->id();

            // 获取新境界的奖励
            Helper::getManualLevelBonuses($this->db(), $player->id, $minLevel['id']);
        } else {
            $manualId = $playerManual['id'];
        }


        $mid = getgameconfig($this->db(), ['firstmid'], true);
        // 更新用户当前功法
        $this->game->db->update('game1', [
            'player_manual_id' => $manualId,
            'nowmid' => $mid['v'],
        ], ['id' => $player->id]);

        $this->flash->success('职业选择成功');

        $this->doRawCmd('cmd=gomid');
        //$this->doCmd($this->encode($this->lastAction()));
    }

    public function learnSkill()
    {
        $id = $this->params['id'] ?? 0;
        $itemId = $this->params['item_id'];
        $player = \player\getPlayerById($this->game->db, $this->uid());

        $skill = $this->db()->get('skills', '*', ['id' => $id]);
        if (empty($skill)) {
            $this->flash->error('无效技能');
            $this->doCmd($this->encode($this->lastAction()));
        }

        if ($skill['level'] > $player->level) {
            $this->flash->error("等级不足{$skill['level']}，无法学习技能");
            $this->doCmd($this->encode($this->lastAction()));
        }

        $exists = $this->db()->count('player_skill', ['uid' => $this->uid(), 'skill_id' => $id]);
        if ($exists) {
            $this->flash->error('你已经习得该技能，无法重复学习');
            $this->doCmd($this->encode($this->lastAction()));
        }

        if ($skill['manual_id'] != 0 && $skill['manual_id'] != $player->manualId) {
            $this->flash->error('无法学习其他职业技能');
            $this->doCmd($this->encode($this->lastAction()));
        }

        $playerItem = getPlayerItem($this->db(), $itemId, $this->uid());
        if ($playerItem->amount < 1 || $playerItem->extra != $id) {
            $this->flash->error('使用失败');
            $this->doCmd($this->encode($this->lastAction()));
        }

        // 删除道具
        $success = deledjsum($this->db(), $itemId, 1, $player->id);
        if (!$success) {
            $this->flash->error('你没有获得物品');
            $this->doCmd($this->encode($this->lastAction()));
        }

        // 更新用户当前功法
        $this->db()->insert('player_skill', [
            'uid' => $this->uid(),
            'skill_id' => $id,
            'manual_id' => $skill['manual_id']
        ]);

        $this->flash->success("你已成功学习技能<span class='color-blue'>{$skill['name']}</span>");
        $this->doRawCmd($this->lastAction());
    }

    public function useSkillOutside()
    {
        $id = $this->params['id'] ?? 0;
        if (!$id) {
            $this->flash->error('无效技能');
            $this->doRawCmd($this->lastAction());
        }
        $skill = $this->db()->get('player_skill', ['[>]skills' => ['skill_id' => 'id']], [
            'player_skill.id',
            'player_skill.skill_id',
            'player_skill.level',
            'player_skill.score',
            'player_skill.max_score',
            'skills.name',
            'skills.level(require_level)',
            'skills.type',
            'skills.in_combat',
            'skills.outside_combat',
            'skills.event',
        ], [
            'player_skill.id' => $id,
            'uid' => $this->uid()
        ]);
        if (!$skill['outside_combat']) {
            $this->flash->error('该技能无法在战斗外使用');
            $this->doRawCmd($this->lastAction());
        }
        if (!empty($skill['event'])) {
            $this->doRawCmd($skill['event']);
        }
        $this->flash->success(sprintf('使用技能%s成功', $skill['name']));
        $this->doRawCmd($this->lastAction());
    }

    public function summonPet()
    {
        $player = getPlayerById($this->db(), $this->uid(), true);
        $type = $this->params['type'] ?? 1;
        $skillId = $this->params['skill_id'] ?? 0;

        if (!$skillId) {
            $this->flash->error('缺少技能参数');
            $this->doRawCmd('cmd=player-skills');
        }
        $skill = $this->db()->get('player_skill', [
            'id',
            'skill_id',
            'level',
            'score',
            'max_score'
        ], [
            'skill_id' => $skillId,
            'uid' => $this->uid()
        ]);
        switch ($type) {
            case 1:
                $name = sprintf('骷髅(%s)', $player->name);
                $rate = 0.5 + Helper::SKILL_LEVEL_RATE * ($skill['level'] - 1);
                break;
            case 2:
                $name = sprintf('神兽(%s)', $player->name);
                $rate = 0.6 + Helper::SKILL_LEVEL_RATE * ($skill['level'] - 1) * 2;
                break;
        }

        $exists = $this->db()->count('player_pet', ['uid' => $this->uid(), 'name' => $name]);
        if ($exists) {
            $this->flash->error(sprintf('当前已召唤%s，召唤失败', $name));
            $this->doRawCmd('cmd=player-skill-info&id=%d', $skill['id']);
        }

        // 降低宝宝等级，防止秒同等级怪
        $data = $this->db()->get('system_data', '*', ['level' => $player->level  - 5]);
        $pet = [
            'name' => $name,
            'level' => $player->level,
            'exp' => 0,
            'max_exp' => $data['player_exp'],
            'uid' => $this->uid(),
            'hp' => $data['monster_hp'],
            'maxhp' => $data['monster_hp'],
            'wugong' => floor($data['monster_gongji'] * $rate),
            'fagong' => floor($data['monster_gongji'] * $rate),
            'wufang' => floor($data['monster_fangyu'] * $rate),
            'fafang' => floor($data['monster_fangyu'] * $rate),
            'baqi' => floor($data['monster_baqi'] * $rate),
            'mingzhong' => floor($data['monster_mingzhong'] * $rate),
            'shanbi' => floor($data['monster_shanbi'] * $rate),
            'baoji' => floor($data['monster_baoji'] * $rate),
            'shenming' => floor($data['monster_shenming'] * $rate),
            'quality' => $skill['level'],
            'is_born' => 1,
            'is_out' => 1,
            'player_skill_id' => $skill['id'],
            'skills' => $type == 2 ? '25' : '',
        ];
        $petId = Pet::add($this->db(), $pet);
        if (!empty($petId)) {
            $this->flash->success("{$name}召唤成功，请前往<span class='color-blue'>宝宝</span>界面查看");
        } else {
            $this->flash->error('召唤失败');
        }
        $this->upSkillExp($skill);
        $this->doRawCmd('cmd=player-skill-info&id=%d', $skill['id']);
    }
}