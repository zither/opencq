<?php

namespace Xian\Combat;

class Combat
{
    const COMBAT_FAILED = 0;

    const COMBAT_SUCCESS = 1;

    /**
     * @var int 当前回合数量
     */
    public $currentRound = 1;

    /**
     * @var Attacker[]
     */
    public $attackers = [];

    /**
     * @var Attacker[]
     */
    public $defenders = [];

    public $aliveAttackerCount = 0;

    public $aliveDefenderCount = 0;

    public $end = false;

    /**
     * @var int combat result
     */
    public $result;

    public $logs = [];

    public function addAttacker(Attacker $attacker)
    {
        $this->attackers[] = $attacker;
        return $this;
    }

    public function addDefender(Attacker $attacker)
    {
        $this->defenders[] = $attacker;
        return $this;
    }

    public function fight(bool $fastAttack = false)
    {
        do {
            // 更改回合数
            $this->currentRound += 1;
            $currentLogs = [];
            $currentLogs['pre_logs'] = [];
            // 战斗前触发存在的效果
            $attackersDotLogs = $this->checkPreEffects($this->attackers);
            if (!empty($attackersDotLogs)) {
                $currentLogs['pre_logs'][] = $attackersDotLogs;
            }
            $defendersDotLOgs = $this->checkPreEffects($this->defenders);
            if (!empty($defendersDotLOgs)) {
                $currentLogs['pre_logs'][] = $defendersDotLOgs;
            }

            // the round for attackers
            $currentLogs['attacker_logs'] = $this->move($this->attackers, $this->defenders);
            if (empty($currentLogs['attacker_logs'])) {
                $this->end = true;
                $this->result = self::COMBAT_FAILED;
                goto saveLogs;
            }
            // the round for defenders
            $currentLogs['defender_logs'] = $this->move($this->defenders, $this->attackers);
            if (empty($currentLogs['defender_logs'])) {
                $this->end = true;
                $this->result = self::COMBAT_SUCCESS;
            }
            $this->checkEnd();
            $postLogs = [];
            if (!$this->end) {
                // 删除过期效果
                $postLogs =  array_merge($postLogs, $this->checkPostEffects($this->attackers));
                $postLogs =  array_merge($postLogs, $this->checkPostEffects($this->defenders));
            }
            saveLogs:
            $this->logs[] = array_merge(
                $currentLogs['pre_logs'],
                $currentLogs['attacker_logs'],
                $currentLogs['defender_logs'] ?? [],
                [$postLogs]
            );
        } while ($fastAttack && !$this->end);

        $this->saveStatus();
    }

    /**
     * 获取战斗日志
     *
     * @return array
     */
    public function logs()
    {
        return $this->logs;
    }

    /**
     * 是否胜利
     *
     * @return bool
     */
    public function won()
    {
        return $this->result === self::COMBAT_SUCCESS;
    }

