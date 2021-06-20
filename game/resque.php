<?php
use Xian\Helper;

include_once __DIR__ . "/bootstrap.php";

$configs = Helper::loadConfigs(ROOT . '/configs/config.json');
if (isset($configs['debug']) && $configs['debug']) {
    error_reporting(E_ALL);
    define('DEBUG', true);
} else {
    error_reporting(0);
    define('DEBUG', false);
}
// Resque 环境关闭 session
define('DISABLE_SESSION', true);

// 初始化数据库
Helper::createDB($configs['db_host'], $configs['db_database'], $configs['db_user'], $configs['db_password']);