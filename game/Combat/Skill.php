<?php

namespace Xian\Combat;

use SplObjectStorage;

class Skill
{

    /**
     * @var int
     */
    public $id = 0;

    /**
     * @var int 武器类型
     */
    public $equipType = 0;

    /**
     * @var string
     */
    public $name = '';

    /**
     * @var SplObjectStorage
     */
    public $effects;

    /**
     * @var int 技能调息
     */
    public $cd;

    /**
     * @var int 类型
     */
    public $type;

    /**
     * @var int 等级
     */
    public $level;

    /**
     * Shortcut constructor.
     * @param array $columns
     */
    public function __construct(array $skill, array $effects)
    {

        $this->id = $skill['id'];
        $this->name = $skill['name'];
        $this->equipType = $skill['equip_type'];
        $this->cd = $skill['tiaoxi'];
        $this->effects = new SplObjectStorage();
        foreach ($effects as $v) {
            $this->effects->attach(new BaseEffect($v, BaseEffect::ORIGIN_TYPE_SKILL));
        }
    }
}