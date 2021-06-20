<?php
namespace Xian\Job\Cron;

use Xian\Job\BaseJob;

class DeleteCombat extends BaseJob
{
    public function perform($args)
    {
        // 删除一分钟前的战斗日志
        $duration = 60;
        $timestamp = date('Y-m-d H:i:s', time() - $duration);
        $this->db->delete('pve_logs', ['created_at[<]' => $timestamp]);
        $this->db->delete('combat', ['is_end' => true, 'updated_at[<]' => $timestamp]);
    }
}