    /**
     * 检查效果
     * @param Attacker[] $attackers
     * @return array
     */
    protected function checkPreEffects(array $attackers): array
    {
        $logs = [];
        foreach ($attackers as $attacker) {
            if ($attacker->isDied()) {
                continue;
            }
            $selfDamage = 0;
            $attacker->effects->rewind();
            /** @var BaseEffect $effect */
            foreach ($attacker->effects as $effect) {
                if (!$effect->isDot) {
                    continue;
                }

                if ($effect->effectTurn > 1) {
                    $effect->effectTurn -= 1;
                    continue;
                }

                // 减少 Dot 效果生效次数
                $effect->turns--;

                $a = $effect->fromAttacker;
                $d = $attacker;

                if ($a->isDied()) {
                    $toDeleteEffects[] = $effect;
                    $logs[] = sprintf('%s已重伤,%s失去了作用', $a->name, $effect->uiInfo);
                    continue;
                }

                // 获取攻防双方修正参数
                $aArgs = $this->getAttackArgs($a);
                $dArgs = $this->getDefenderArgs($d);

                // 命中率计算
                $mingzhong = $a->mingzhong * (1 + $aArgs['mzcx']) + $aArgs['mzjx'];
                $shanbi = $d->shanbi * (1 + $dArgs['sbcx']) + $dArgs['sbjx'];
                $mingzhongRate = $mingzhong / $shanbi;
                $mingzhongRate = $mingzhongRate * (1 + $aArgs['mzlcx'] / 100 - $dArgs['sblcx'] / 100) + $aArgs['mzljx'] / 100  - $dArgs['sbljx'] / 100;

                // 暴击率计算
                $baoji = $a->baoji * (1 + $aArgs['bjcx']) + $aArgs['bjjx'];
                $shenming = $d->shenming * (1 + $dArgs['smcx']) + $dArgs['smjx'];
                $baojiRate = $baoji / $shenming;
                $baojiRate = $baojiRate * (1 + $aArgs['bjlcx'] / 100 - $dArgs['kblcx'] / 100) + $aArgs['bjljx'] / 100 - $dArgs['kbljx'] / 100;

                $isHit = $mingzhongRate >= 1 || rand(0, 100) < $mingzhongRate * 100;
                if (!$isHit) {
                    $logs[] = sprintf('%s躲过了%s的影响', $d->name, $effect->uiInfo);
                    continue;
                }

                $damage = $this->activeEffect($effect, $a, $aArgs, $d, $dArgs);
                $isCritical = $baojiRate >= 1 || rand(0, 100) < $baojiRate * 100;
                if ($isCritical) {
                    $damage *= 1.72;
                    $logs[] = sprintf('%s被%s会心一击，受到了<span class="text-red-500 font-bold">%d</span>点伤害', $d->name, $effect->uiInfo, $damage);
                } else {
                    $logs[] = sprintf('%s对%s造成了%d点伤害', $effect->uiInfo, $d->name, $damage);
                }

                $selfDamage += $damage;
            }

            $attacker->hp -= floor($selfDamage);
            if ($attacker->hp < 0) {
                $attacker->hp = 0;
            }
        }
        return $logs;
    }

    protected function checkPostEffects(array $attackers)
    {
        $logs = [];
        /** @var Attacker $attacker */
        foreach ($attackers as $attacker) {
            // 减少 CD 回合
            if ($attacker->cd > 0) {
                $attacker->cd--;
            }

            if ($attacker->isDied()) {
                continue;
            }
            // 从头遍历，避免错过
            $attacker->effects->rewind();
            // 遍历结束后再删除，防止打乱遍历结果，漏删效果
            $toDeleteEffects = [];
            /** @var BaseEffect $effect */
            foreach ($attacker->effects as $effect) {
                // 不区分类型，施加方死亡都直接移除
                if ($effect->fromAttacker->isDied()) {
                    $toDeleteEffects[] = $effect;
                    continue;
                }
                // Dot 伤害和当前不生效的属性不减回合
                if ($effect->effectTurn == 2 || $effect->isDot) {
                    continue;
                }
                // 回合数为零直接删除
                $effect->turns--;
                if ($effect->turns < 1) {
                    $toDeleteEffects[] = $effect;
                }
            }
            foreach ($toDeleteEffects as $effect) {
                $effect->removeFrom($attacker);
                $info = !empty($effect->uiInfo) ? $effect->uiInfo : $effect->info;
                $logs[] = sprintf('%s从%s身上移除', $info, $attacker->name);
            }
        }
        return $logs;
    }

    protected function move(array $attackers, array $defenders)
    {
        $logs = [];
        /** @var Attacker $attacker */
        foreach ($attackers as $attacker) {
            if ($attacker->isDied()) {
                continue;
            }
            try {
                $attackInfo = $this->attack($attacker, $attackers, $defenders);
                $logs[] = $attackInfo;
            } catch (\Exception $e) {
                // pass
            }
        }
        return $logs;
    }

