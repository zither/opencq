<?php
namespace Xian\Task;

use Xian\ResqueClient;
use Xian\Job\Cron\TruncateCmdHistory;

class TruncateCmdHistoryTask extends AbstractTask
{
    public function run(ResqueClient $client)
    {
        $client->push(TruncateCmdHistory::class, []);
    }
}