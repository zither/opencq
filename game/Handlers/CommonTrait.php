<?php

namespace Xian\Handlers;

use player\Player;
use player\PlayerEquip;
use Xian\Helper;
use Xian\Object\PlayerParty;
use Xian\Object\PlayerPartyMember;

trait CommonTrait
{
    /**
     * @var string[] 装备类型
     */
    protected $equipTypes = [
        1 => '武器',
        2 => '衣服',
        3 => '头盔',
        4 => '项链',
        5 => '手镯',
        6 => '戒指',
        7 => '腰带',
        8 => '鞋子',
        9 => '宝石',
        10 => '勋章',
    ];

    /**
     * @var string[] 装备栏名称
     */
    protected $toolNames = [
        '占位装备栏' => 0,
        '武器' => 1,
        '衣服' => 2,
        '头盔' => 3,
        '项链' => 4,
        '手镯(左)' => 5,
        '手镯(右)' => 5,
        '戒指(左)' => 6,
        '戒指(右)' => 6,
        '腰带' => 7,
        '鞋子' => 8,
        '宝石' => 9,
        '勋章' => 10,
    ];

    protected $attributes = [
        'hp' => '生命值',
        'mp' => '魔法值',
        'maxhp' => '生命值上限',
        'maxmp' => '魔法值上限',
        'baqi' => '神力',
        'wugong' => '物攻',
        'fagong' => '法攻',
        'wufang' => '物防',
        'fafang' => '法防',
        'mingzhong' => '命中',
        'shanbi' => '闪避',
        'baoji' => '暴击',
        'shenming' => '抗暴'
    ];

    protected $playerTools = [
        'tool1',
        'tool2',
        'tool3',
        'tool4',
        'tool5',
        'tool6',
        'tool7',
        'tool8',
        'tool9',
        'tool10',
        'tool11',
        'tool12'
    ];

    protected $vipColors = [
        '',
        'color-green',
        'color-red',
        'color-golden'
    ];

    protected $skillLevelMap = [
        '1' => '初级',
        '2' => '中级',
        '3' => '高级',
        '4' => '专家',
    ];

    protected function upSkillExp(array $skill, int $score = 1)
    {
        // 经等级已经到达专家级
        if ($skill['level'] == 4) {
            return false;
        }
        if ($skill['score'] + $score < $skill['max_score']) {
            $this->db->update('player_skill', ['score[+]' => $score], ['id' => $skill['id']]);
            return true;
        }
        $maxScore = Helper::SKILL_INIT_SCORE * ($skill['level'] + 1);
        $this->db->update('player_skill', [
            'score' => ($skill['score'] + $score) -  $skill['max_score'],
            'max_score' => $maxScore,
            'level[+]' => 1
        ], ['id' => $skill['id']]);
        return true;
    }

    protected function countEquipRandomProperties(PlayerEquip $playerEquip): int
    {
        $count = 0;
        $attributes = array_keys($this->attributes);
        foreach ($attributes as $k) {
            $k = ucfirst($k);
            if (!empty($playerEquip->{"quality$k"})) {
                $count++;
            }
        }
        return $count;
    }

    protected function getQualityColor(int $quality): string
    {
        switch ($quality) {
            case 1:
                return 'color-green';
            case 2:
                return 'color-purple';
            case 3:
                return 'color-red';
            case 4:
                return 'color-golden';
        }
        return '';
    }

    protected function getValidPartyMembers(int $partyId)
    {
        $arr = $this->db->select('player_party_member', '*', [
            'party_id' => $partyId,
            'status' => 2
        ]);
        $members = [];
        foreach ($arr as $v) {
            $members[] = PlayerPartyMember::fromArray($v);
        }
        return $members;
    }

    protected function isPartyMember(Player $player): bool
    {
        if (!$player->partyId) {
            return false;
        }
        $playerParty = PlayerParty::get($this->db, $player->partyId);
        if ($playerParty->uid == $player->id) {
            return false;
        }
        $member = $this->db->get('player_party_member', '*', [
            'uid' => $player->id,
            'party_id' => $playerParty->id,
        ]);
        return $member['status'] == 2;
    }

    protected function changePartyMemberStatus(Player $player, int $status)
    {
        if ($player->partyId) {
            $this->db->update('player_party_member', ['status' => $status], [
                'uid' => $player->id,
                'party_id' => $player->partyId,
            ]);
        }
    }

    protected function getImMessages(Player $player)
    {
        $types = [
            'AND #system' => ['tid' => 0, 'type' => 1],
            'AND #player' => ['tid' => $this->uid(),'type' => [1, 2]],
        ];
        if ($player->partyId) {
            $types['AND #party'] = ['tid' => $player->partyId, 'type' => 3];
        }
        $timestamp = date('Y-m-d H:i:s', strtotime('-30 seconds'));
        $messages = $this->db->select('im', '*', [
            'OR' => $types,
            'created_at[>]' => $timestamp,
            'ORDER' => ['id' => 'DESC'],
            'LIMIT' => 5
        ]);
        return $messages;
    }

    /**
     * @param int $mid
     * @return array
     */
    protected function getAreaInfoByMid(int $mid): array
    {
        $area = $this->db->get('mid', ['[>]qy' => ['mqy' => 'qyid']], [
            'mid.mid',
            'qy.qyid(area_id)',
        ], ['mid.mid' => $mid]);
        if (empty($area)) {
            return [];
        }
        return $area;
    }
}