    /**
     * @param Attacker $attacker
     * @param Attacker[] $attackers 暂未使用，之后可能用于群体治疗，神圣战甲一类
     * @param Attacker[] $defenders 用于群体伤害类
     * @return array
     */
    protected function attack(Attacker $attacker, array $attackers, array $defenders)
    {
        $logs = [];
        $selfDamage = 0;

        // 当前回合为使用物品
        if ($attacker->currentItem) {
            $logs[] = sprintf('%s使用了<span class="color-green font-bold">%s</span>', $attacker->name, $attacker->currentItem->name);
            /** @var BaseEffect $effect */
            foreach ($attacker->currentItem->effects as $effect) {
                // 暂时只支持属性药品
                if (!$effect->isColumn) {
                    continue;
                }
                $newEffect = clone $effect;
                if ($newEffect->target == 2 || $newEffect->target == 0) {
                    if ($newEffect->isRaw) {
                        // 直接修改源数据的属性
                        $newEffect->trigger($attacker);
                    } else {
                        $newEffect->fromAttacker = $attacker;
                        $newEffect->addTo($attacker);
                    }
                    $logs[] = sprintf($this->effectDescription($newEffect), $attacker->name);
                }
            }
            return $logs;
        }


        // 为了绑定攻击效果和辅助效果对象之间的关系，这里强制规定两者对象必须相同
        $effectedAttackers = [];
        $effectedDefenders = [];

        $logs[] = sprintf('%s使用了<span class="font-bold">%s</span>', $attacker->name, $attacker->currentSkill->name);

        // 将技能效果添加到对应的对象
        if ($attacker->currentSkill) {
            /** @var BaseEffect $effect */
            foreach ($attacker->currentSkill->effects as $effect) {
                // 跳过攻击效果，或者下回合生效的效果
                if ($effect->attackType || $effect->isDot || $effect->effectTurn == 2) {
                    continue;
                }

                // 选择目标
                $isAttackerTargets = $effect->target == 2 || $effect->target == 0;
                if ($isAttackerTargets) {
                    if (empty($effectedAttackers)) {
                        $effectedAttackers = $selected = $this->selectTargets($attackers, $effect->targetNum, $effect->targetMode);
                    } else {
                        $selected = $effectedAttackers;
                    }
                } else {
                    if (empty($effectedDefenders)) {
                        $selected = $this->selectTargets($defenders, $effect->targetNum, $effect->targetMode);
                    } else {
                        $selected = $effectedDefenders;
                    }
                }
                // 对附加技能效果
                foreach ($selected as $v) {
                    $newEffect = clone $effect;
                    $newEffect->fromAttacker = $attacker;
                    $v->effects->attach($newEffect);
                    $logs[] = sprintf('%s受到%s的影响，%s', $v->name, $newEffect->uiInfo, $this->newEffectDescription($newEffect));
                }
            }
        }

        // 伤害统计
        $totalDamageMap = [];
        foreach ($attacker->currentSkill->effects as $effect) {
            // 跳过所有非攻击效果
            if (!$effect->attackType) {
                continue;
            }
            // 获取攻方修正参数
            $attackerArgs = $this->getAttackArgs($attacker);
            // 选择防方对象
            if (empty($effectedDefenders)) {
                $effectedDefenders = $this->selectTargets($defenders, $effect->targetNum, $effect->targetMode);
            }

            // 对所有目标施加效果
            foreach ($effectedDefenders as $defender) {
                if (!isset($totalDamageMap[$defender->id])) {
                    $totalDamageMap[$defender->id] = 0;
                }
                // 获取防方修正参数
                $defenderArgs = $this->getDefenderArgs($defender);

                // 命中率计算
                $mingzhong = $attacker->mingzhong * (1 + $attackerArgs['mzcx']) + $attackerArgs['mzjx'];
                $shanbi = $defender->shanbi * (1 + $defenderArgs['sbcx']) + $defenderArgs['sbjx'];
                $mingzhongRate = $mingzhong / $shanbi;
                $mingzhongRate = $mingzhongRate * (1 + $attackerArgs['mzlcx'] / 100 - $defenderArgs['sblcx'] / 100) + $attackerArgs['mzljx'] / 100  - $defenderArgs['sbljx'] / 100;

                // 暴击率计算
                $baoji = $attacker->baoji * (1 + $attackerArgs['bjcx']) + $attackerArgs['bjjx'];
                $shenming = $defender->shenming * (1 + $defenderArgs['smcx']) + $defenderArgs['smjx'];
                $baojiRate = $baoji / $shenming;
                $baojiRate = $baojiRate * (1 + $attackerArgs['bjlcx'] / 100 - $defenderArgs['kblcx'] / 100) + $attackerArgs['bjljx'] / 100 - $defenderArgs['kbljx'] / 100;

                // 获取攻击伤害
                $damage = $this->activeEffect($effect, $attacker, $attackerArgs, $defender, $defenderArgs);
                // 保存 DOT 伤害，不再重复计算
                if ($effect->isDot) {
                    $newEffect = clone $effect;
                    // 保存施加方信息
                    $newEffect->fromAttacker = $attacker;
                    $defender->effects->attach($newEffect);
                    $logs[] = sprintf('%s对%s施加了%s效果，持续%d回合', $attacker->name, $defender->name, $newEffect->uiInfo, $newEffect->turns);
                    continue;
                }

                for ($i = 0; $i < $effect->combo; $i++) {
                    $random = rand(0, 100);
                    $isHit = $mingzhongRate >= 1 || $random < $mingzhongRate * 100;
                    if (!$isHit) {
                        $logs[] = sprintf('%s躲过了%s的%s', $defender->name, $attacker->name, $effect->uiInfo);
                        // 添加未命中日志
                        continue;
                    }

                    $random = rand(0, 100);
                    $isCritical = $baojiRate >= 1 || $random < $baojiRate * 100;
                    // 如果暴击，伤害增加
                    if ($isCritical) {
                        $damage *= 1.72;
                    }

                    // 计算总伤害
                    if (!$effect->isDot || ($effect->isDot && $effect->effectTurn == 1)) {
                        $totalDamageMap[$defender->id] += $damage;
                        $damageType = $effect->attackType == 1 ? '物理' : '法术';
                        if ($isCritical) {
                            $logs[] = sprintf('%s的%s对%s会心一击，造成了<span class="text-red-500 font-bold">%d</span>点%s伤害', $attacker->name, $effect->uiInfo, $defender->name,$damage, $damageType);
                        } else {
                            $logs[] = sprintf('%s的%s对%s造成了%d点%s伤害', $attacker->name, $effect->uiInfo, $defender->name, $damage, $damageType);
                        }
                    }
                }
            }
        }

        //$wugong = $attacker->wugong * (1 + A) + B;
        //$wufang = $defender->wufang * (1 + C) + D
        //$damage = ($wugong * $attacker->baqi) / $wufang;
        //$finalDamage = $damage * (1 +E - F) + (1 + G - H);
        // 提高自身原始属性，乘法修正，比如攻击力上升10%
        // 降低对方原始属性，乘法修正，比如防御力降低10%
        // 提供最终伤害乘法修正，比如最终伤害上升10%
        // 提供最终伤害加法修正，比如附加100点伤害，比如真实伤害或者物理穿透，或者伤害抵抗
        foreach ($defenders as $defender) {
            if (!isset($totalDamageMap[$defender->id])) {
                continue;
            }
            $totalDamage = floor($totalDamageMap[$defender->id]);
            $defender->hp -= $totalDamage;
            if ($defender->hp < 0) {
                $defender->hp = 0;
            }
        }

        $selfDamage = floor($selfDamage);
        $attacker->hp -= $selfDamage;
        if ($attacker->hp < 0) {
            $attacker->hp = 0;
        }

        return $logs;
    }

