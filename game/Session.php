<?php

namespace Xian;

use Predis\Client;

class Session extends Collection
{
    /**
     * @var Client $predis
     */
    protected $predis;

    /**
     * @var string
     */
    protected $name = 'PHPSESSID';

    /**
     * @var bool $started
     */
    protected $started = false;

    /**
     * @var array
     */
    protected $data = [];

    /**
     * @var string
     */
    protected $token;

    /**
     * @var int 存活时间
     */
    protected $ttl = 300;

    /**
     * @var array
     */
    protected $config = [
        'namespace' => 'session',
        'name' => 'Predisson'
    ];

    public function __construct(Client $predis, $config = [])
    {
        $this->predis = $predis;
        if (!empty($config))
            $this->config = $config;
    }

    /**
     * @return string
     * @throws \Exception
     */
    private function generateRandomToken(): string
    {
        return bin2hex(random_bytes(20));
    }

    /**
     * Starts the session storage.
     *
     * @return bool True if session started
     *
     * @throws \RuntimeException if session fails to start
     * @throws \Exception
     */
    public function start()
    {
        if (empty($this->token)) {
            while ($this->predis->exists($this->token = $this->generateRandomToken())) {
                continue;
            };
        }
        $this->started = true;
        $this->data = unserialize($this->predis->get($this->getKey()));
        return true;
    }

    /**
     * Returns the session ID.
     *
     * @return string The session ID
     * @throws \Exception
     */
    public function getId()
    {
        if (!$this->started) {
            $this->start();
        }

        return $this->token;
    }

    /**
     * Sets the session ID.
     *
     * @param string $id
     */
    public function setId($id)
    {
        $this->token = $id;
    }

    /**
     * Returns the session name.
     *
     * @return mixed The session name
     */
    public function getName() : string
    {
        return $this->name;
    }

    /**
     * Sets the session name.
     *
     * @param string $name
     */
    public function setName($name) : void
    {
        $this->name = $name;
    }

    /**
     * Checks if the session was started.
     *
     * @return bool
     */
    public function isStarted()
    {
        return $this->started;
    }

    /**
     * @return bool
     */
    public function commit()
    {
        $data = serialize($this->data);
        $this->predis->set($this->getKey(), $data);
        $this->predis->expire($this->getKey(), $this->ttl);
        $this->token = null;
        $this->started = false;
        return true;
    }

    /**
     * @return bool
     */
    public function destroy()
    {
        $this->predis->del($this->getKey());
        $this->token = null;
        $this->started = false;
        return true;
    }

    /**
     * 删除其他 session 数据
     *
     * @param string $token
     * @return bool
     * @throws \Exception
     */
    public function destroySessionById(string $token)
    {
        if (empty($token)) {
            return false;
        }
        $status = $this->predis->del($this->getKey($token));
        return true;
    }

    /**
     * @return string
     * @throws \Exception
     */
    protected function getKey(string $token = null)
    {
        if (is_null($token)) {
            $token = $this->getId();
        }
        return "{$this->config['namespace']}:{$token}";
    }
}
