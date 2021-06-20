<?php

namespace Xian\Combat;

class Shortcuts
{
    const TYPE_SKILL = 0x01;
    const TYPE_ITEM = 0x02;

    public $slots = [];

    public function init(Attacker $attacker)
    {
        if ($attacker->type === Attacker::TYPE_PLAYER) {

        }
    }
}