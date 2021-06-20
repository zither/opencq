<?php

namespace Xian\Combat;

use Xian\Object\Pet;

class PetAttacker extends Attacker
{
    public static $map = [
        'name' => 'name',
        'level' => 'level',
        'hp' => 'hp',
        'maxHp' => 'maxhp',
        'mp' => 'mp',
        'maxMp' => 'maxmp',
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
     * @var Pet
     */
    public $pet;

    /**
     * @param Pet $pet
     */
    public static function fromPet(Pet $pet)
    {
        $attacker = new PetAttacker();
        $attacker->id = $pet->id;
        $attacker->type = self::TYPE_PET;
        $attacker->pet = $pet;
        // 设置属性
        foreach (self::$map as $k1 => $k2) {
            $attacker->$k1 = $pet->$k2 ?? 0;
        }
        return $attacker;
    }

    /**
     * 保存属性
     */
    public function saveStatus()
    {
        foreach (self::$map as $k1 => $k2) {
            $this->pet->$k2 = $this->$k1;
        }
    }

    /**
     * @return Pet
     */
    public function getRawObject(): Pet
    {
        return $this->pet;
    }
}