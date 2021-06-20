<?php

namespace Xian\Combat;

use player\MidGuaiwu;

class MonsterAttacker extends Attacker
{
    public static $map = [
        'name' => 'name',
        'level' => 'level',
        'hp' => 'hp',
        'maxHp' => 'maxhp',
        'baqi' => 'baqi',
        'wugong' => 'wugong',
        'wufang' => 'wufang',
        'fagong' => 'fagong',
        'fafang' => 'fafang',
        'baoji' => 'baoji',
        'shenming' => 'shenming',
        'mingzhong' => 'mingzhong',
        'shanbi' => 'shanbi',
    ];

    /**
     * @var int 怪物种类
     */
    public $monsterType;

    /**
     * @var \player\MidGuaiwu
     */
    protected $monster;

    /**
     * @param array $columns
     * @param array $shortCuts
     */
    public static function fromMonster(MidGuaiwu $monster, array $shortCuts = [])
    {
        $attacker = new MonsterAttacker();
        $attacker->id = $monster->id;
        $attacker->type = self::TYPE_MONSTER;
        $attacker->monster = $monster;
        $attacker->monsterType = $monster->type;
        // 设置属性
        foreach (self::$map as $k1 => $k2) {
            $attacker->$k1 = $monster->$k2 ?? 0;
        }
        // 设置快捷键
        foreach ($shortCuts as $k => $v) {
            $attacker->shortcuts->attach(new Shortcut($v), $k + 1) ;
        }

        return $attacker;
    }

    /**
     * 保存属性
     */
    public function saveStatus()
    {
        foreach (self::$map as $k1 => $k2) {
            $this->monster->$k2 = $this->$k1;
        }
    }

    public function getRawObject()
    {
        return $this->monster;
    }
}