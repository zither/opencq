<?php

namespace Xian\Player;

use Medoo\Medoo;

class PrivateItem
{
    /**
     * 地图私有怪物刷新时间
     */
    const TYPE_MONSTER_FRESH_TIME = 1;

    /**
     * 怪物击杀记录，特指不会重新刷新的怪物
     */
    const TYPE_MONSTER_KILLED = 2;

    /**
     * 触发操作标识
     */
    const TYPE_OPERATION_IDENTITY = 3;

    /**
     * @var Medoo
     */
    protected $db;

    /**
     * @var int
     */
    protected $uid;

    /**
     * @var int
     */
    protected $type;

    public function __construct(Medoo $db, int $uid, int $type)
    {
        $this->db = $db;
        $this->uid = $uid;
        $this->type = $type;
    }

    public function getByKey(string $key): array
    {
        $item = $this->db->get('player_private_items', '*', [
            'uid' => $this->uid,
            'type' => $this->type,
            'k' => $key,
        ]);
        return empty($item) ? [] : $item;
    }

    public function updateByKey(string $key, $value):  bool
    {
        $res = $this->db->update('player_private_items', [
            'v' => $value,
            'updated_at' => date('Y-m-d H:i:s'),
        ], [
            'uid' => $this->uid,
            'type' => $this->type,
            'k' => $key,
        ]);
        return $res->rowCount() > 0;
    }

    public function add(string $key, $value, int $type): bool
    {
        $res = $this->db->insert('player_private_items', [
            'uid' => $this->uid,
            'type' => $type,
            'k' => $key,
            'v' => $value
        ]);
        return $res->rowCount() > 0;
    }
}