    protected function newEffectDescription(BaseEffect $effect)
    {
        $name = $effect->uiInfo;
        if ($effect->isColumn) {
            switch ($effect->column) {
                case 'wugong':
                    $column = '物攻';
                    break;
                case 'fagong':
                    $column = '法攻';
                    break;
                case 'wufang':
                    $column = '物防';
                    break;
                case 'fafang':
                    $column = '法防';
                    break;
                case 'mingzhong':
                    $column = '命中';
                    break;
                case 'shanbi':
                    $column = '闪避';
                    break;
                case 'baoji':
                    $column = '暴击';
                    break;
                case 'shenming':
                    $column = '神明';
                    break;
                case 'hp':
                    $column = '气血';
                    break;
                case 'max_hp':
                    $column = '气血上限';
                    break;
            }
        } else {
            if ($effect->isWushang) {
                $column = '物伤';
            } else if ($effect->isFashang) {
                $column = '法伤';
            } else if ($effect->isWumian) {
                $column = '物免';
            } else if ($effect->isFamian) {
                $column = '法免';
            } else if ($effect->isMingzhong) {
                $column = '命中率';
            } else if ($effect->isShanbi) {
                $column = '闪避率';
            } else if ($effect->isBaoji) {
                $column = '暴击率';
            } else if ($effect->isShenming) {
                $column = '抗暴率';
            }
        }

        if ($effect->effectType == 1) {
            $type = $effect->amount > 0 ? '提升' : '降低';
        } else {
            $type = $effect->amount > 0 ? '增加' : '减少';
        }

        $amount = abs($effect->amount);
        $turns = $effect->turns;
        $percent = $effect->effectType == 1 ? '%': '点';

        return "{$column}{$type}{$amount}{$percent}，持续{$turns}回合";
    }

