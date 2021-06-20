<?php
namespace Xian;

use Closure;
use Predis\Client;
use Resque\Helpers\Stats;
use Resque\Helpers\SerializableClosure;
use Resque\Job;
use Resque\Queue;

class ResqueClient
{
    /**
     * @var Client
     */
    protected $redis;

    /**
     * @var array
     */
    protected $configs = [];

    /**
     * ResqueClient constructor.
     * @param Client $redis
     * @param array $configs
     */
    public function __construct(Client $redis, array $configs)
    {
        $this->redis = $redis;
        $this->configs = $configs;
    }

    /**
     * @param $job
     * @param array $data
     * @param string|null $queue
     * @return bool
     */
    public function push($job, array $data, string $queue = null)
    {
        if (empty($queue)) {
            $queue = $this->getDefaultQueue();
        }
        $packet = $this->createJobPacket($queue, $job, $data);
        $this->redis->sadd($this->addNamespace(Queue::redisKey()), [$queue]);
        $status = $this->redis->rpush($this->addNamespace(Queue::redisKey($queue)), $packet['payload']);
        if ($status < 1) {
            return false;
        }
        $this->redis->hmset($this->addNamespace(Job::redisKey($packet['id'])), $packet);
        $this->redis->hincrby($this->addNamespace(Stats::DEFAULT_KEY), 'queued', 1);
        $this->redis->hincrby($this->addNamespace(Queue::redisKey($queue, 'stats')), 'queued', 1);
        $this->redis->hincrby($this->addNamespace(Stats::DEFAULT_KEY), 'total', 1);
        $this->redis->hincrby($this->addNamespace(Queue::redisKey($queue, 'stats')), 'total', 1);
        return true;
    }

    /**
     * @param $delay
     * @param $job
     * @param array $data
     * @param string|null $queue
     * @return bool
     */
    public function later($delay, $job, array $data, string $queue = null)
    {
        if (empty($queue)) {
            $queue = $this->getDefaultQueue();
        }
        $packet = $this->createJobPacket($queue, $job, $data);
        $packet['status'] = Job::STATUS_DELAYED;
        $this->redis->sadd($this->addNamespace(Queue::redisKey()), [$queue]);
        $status = $this->redis->zadd($this->addNamespace(Queue::redisKey($queue, 'delayed')), [
            $packet['payload'] => $this->getTimestamp($delay)
        ]);
        if ($status < 1) {
            return false;
        }
        $this->redis->hmset($this->addNamespace(Job::redisKey($packet['id'])), $packet);
        $this->redis->hincrby($this->addNamespace(Stats::DEFAULT_KEY), 'delayed', 1);
        $this->redis->hincrby($this->addNamespace(Queue::redisKey($queue, 'stats')), 'delayed', 1);
        $this->redis->hincrby($this->addNamespace(Stats::DEFAULT_KEY), 'total', 1);
        $this->redis->hincrby($this->addNamespace(Queue::redisKey($queue, 'stats')), 'total', 1);
        return true;
    }

    /**
     * @param string $queue
     * @param Closure|string $class
     * @param array|null $data
     * @param int $runAt
     * @return array
     */
    public function createJobPacket(string $queue, $class, array $data = null, int $runAt = 0)
    {
        $id = Job::createId($queue, $class, $data, $runAt);
        $method = null;
        if ($class instanceof Closure) {
            $data = $class;
            $class = 'Resque\Helpers\ClosureJob';
        } else {
            if (strpos($class, '@') !== false) {
                list($class, $method) = explode('@', $class, 2);
            }
            $class = trim($class, '\\ ');
        }
        if ($data instanceof Closure) {
            $closure = serialize(new SerializableClosure($data));
            $tmpData = compact('closure');
        } else {
            $tmpData = $data;
        }
        $payload = json_encode(['id' => $id, 'class' => $class, 'data' => $tmpData]);
        $packet = [
            'id' => $id,
            'queue' => $queue,
            'payload' => $payload,
            'worker' => '',
            'status' => Job::STATUS_WAITING,
            'created' => microtime(true),
            'updated' => microtime(true),
            'delayed' => 0,
            'started' => 0,
            'finished' => 0,
            'output' => '',
            'exception' => null,
        ];
        return $packet;
    }

    /**
     * @return string
     */
    protected function getDefaultQueue(): string
    {
        return $this->configs['default']['jobs']['queue'] ?? 'default';
    }

    /**
     * @param $delay
     * @return int|string
     */
    protected function getTimestamp($delay)
    {
        // If it's a datetime object conver to unix time
        if ($delay instanceof \DateTime) {
            $delay = $delay->getTimestamp();
        }
        if (!is_numeric($delay)) {
            throw new \InvalidArgumentException('The delay "'.$delay.'" must be an integer or DateTime object.');
        }
        // If the delay is smaller than 3 years then assume that an interval
        // has been passed i.e. 600 seconds, otherwise it's a unix timestamp
        if ($delay < 94608000) {
            $delay += time();
        }
        return $delay;
    }

    /**
     * @param string $key
     * @return string
     */
    protected function addNamespace(string $key): string
    {
        $namespace = $this->configs['redis']['namespace'] ?? 'resque';
        $namespace = ltrim($namespace, ':');
        return "$namespace:$key";
    }

    public function __destruct()
    {
        unset($this->redis);
    }
}