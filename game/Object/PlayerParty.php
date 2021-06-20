<?php

namespace Xian\Object;

use Medoo\Medoo;
use Xian\Helper;

class PlayerParty
{
    /**
     * @var int 编号
     */
    public $id = 0;

    /**
     * @var string 名称
     */
    public $name;

    /**
     * @var int 队长编号
     */
    public $uid;

    /**
     * @var int 是否关闭入队申请
     */
    public $isClosed;

    /**
     * @var string 创建时间
     */
    public $createdAt;

    /**
     * @var string 队长名称
     */
    public $leaderName;

    use FromArrayTrait;

    public static function get(Medoo $db, int $id, array $conditions = [])
    {
        $where = ['id' => $id];
        if (!empty($conditions)) {
            $where = array_merge($where, $conditions);
        }

        $arr = $db->get('player_party', '*', $where);
        if (empty($arr)) {
            return new self();
        }
        $pet = self::fromArray($arr)->withDatabase($db);
        return $pet;
    }

    public static function add(Medoo $db, array $data)
    {
        $db->insert('player_party', $data);
        return $db->id();
    }

    public static function update(Medoo $db, int $id, array $data)
    {
        $db->update('player_party', $data, ['id' => $id]);
    }
}