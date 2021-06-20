<?php
namespace Xian\Job;

use Xian\Helper;
use Resque;

class CheckPlayerOnlineStatus extends BaseJob
{
    public function perform($args)
    {
        $time = $args['time'] ?? 60 * 5;
        $player = $this->db->get('game1', '*', ['id' => $args['uid']]);
        $now = time();
        $difference = $now  - strtotime($player['endtime']);
        if ($difference < $time) {
            // 时间未达到，以差值作为延迟再次检查，直到离线为止
            $later = $time - $difference;
            Resque::later($later, CheckPlayerOnlineStatus::class, ['uid' => $player['id'], 'time' => $time]);
            return;
        }
        // 标记为下线
        $data = ['sfzx' => 0];
        if ($player['party_id']) {
            $res = $this->db->update('player_party_member', [
                'status' => 1,
            ], [
                'uid' => $player['id'],
                'party_id' => $player['party_id'],
                'is_leader' => 0,
            ]);
            if ($res->rowCount()) {
                $name = Helper::getVipName($player);
                $this->db->insert('im', [
                    'uid' => 0,
                    'tid' => $player['party_id'],
                    'type' => 3,
                    'content' => "{$name}已离线，自动取消跟随模式",
                ]);
            }
        }
        $this->db->update('game1', $data, ['id' => $args['uid']]);
    }
}