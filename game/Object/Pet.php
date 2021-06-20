<?php

namespace Xian\Object;

use Medoo\Medoo;
use Xian\Helper;

class Pet
{
    /**
     * @var int 编号
     */
    public $id = 0;

    /**
     * @var string 名称
     */
    public $name;

    /**
     * @var int 等级
     */
    public $level;

    /**
     * @var int 用户编号
     */
    public $uid;

    /**
     * @var int 地图编号
     */
    public $mid;

    /**
     * @var int 怪物原型编号
     */
    public $gid;

    /**
     * @var int 经验
     */
    public $exp;

    /**
     * @var int 升级经验
     */
    public $maxExp;

    /**
     * @var int 当前血量
     */
    public $hp;

    /**
     * @var int 最大血量
     */
    public $maxhp;

    /**
     * @var int 当前蓝量
     */
    public $mp;

    /**
     * @var int 最大蓝量
     */
    public $maxmp;

    /**
     * @var int 霸气
     */
    public $baqi;

    /**
     * @var int 物攻
     */
    public $wugong;

    /**
     * @var int 法攻
     */
    public $fagong;

    /**
     * @var int 物防
     */
    public $wufang;

    /**
     * @var int 法防
     */
    public $fafang;

    /**
     * @var int 闪避
     */
    public $shanbi;

    /**
     * @var int 命中
     */
    public $mingzhong;

    /**
     * @var int 暴击
     */
    public $baoji;

    /**
     * @var int 神明
     */
    public $shenming;

    /**
     * @var int 是否已孵化
     */
    public $isBorn;

    /**
     * @var int 品质
     */
    public $quality;

    /**
     * @var string
     */
    public $qualityName;

    /**
     * @var int 是否出战
     */
    public $isOut;

    /**
     * @var int 召唤技能编号
     */
    public $playerSkillId;

    /**
     * @var string 宠物技能
     */
    public $skills;

    use FromArrayTrait;

    public static function get(Medoo $db, int $id, array $conditions = [])
    {
        $where = ['id' => $id];
        if (!empty($conditions)) {
            $where = array_merge($where, $conditions);
        }

        $arr = $db->get('player_pet', '*', $where);
        if (empty($arr)) {
            return new self();
        }
        $pet = self::fromArray($arr)->withDatabase($db);
        $pet->qualityName = self::quality($pet->quality);
        return $pet;
    }

    /**
     * @param int $uid
     * @return Pet[]
     */
    public static function getAllByUid(Medoo $db, int $uid): array
    {
        $arr = $db->select('player_pet', '*', ['uid' => $uid]);
        if (empty($arr)) {
            return [];
        }
        $pets = [];
        foreach ($arr as $v) {
            $pet = self::fromArray($v)->withDatabase($db);
            $pet->qualityName = self::quality($pet->quality);
            $pets[] = $pet;
        }
        return $pets;
    }

    public static function add(Medoo $db, array $pet)
    {
        $db->insert('player_pet', $pet);
        return $db->id();
    }

    public static function update(Medoo $db, int $id, array $data)
    {
        $db->update('player_pet', $data, ['id' => $id]);
    }

    public function gainExp($exp)
    {
        if ($this->exp + $exp < $this->maxExp) {
            $this->exp += $exp;
            $this->db->update('player_pet', ['exp[+]' =>$exp], ['id' => $this->id]);
        } else {
            $data = $this->db->get('system_data', '*', ['level' => $this->level + 1]);
            $updated = [
                'level' => $this->level + 1,
                'exp' => 0,
                'max_exp' => $data['player_exp'],
                'wugong' => $data['player_gongji'],
                'fagong' => $data['player_gongji'],
                'wufang' => $data['player_fangyu'],
                'fafang' => $data['player_fangyu'],
                'baqi' => $data['player_baqi'],
                'mingzhong' => $data['player_mingzhong'],
                'shanbi' => $data['player_shanbi'],
                'baoji' => $data['player_baoji'],
                'shenming' => $data['player_shenming'],
            ];
            $this->db->update('player_pet', $updated, ['id' => $this->id]);
        }
    }

    /**
     * @param int $quality
     * @return string
     */
    public static function quality(int $quality): string
    {
        $map = ['普通', '优秀', '卓越', '非凡', '完美', '逆天'];
        if (isset($map[$quality])) {
            return $map[$quality];
        }
        return $map[0];
    }
}