<?php
namespace Xian\Job;

use Xian\Handlers\CommonTrait;
use Xian\Helper;

class LootExpAndMoney extends BaseJob
{
    use CommonTrait;

    public function perform($args)
    {
        $uid = $args['uid'] ?? 0;
        $totalExp = $args['total_exp'] ?? 0;
        $totalMoney = $args['total_money'] ?? 0;
        $totalPetScore = $args['total_score'] ?? 0;
        $skillId = $args['player_skill_id'] ?? 0;
        if ($totalExp) {
            \player\changeexp($this->db, $uid, $totalExp);
        }
        if ($totalMoney) {
            \player\changeyxb($this->db, 1, $totalMoney, $uid);
        }
        if ($totalPetScore && $skillId) {
            $skill = $this->db->get('player_skill', [
                'id',
                'level',
                'score',
                'max_score'
            ], ['id' =>  $skillId]);
            $r = $this->upSkillExp($skill, $totalPetScore);
        }
    }
}