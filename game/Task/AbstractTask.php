<?php
namespace Xian\Task;

use Xian\ResqueClient;

abstract class AbstractTask
{
    /**
     * @var int 间隔
     */
    public $duration;

    /**
     * @var int 执行时间
     */
    public $timestamp;

    /**
     * @var bool
     */
    public $isOnce;

    public function __construct(int $duration = 60, int $timestamp = 0, bool $isOnce = false)
    {
        if ($duration <= 0) {
            $duration = 60;
        }
        $this->duration = $duration;
        $this->timestamp = $timestamp;
        $this->isOnce = $isOnce;
    }

    abstract public function run(ResqueClient $client);
}