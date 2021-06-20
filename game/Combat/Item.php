<?php

namespace Xian\Combat;

use SplObjectStorage;

class Item
{
    /**
     * @var int
     */
    public $id = 0;

    /**
     * @var string
     */
    public $name = '';

    /**
     * @var SplObjectStorage
     */
    public $effects;

    /**
     * Shortcut constructor.
     * @param array $columns
     */
    public function __construct(array $tiem, array $effects)
    {

        $this->id = $tiem['id'];
        $this->name = $tiem['name'];
        $this->effects = new SplObjectStorage();
        foreach ($effects as $v) {
            if (!$v['is_combat']) {
                continue;
            }
            $this->effects->attach(new BaseEffect($v, BaseEffect::ORIGIN_TYPE_ITEM));
        }
    }
}