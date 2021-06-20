<?php

namespace Xian\Object;

use Medoo\Medoo;
use Xian\Helper;

class PlayerPartyMember
{
    /**
     * @var int 编号
     */
    public $id = 0;

    /**
     * @var int 队伍编号
     */
    public $partyId;

    /**
     * @var int 队员编号
     */
    public $uid;

    /**
     * @var string 名称
     */
    public $name;

    /**
     * @var int 入队状态，0申请中，1自由活动，2跟随
     */
    public $status;

    /**
     * @var string 入队状态
     */
    public $statusText;

    use FromArrayTrait;

    public static function get(Medoo $db, int $id, array $conditions = [])
    {
        $where = ['id' => $id];
        if (!empty($conditions)) {
            $where = array_merge($where, $conditions);
        }

        $arr = $db->get('player_party_member', '*', $where);
        if (empty($arr)) {
            return new self();
        }
        $pet = self::fromArray($arr)->withDatabase($db);
        return $pet;
    }

    public static function add(Medoo $db, array $data)
    {
        $db->insert('player_party_member', $data);
        return $db->id();
    }

    public static function update(Medoo $db, int $id, array $data)
    {
        $db->update('player_party_member', $data, ['id' => $id]);
    }

    public static function delete(Medoo $db, array $condition): bool
    {
        $res = $db->delete('player_party_member', $condition);
        if ($res->rowCount()) {
            return true;
        }
        return false;
    }

    public static function getMembersByPartyId(Medoo $db, int $partyId, array $status = [])
    {
        if (!empty($status)) {
            $arr = $db->select('player_party_member', [
                'party_id' => $partyId,
                'status' => $status
            ]);
        } else {
            $arr = $db->select('player_party_member', [
                'party_id' => $partyId,
            ]);
        }
    }
}