    protected function effectDescription(BaseEffect $effect)
    {
        $name = $effect->uiInfo;
        if ($effect->isColumn) {
            switch ($effect->column) {
                case 'wugong':
                    $column = '物攻';
                    break;
                case 'fagong':
                    $column = '法攻';
                    break;
                case 'wufang':
                    $column = '物防';
                    break;
                case 'fafang':
                    $column = '法防';
                    break;
                case 'mingzhong':
                    $column = '命中';
                    break;
                case 'shanbi':
                    $column = '闪避';
                    break;
                case 'baoji':
                    $column = '暴击';
                    break;
                case 'shenming':
                    $column = '神明';
                    break;
                case 'hp':
                    $column = '生命值';
                    break;
                case 'max_hp':
                    $column = '生命值上限';
                    break;
            }
        } else {
            if ($effect->isWushang) {
                $column = '物伤';
            } else if ($effect->isFashang) {
                $column = '法伤';
            } else if ($effect->isWumian) {
                $column = '物免';
            } else if ($effect->isFamian) {
                $column = '法免';
            } else if ($effect->isMingzhong) {
                $column = '命中率';
            } else if ($effect->isShanbi) {
                $column = '闪避率';
            } else if ($effect->isBaoji) {
                $column = '暴击率';
            } else if ($effect->isShenming) {
                $column = '抗暴率';
            }
        }

        if ($effect->effectType == 1) {
            $type = $effect->amount > 0 ? '提升' : '降低';
        } else {
            $type = $effect->amount > 0 ? '增加' : '减少';
        }

        $amount = abs($effect->amount);
        $turns = $effect->turns;
        $percent = $effect->effectType == 1 ? '%%': '点';

        //return "{$name}让%s的{$column}{$type}{$amount}{$percent}，持续{$turns}回合";
        return "{$name}让%s的{$column}{$type}{$amount}{$percent}";
    }

    protected function activeEffect(BaseEffect $effect, Attacker $attacker, array $attackerArgs,  Attacker $defender, array $defenderArgs)
    {
        if ($effect->attackType == 1) {
            $attack = $attacker->wugong * (1 + $attackerArgs['wgcx'] / 100) + $attackerArgs['wgjx'];
            // 攻击力乘以技能威力
            $attack = $attack * ($effect->amount / 100);
            $defense = $defender->wufang * (1 + $defenderArgs['wfcx'] / 100)  + $defenderArgs['wfjx'];
            $damage = ($attack * $attacker->baqi) / ($defense ?: 1);
            $finalDamage = $damage * (1 + $attackerArgs['wscx'] / 100 - $defenderArgs['wmcx'] / 100) + $attackerArgs['wsjx'] + $defenderArgs['wmjx'];
        } else {
            $attack = $attacker->fagong * (1 + $attackerArgs['fgcx'] / 100) + $attackerArgs['fgjx'];
            // 攻击力乘以技能威力
            $attack = $attack * ($effect->amount / 100);
            $defense = $defender->fafang * (1 + $defenderArgs['ffcx'] / 100)  + $defenderArgs['ffjx'];
            $damage = ($attack * $attacker->baqi) / ($defense ?: 1);
            $finalDamage = $damage * (1 + $attackerArgs['fscx'] / 100 - $defenderArgs['fmcx'] / 100) + $attackerArgs['fsjx'] + $defenderArgs['fmjx'];
        }

        return $finalDamage;
    }

