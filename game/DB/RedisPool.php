<?php
namespace Xian\DB;

use Predis\Client;

class RedisPool extends AbstractPool
{
    protected function createDb(): Client
    {
        $params = [
            'scheme' => $this->config['scheme'],
            'host' => $this->config['host'],
            'port' => $this->config['port'],
            'password' => $this->config['auth'],
        ];
        $redis = new Client($params);
        $redis->connect();
        return $redis;
    }

    protected function checkDb($db)
    {
        /** @var Client $db */
        return $db->isConnected();
    }
    
    protected function closeDb($db)
    {
        /** @var Client $db */
        $db->disconnect();
        unset($db);
    }
}