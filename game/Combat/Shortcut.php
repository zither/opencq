<?php

namespace Xian\Combat;

use SplObjectStorage;

class Shortcut
{
    const TYPE_SKILL = 1;
    const TYPE_ITEM = 2;
    const TYPE_UNDEFINED = 3;

    /**
     * @var int
     */
    public $type = self::TYPE_UNDEFINED;

    /**
     * @var int
     */
    public $id = 0;

    /**
     * @var int
     */
    public $index = 0;

    /**
     * @var string
     */
    public $name = '';

    /**
     * @var SplObjectStorage
     */
    public $effects;

    /**
     * @var int
     */
    public $count = 0;

    /**
     * @var bool
     */
    public $initiated = false;

    /**
     * Shortcut constructor.
     * @param array $columns
     */
    public function __construct(array $columns)
    {
        $this->effects = new SplObjectStorage();
        foreach ($columns as $k => $v) {
            if (isset($this->$k)) {
                $this->$k = $v;
                continue;
            }
            if (strpos($k, 'e_') === false) {
                continue;
            }
            $effectColumn = $this->format($k);
            $this->effects->attach(new BaseEffect($effectColumn, $v));
        }
    }

    public function trigger(Attacker $attacker)
    {
        $logs = [];
        /** @var EffectInterface $effect */
        foreach ($this->effects as $effect) {
            $logs[] = $effect->addTo($attacker);
        }
        $attacker->currentShortcut = 0;
        return $logs;
    }

    /**
     * @param string $key
     * @return string
     */
    protected function format(string $key): string
    {
        $key = substr($key, 2);
        $words = explode('_', $key);
        if (count($words) === 1) {
            return $words[0];
        }
        $key = array_shift($words);
        foreach ($words as $word) {
            $key .= ucfirst($word);
        }
        return $key;
    }

    /**
     * @return bool 是否定义
     */
    public function isDefined()
    {
        return $this->type !== self::TYPE_UNDEFINED;
    }
}