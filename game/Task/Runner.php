<?php
namespace Xian\Task;

use Xian\DB\RedisPool;
use Xian\ResqueClient;

class Runner
{
    /**
     * @var Heap;
     */
    protected $heap;

    /**
     * @var RedisPool
     */
    protected $redisPool;

    /**
     * @var array
     */
    protected $resqueConfigs;

    /**
     * @var bool
     */
    public $stop = false;

    public function __construct(RedisPool $redisPool, array $resqueConfigs)
    {
        $this->redisPool = $redisPool;
        $this->resqueConfigs = $resqueConfigs;
        $this->heap = new Heap();
    }

    /**
     * @param AbstractTask $task
     */
    public function addTask(AbstractTask $task)
    {
        $this->heap->insert($task);
    }

    public function execute()
    {
        while (!$this->stop) {
            do {
                // 没有要执行的任务
                if ($this->heap->isEmpty()) {
                    break;
                }
                // 获取 redis 实例
                $redis = $this->redisPool->get();
                if (!$redis) {
                    break;
                }
                $client = new ResqueClient($redis, $this->resqueConfigs);
                $now = time();
                /** @var AbstractTask $task */
                while ($task = $this->heap->top()){
                    // 检查对顶的时间戳
                    if ($task->timestamp > $now) {
                        break;
                    }
                    // 弹出任务
                    $task = $this->heap->extract();
                    try {
                        $task->run($client);
                        // 更新下次执行时间
                        if (!$task->isOnce) {
                            $task->timestamp = $now + $task->duration;
                            $this->heap->insert($task);
                        }
                    } catch (\Exception $e) {
                        if (DEBUG) {
                            error_log($e->getMessage());
                        }
                    }
                }
            } while (0);
            isset($redis) && $redis && $this->redisPool->put($redis);
            sleep(1);
        }
    }
}