<?php
namespace Xian\Job;

use Medoo\Medoo;
use Xian\Helper;

class BaseJob
{
    /**
     * @var Medoo
     */
    protected $db;

    public function setUp()
    {
        $configs = Helper::$configs;
        $this->db = new Medoo([
            'database_type' => 'mysql',
            'database_name' => $configs['db_database'],
            'server' => $configs['db_host'],
            'username' => $configs['db_user'],
            'password' => $configs['db_password'],
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_general_ci',
            'option' => [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION],
            // 时区设置，避免出现 timestamp 转换错误
            'command' => ["SET time_zone='+8:00'"],
        ]);
    }
}