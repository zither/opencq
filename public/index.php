<?php

use Xian\Game;
use Xian\Helper;
use Laminas\Diactoros\Response;
use Xian\Session;
use Predis\Client;
use Xian\Collection;
use HansOtt\PSR7Cookies\SetCookie;
use Symfony\Component\Yaml\Yaml;
use Xian\ResqueClient;
use Laminas\Diactoros\ServerRequestFactory;

require_once dirname(__DIR__) . '/game/bootstrap.php';

try {
    $container = new Collection();
    $container['configs'] = $configs = Helper::loadConfigs(ROOT . '/configs/config.json');
    $resqueConfigs = Yaml::parse(file_get_contents(ROOT . '/configs/resque.yml'));

    if (isset($configs['debug']) && $configs['debug']) {
        error_reporting(E_ALL);
        define('DEBUG', true);
    } else {
        error_reporting(0);
        define('DEBUG', false);
    }
    $container['request'] = ServerRequestFactory::fromGlobals();
    $redis = new Client([
        'scheme' => 'tcp',
        'host' => $configs['redis_host'],
        'port' => $configs['redis_port'],
        'password' => $configs['redis_password'],
    ]);
    $session = new Session($redis);
    $sessionName = $session->getName();
    if (isset($_COOKIE[$sessionName])) {
        $session->setId($_COOKIE[$sessionName]);
    }
    $session->start();
    $container['session'] = $session;
    $container['resque'] = new ResqueClient($redis, $resqueConfigs);

    // 初始化数据库
    $container['db'] = Helper::createDB(
        $configs['db_host'],
        $configs['db_database'],
        $configs['db_user'],
        $configs['db_password']
    );
    // 初始化队列类
    Resque::loadConfig(ROOT . '/configs/resque.yml');

    $game = new Game($container);
    $routers = require_once ROOT . '/game/routers.php';
    $game->registerRouters($routers);

    // 处理请求
    $queryString = $_SERVER['QUERY_STRING'] ?? '';
    $response = $game->handle($queryString);

    if (!isset($_COOKIE[$sessionName])) {
        $cookie = new SetCookie(
            $sessionName,
            $session->getId(),
            time() + 3600 * 24,
            '/',
            '',
            false,
            true,
            'strict'
        );
        $response = $cookie->addToResponse($response);
    }

    foreach ($response->getHeaders() as $k => $values) {
        foreach ($values as $v) {
            header($k . ':' . $v);
        }
    }
    if ($response instanceof Response) {
        echo $response->getBody()->getContents();
    }
} catch (Exception $e) {
    $errorReporting = error_reporting();
    if ($errorReporting === E_ALL) {
        if ($e instanceof PDOException) {
            echo $e->getMessage();
            exit($e->getTraceAsString());
        } else {
            exit($e->getMessage());
        }
    }
    exit('404 NOT FOUND');
} finally {
    if (isset($session) && $session->isStarted()) {
        $session->commit();
    }
    if ($game ?? false) {
        // 保存历史记录
        $game->saveAction();
    }
}
