<?php
namespace Xian;

use Predis\Client;

class SessionHandler
{
    /**
     * @var Client;
     */
    private $redis;
    private $session_expiretime = 3600;

    public function __construct(Client $redis)
    {
        $this->redis = $redis;
        session_set_save_handler(
            array($this, "open"),
            array($this, "close"),
            array($this, "read"),
            array($this, "write"),
            array($this, "destroy"),
            array($this, "gc")
        );
    }

    public function open($path, $name)
    {
        return true;
    }

    public function close()
    {
        return true;
    }

    public function read($id)
    {
        // Get the specified record in redis
        $value = $this->redis->get($id);
        if ($value) {
            return $value;
        } else {
            return '';
        }
    }

    public function write($id, $data)
    {
        // stored with session ID as the key
        if ($this->redis->set($id, $data)) {
            // Set the expiration time of data in redis, that is, session expiration time
            $this->redis->expire($id, $this->session_expiretime);
            return true;
        }

        return false;
    }

    public function destroy($id)
    {
        // delete the specified record in redis
        if ($this->redis->del($id)) {
            return true;
        }
        return false;
    }

    public function gc($maxlifetime)
    {
        return true;
    }

    public function cleanUp()
    {
        unset($this->redis);
    }
}