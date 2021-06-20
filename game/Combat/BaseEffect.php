<?php

namespace Xian\Combat;

class BaseEffect implements EffectInterface
{
    const ORIGIN_TYPE_SKILL = 1;
    const ORIGIN_TYPE_ITEM = 2;

    public $id;

    public $info;

    public $uiInfo;

    public $originId;

    /**
     * @var string 属性名称
     */
    public $column;

    /**
     * @var int
     */
    public $amount;

    public $effectType;

    /**
     * @var int 持续回合数量
     */
    public $turns;

    public $effectTurn;

    public $isColumn;

    public $isWushang;
    public $isWumian;

    public $isFashang;
    public $isFamian;

    public $isMingzhong;
    public $isShanbi;

    public $isBaoji;
    public $isShenming;

    public $isDot;

    /**
     * @var int 是否直接修改源属性，比如修改 HP
     */
    public $isRaw;

    /**
     * @var Attacker
     */
    public $fromAttacker;

    public $attackType;

    public $target;

    /**
     * @var int 目标数量
     */
    public $targetNum;

    /**
     * @var int 目标选择模式，1随机
     */
    public $targetMode;

    public $combo;

    /**
     * @var string 效果标识
     */
    public $identity;

    /**
     * @var int 效果是否唯一
     */
    public $isUnique;

    /**
     * @var int dot伤害
     */
    public $damage;

    public function __construct(array $columns, int $type)
    {
        foreach ($columns  as $k => $v) {
            $formatKey = $this->formatKey($k);
            if (property_exists($this, $formatKey)) {
                $this->$formatKey = $v;
            }
        }
        if ($type === self::ORIGIN_TYPE_SKILL) {
            $this->originId = $columns['skill_id'];
        } else if ($type === self::ORIGIN_TYPE_ITEM) {
            $this->originId = $columns['item_id'];
        }
    }

    public function trigger(Attacker $attacker)
    {
        $key = $this->column;
        if (!$this->isRaw || !property_exists($attacker, $key)) {
            return;
        }
        if ($this->effectType == 1) {
            $attacker->$key = $attacker->$key * (1 + $this->amount/ 100);
        } else {
            $attacker->$key += $this->amount;
        }
        // 检查属性是否有上限
        $maxKey = "max_$key";
        if (property_exists($attacker, $this->formatKey($maxKey))) {
            $maxValue = $attacker->column("max_$key");
            if ($attacker->$key > $maxValue) {
                $attacker->$key = $maxValue;
            }
        }
    }

    /**
     * @param Attacker $attacker
     */
    public function addTo(Attacker $attacker)
    {
        $key = $this->column;
        // 如果效果不能重复
        if ($this->isUnique) {
            $attacker->effects->rewind();
            foreach ($attacker->effects as $effect) {
                if ($effect->identity === $this->identity) {
                    $effect->removeFrom($attacker);
                }
            }
        }
        $attacker->effects->attach($this);
        return [$key => $this->amount];
    }

    /**
     * @param Attacker $attacker
     */
    public function removeFrom(Attacker $attacker)
    {
        $attacker->effects->detach($this);
    }

    /**
     * @param string $key
     * @return string
     */
    protected function formatKey(string $key): string
    {
        $arr = explode('_', $key);
        $words = [array_shift($arr)];
        foreach ($arr as $word) {
            $words[] = ucfirst($word);
        }
        return implode('', $words);
    }
}