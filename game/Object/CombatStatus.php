<?php
namespace Xian\Object;

use Medoo\Medoo;
use Xian\Combat\Combat;
use Xian\Helper;

class CombatStatus
{
    use FromArrayTrait;

    /**
     * @var int 编号
     */
    public $id;

    /**
     * @var int 攻击者编号
     */
    public $attackerId;

    /**
     * @var int 防御者编号
     */
    public $defenderId;

    /**
     * @var string 战斗状态
     */
    public $data;

    /**
     * @var string 战斗日志
     */
    public $logs;

    /**
     * @var int 战斗类型，1为 PVE 2为 PVP
     */
    public $type;

    /**
     * @var int 战斗是否结束
     */
    public $isEnd;

    /**
     * @var int 战斗结果，0未结束，１攻方胜利，２防方胜利，３攻方逃跑，４防方逃跑
     */
    public $resultType;

    /**
     * @var string 上回合攻击时间
     */
    public $lastTurnTimestamp;

    /**
     * @var string 战斗开始时间
     */
    public $createdAt;

    /**
     * @var string 战斗更新时间
     */
    public $updatedAt;

    /**
     * @return Combat
     */
    public function getCombat(): Combat
    {
        $combat = unserialize($this->data);
        if (empty($combat)) {
            throw new \RuntimeException('Get combat object failed');
        }
        return $combat;
    }

    /**
     * @return bool 删除战斗记录
     */
    public function delCombat(): bool
    {
        $stmt = $this->db->delete('combat', ['id' => $this->id]);
        return $stmt->rowCount() > 0;
    }

    /**
     * @param int $id
     * @return CombatStatus
     */
    public static function get(Medoo $db, int $id): CombatStatus
    {
        $arr = $db->get('combat', '*', ['id' => $id]);
        if (empty($arr)) {
            return new static();
        }
        return static::fromArray($arr)->withDatabase($db);
    }
}