    /**
     * 获取攻击方攻击修正
     * @param Attacker $attacker
     * @return array
     */
    protected function getAttackArgs(Attacker $attacker): array
    {

        $arr = [
            // 物攻乘修
            'wgcx' => 0,
            // 法攻乘修
            'fgcx' => 0,
            // 物攻加修
            'wgjx' => 0,
            // 法攻加修
            'fgjx' => 0,
            // 物伤乘修
            'wscx' => 0,
            // 法伤乘修
            'fscx' => 0,
            // 物伤加修
            'wsjx' => 0,
            // 法伤加修
            'fsjx' => 0,
            // 暴击乘修
            'bjcx' => 0,
            // 暴击加修
            'bjjx' => 0,
            // 暴击率乘修
            'bjlcx' => 0,
            // 暴击率加修
            'bjljx' => 0,
            // 命中乘修
            'mzcx' => 0,
            // 命中加修
            'mzjx' => 0,
            // 命中率乘修
            'mzlcx' => 0,
            // 命中率加修
            'mzljx' => 0,
        ];

        $effects = clone $attacker->effects;
        /** @var BaseEffect $effect */
        foreach ($effects as $effect) {
            if ($effect->isColumn) {
                switch ($effect->column) {
                    case 'wugong':
                        if ($effect->effectType == 1) {
                            $arr['wgcx'] += $effect->amount;
                        } else {
                            $arr['wgjx'] += $effect->amount;
                        }
                        break;
                    case 'fagong':
                        if ($effect->effectType == 1) {
                            $arr['fgcx'] += $effect->amount;
                        } else {
                            $arr['fgjx'] += $effect->amount;
                        }
                        break;
                    case 'baoji':
                        if ($effect->effectType == 1) {
                            $arr['bjcx'] += $effect->amount;
                        } else {
                            $arr['bjjx'] += $effect->amount;
                        }
                        break;
                    case 'mingzhong':
                        if ($effect->effectType == 1) {
                            $arr['mzcx'] += $effect->amount;
                        } else {
                            $arr['mzjx'] += $effect->amount;
                        }
                        break;
                }
                continue;
            }

            if ($effect->isWushang) {
                if ($effect->effectType == 1) {
                    $arr['wscx'] += $effect->amount;
                } else {
                    $arr['wsjx'] += $effect->amount;
                }
                continue;
            }

            if ($effect->isFashang) {
                if ($effect->effectType == 1) {
                    $arr['fscx'] += $effect->amount;
                } else {
                    $arr['fsjx'] += $effect->amount;
                }
                continue;
            }

            if ($effect->isMingzhong) {
                if ($effect->effectType == 1) {
                    $arr['mzlcx'] += $effect->amount;
                } else {
                    $arr['mzljx'] += $effect->amount;
                }
                continue;
            }

            if ($effect->isBaoji) {
                if ($effect->effectType == 1) {
                    $arr['bjlcx'] += $effect->amount;
                } else {
                    $arr['bjljx'] += $effect->amount;
                }
                continue;
            }
        }

        return $arr;
    }


    /**
     * 获取防御方防御参数
     * @param Attacker $defender
     * @return array
     */
    protected function getDefenderArgs(Attacker $defender): array
    {
        $arr = [
            // 物防乘修
            'wfcx' => 0,
            // 物防加修
            'wfjx' => 0,
            // 法防乘修
            'ffcx' => 0,
            // 法防加修
            'ffjx' => 0,
            // 物免乘修
            'wmcx' => 0,
            // 物免加修
            'wmjx' => 0,
            // 法免乘修
            'fmcx' => 0,
            // 法免加修
            'fmjx' => 0,
            // 神明乘修
            'smcx' => 0,
            // 神明加修
            'smjx' => 0,
            // 闪避乘修
            'sbcx' => 0,
            // 闪避加修
            'sbjx' => 0,
            // 抗暴率乘修
            'kblcx' => 0,
            // 抗暴率加修
            'kbljx' => 0,
            // 闪避率乘修
            'sblcx' => 0,
            // 闪避率加修
            'sbljx' => 0,
        ];

        $effects = clone $defender->effects;
        /** @var BaseEffect $effect */
        foreach ($effects as $effect) {

            if ($effect->isColumn) {
                switch ($effect->column) {
                    case 'wufang':
                        if ($effect->effectType == 1) {
                            $arr['wfcx'] += $effect->amount;
                        } else {
                            $arr['wfjx'] += $effect->amount;
                        }
                        break;
                    case 'fafang':
                        if ($effect->effectType == 1) {
                            $arr['ffcx'] += $effect->amount;
                        } else {
                            $arr['ffjx'] += $effect->amount;
                        }
                        break;
                    case 'shenming':
                        if ($effect->effectType == 1) {
                            $arr['smcx'] += $effect->amount;
                        } else {
                            $arr['smjx'] += $effect->amount;
                        }
                        break;
                    case 'shanbi':
                        if ($effect->effectType == 1) {
                            $arr['sbcx'] += $effect->amount;
                        } else {
                            $arr['sbjx'] += $effect->amount;
                        }
                        break;
                }
                continue;
            }

            if ($effect->isWumian) {
                if ($effect->effectType == 1) {
                    $arr['wmcx'] += $effect->amount;
                } else {
                    $arr['wmjx'] += $effect->amount;
                }
                continue;
            }

            if ($effect->isFamian) {
                if ($effect->effectType == 1) {
                    $arr['fmcx'] += $effect->amount;
                } else {
                    $arr['fmjx'] += $effect->amount;
                }
                continue;
            }

            if ($effect->isShanbi) {
                if ($effect->effectType == 1) {
                    $arr['sblcx'] += $effect->amount;
                } else {
                    $arr['sbljx'] += $effect->amount;
                }
                continue;
            }

            if ($effect->isShenming) {
                if ($effect->effectType == 1) {
                    $arr['kblcx'] += $effect->amount;
                } else {
                    $arr['kbljx'] += $effect->amount;
                }
                continue;
            }
        }

        return $arr;
    }

