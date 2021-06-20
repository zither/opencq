<?php

namespace Xian\Combat;

use player\Player;

class PlayerAttacker extends Attacker
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
     * @var Player
     */
    public $player;

    /**
     * @var array
     */
    public $skillStatus = [];

    /**
     * @var array
     */
    public $itemStatus = [];

    /**
     * @param Player $player
     * @param array $shortCuts
     */
    public static function fromPlayer(Player $player, array $shortCuts = [])
    {
        $attacker = new PlayerAttacker();
        $attacker->id = $player->id;
        $attacker->type = self::TYPE_PLAYER;
        $attacker->player = $player;
        // 设置属性
        foreach (self::$map as $k1 => $k2) {
            $attacker->$k1 = $player->$k2 ?? 0;
        }
        if (empty($shortCuts)) {
            for ($i = 0; $i < 6; $i++) {
                $attacker->shortcuts->attach(new Shortcut(['index' => $i + 1, 'name' => sprintf('快捷键%d', $i + 1)]), $i + 1);
            }
        } else {
            // 设置快捷键
            foreach ($shortCuts as $k => $v) {
                $attacker->shortcuts->attach(new Shortcut($v), $k + 1);
            }
        }
        return $attacker;
    }

    /**
     * 保存属性
     */
    public function saveStatus()
    {
        foreach (self::$map as $k1 => $k2) {
            $this->player->$k2 = $this->$k1;
        }
    }

    public function getRawObject()
    {
        return $this->player;
    }

    public function setSkillStatus(int $playerSkillId, int $round)
    {
        $this->skillStatus[$playerSkillId] = $round;
    }

    public function setItemStatus(int $playerItemId, int $round)
    {
        $this->itemStatus[$playerItemId] = $round;
    }
}