<?php
namespace Xian\DB;

use Medoo\Medoo;

/**
 * mysql连接池
 */
class MedooPool extends AbstractPool
{
    protected function createDb(): Medoo
    {
        $db = new Medoo([
            'database_type' => 'mysql',
            'database_name' => $this->config['db_name'],
            'server' => $this->config['host'],
            'username' => $this->config['username'],
            'password' => $this->config['password'],
            'port' => $this->config['port'] ?? 3306,
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_general_ci',
            'option' => [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_EMULATE_PREPARES => false
            ],
            'command' => ["SET time_zone='+8:00'"],
        ]);
        $db->startedAt = time();

        return $db;
    }

    protected function checkDb($db)
    {
        return isset($db->startedAt) && time() - $db->startedAt < 600 ;
    }
    
    protected function closeDb($db)
    {
        /** @var Medoo $db */
        $db->pdo = null;
        unset($db);
    }
}