    /**
     * 保存属性
     */
    protected function saveStatus()
    {
        $all = array_merge($this->attackers, $this->defenders);
        /** @var Attacker $attacker */
        foreach ($all as $attacker) {
            $attacker->saveStatus();
        }
    }

    /**
     * 检查战斗状态
     */
    protected function checkEnd()
    {
        $someAttackersAreAlive = false;
        foreach ($this->attackers as $attacker) {
            if (!$attacker->isDied()) {
                $someAttackersAreAlive= true;
                break;
            }
        }
        $someDefendersAreAlive = false;
        foreach ($this->defenders as $defender) {
            if (!$defender->isDied()) {
                $someDefendersAreAlive = true;
            }
        }
        if (!($someAttackersAreAlive && $someDefendersAreAlive)) {
            if ($someAttackersAreAlive) {
                $this->end = true;
                $this->result = self::COMBAT_SUCCESS;
            } else {
                $this->end = true;
                $this->result = self::COMBAT_FAILED;
            }
        }
    }

    public function clearLogs()
    {
        $this->logs = [];
    }


    /**
     * @param array $attackers
     * @return Attacker
     * @throws \Exception
     */
    public function randomChose(array $attackers)
    {
        $alive = [];
        /** @var Attacker $attacker */
        foreach ($attackers as $attacker) {
            if ($attacker->isDied()) {
                continue;
            }
            $alive[] = $attacker;
        }
        $n = count($alive);
        if ($n === 0) {
            throw new \Exception('All defenders were died');
        }
        $index = rand(0, $n - 1);
        return $alive[$index];
    }

    /**
     * @param Attacker[] $attackers
     * @param int $num
     * @param int $mode
     * @return Attacker[]
     */
    public function selectTargets(array $attackers, int $num = 1, int $mode = 1): array
    {
        $validAttackers = [];
        foreach ($attackers as $attacker) {
            if ($attacker->isDied()) {
                continue;
            }
            $validAttackers[] = $attacker;
        }
        if (empty($validAttackers)) {
            return [];
        }
        // 相当于全选
        if ($num >= count($validAttackers)) {
            return $validAttackers;
        }
        $selected = [];
        switch ($mode) {
            // 全体目标
            case 3:
                return $attackers;
            // 随机直线单位
            case 4:
                // 敌方单位少于两个，直接攻击
                if (count($validAttackers) < 2) {
                    return $validAttackers;
                }
                // 随机选取单双线
                $random = rand(1, 2);
                foreach ($validAttackers as $k => $v) {
                    if (($k & 1) === ($random & 1)) {
                        $selected[] = $v;
                    }
                }
                return $selected;
            // 暂时只支持随机模式
            default:
                $indexArr = array_rand($validAttackers, $num);
                if (is_numeric($indexArr)) {
                    $indexArr = [$indexArr];
                }
                foreach ($indexArr as $i) {
                    $selected[] = $validAttackers[$i];
                }
                return $selected;
        }
    }
}