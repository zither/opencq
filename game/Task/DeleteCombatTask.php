<?php
namespace Xian\Task;

use Xian\Job\Cron\DeleteCombat;
use Xian\ResqueClient;

class DeleteCombatTask extends AbstractTask
{
    public function run(ResqueClient $client)
    {
        $client->push(DeleteCombat::class, []);
    }
}