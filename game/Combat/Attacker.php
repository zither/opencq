<?php
namespace Xian\Combat;

use \SplObjectStorage;

abstract class Attacker
{
    const TYPE_PLAYER = 0x01;
    const TYPE_MONSTER = 0x02;
    const TYPE_PET = 0x04;
    const TYPE_NPC = 0x08;

    /**
     * @var int 编号
     */
    public $id;

    /**
     * @var string 名称
     */
    public $name;

    /**
     * @var int 等级
     */
    public $level;

    /**
     * @var int 分类
     */
    public $type;

    /**
     * @var int 血量
     */
    public $hp;

    /**
     * @var int 最大血量
     */
    public $maxHp;

    /**
     * @var int 霸气
     */
    public $baqi;

    /**
     * @var int 攻击力
     */
    public $wugong;

    /**
     * @var int 法术攻击力
     */
    public $fagong;

    /**
     * @var int 防御
     */
    public $wufang;

    /**
     * @var int 法术防御力
     */
    public $fafang;

    /**
     * @var int 暴击
     */
    public $baoji;

    /**
     * @var int 神明
     */
    public $shenming;

    /**
     * @var int 命中
     */
    public $mingzhong;

    /**
     * @var int 闪避
     */
    public $shanbi;

    /**
     * @var SplObjectStorage
     */
    public $shortcuts;

    /**
     * @var SplObjectStorage(BaseEffect[])
     */
    public $effects;

    /**
     * @var array 所有字段
     */
    public $columns;

    /**
     * @var int 当前使用的技能
     */
    public $currentShortcut;

    /**
     * @var Skill
     */
    public $currentSkill;

    /**
     * @var Item
     */
    public $currentItem;

    /**
     * @var int 技能调息时间
     */
    public $cd = 0;

    protected function __construct()
    {
        // Don't call this method directly
        $this->effects = new SplObjectStorage();
        $this->shortcuts = new SplObjectStorage();
    }

    /**
     * @return bool 角色是否死亡
     */
    public function isDied()
    {
        return $this->hp <= 0;
    }

    /**
     * @return string
     */
    public function getUniqueId()
    {
        switch ($this->type) {
            case self::TYPE_PLAYER:
                $prefix = 'player';
                break;
            case self::TYPE_MONSTER:
                $prefix = 'monster';
                break;
            case self::TYPE_PET:
                $prefix = 'pet';
                break;
            case self::TYPE_NPC:
                $prefix = 'npc';
                break;
            default:
                $prefix = 'unknown';
        }
        return sprintf('%s_%d', $prefix, $this->id);
    }

    public function column(string $column)
    {
        $formattedColumn = $this->formatKey($column);
        if (!property_exists($this, $formattedColumn)) {
            return 0;
        }
        $mul = 0;
        $add = 0;
        $this->effects->rewind();
        foreach ($this->effects as $effect) {
            if (!$effect->isColumn || $effect->column != $column) {
                continue;
            }
            if ($effect->effectType == 1) {
                $mul += $effect->amount;
            } else {
                $add += $effect->amount;
            }
        }
        return $this->$formattedColumn * (1 + $mul / 100) + $add;
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

    /**
     * 保存属性
     * @return mixed
     */
    abstract public function saveStatus();

    /**
     * 获取原始对象
     * @return mixed
     */
    abstract public function getRawObject();
}