<?php
namespace Xian\Job\Cron;

use Xian\Job\BaseJob;

class TruncateCmdHistory extends BaseJob
{
    public function perform($args)
    {
        $this->db->query('truncate cmd_history');
    }
}