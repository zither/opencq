<?php

namespace Xian\Combat;

interface EffectInterface
{
    public function addTo(Attacker $attacker);

    public function removeFrom(Attacker $attacker);
}