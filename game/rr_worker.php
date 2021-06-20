<?php
/**
 * @var Goridge\RelayInterface $relay
 */
use Spiral\Goridge;
use Spiral\RoadRunner;
use HansOtt\PSR7Cookies\SetCookie;
use Xian\Game;
use Xian\Helper;
use Xian\Session;
use Predis\Client;
use Symfony\Component\Yaml\Yaml;
use Xian\Collection;
use Xian\ResqueClient;
use Laminas\Diactoros\Response;

ini_set('display_errors', 'stderr');
require_once 'game/bootstrap.php';

$worker = new RoadRunner\Worker(new Goridge\StreamRelay(STDIN, STDOUT));
$psr7 = new RoadRunner\PSR7Client($worker);

$routers = require_once ROOT . '/game/routers.php';
$configs = Helper::loadConfigs(ROOT . '/configs/config.json');
if (isset($configs['debug']) && $configs['debug']) {
    error_reporting(E_ALL);
    define('DEBUG', true);
} else {
    error_reporting(0);
    define('DEBUG', false);
}
// 初始化数据库
Helper::createDB($configs['db_host'], $configs['db_database'], $configs['db_user'], $configs['db_password']);
// 初始化队列类
$resqueConfigs = Yaml::parse(file_get_contents(ROOT . '/configs/resque.yml'));
$redis= new Client($resqueConfigs['redis']);

while ($req = $psr7->acceptRequest()) {
    try {
        $container = new Collection();
        // 检查请求是否携带 session 相关的 Cookie
        $cookies = $req->getCookieParams();
        $session = new Session($redis);
        $cookies = $req->getCookieParams();
        if ($sessionId = $cookies[$session->getName()] ?? null) {
            $session->setId($sessionId);
        }
        $session->start();

        $container['session'] = $session;
        $container['db'] = Helper::$db;
        $container['resque'] = new ResqueClient($redis, $resqueConfigs);
        $container['request'] = $req;
        $game = new Game($container);
        $game->registerRouters($routers);

        // 处理请求
        $queryString = http_build_query($req->getQueryParams());
        $game->setRequest($req);
        $response = $game->handle($queryString);
        if (!$sessionId) {
            $cookie = new SetCookie(
                $session->getName(),
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
        $psr7->respond($response);
    } catch (\Throwable $e) {
        if (DEBUG) {
            $content = $e->getMessage();
        } else {
            $content = '500 Internal Server Error';
        }
        $response = new Response($content, 500);
        $psr7->respond($response);
    } finally {
        if (isset($session) && $session->isStarted()) {
            $session->commit();
        }
        unset($session);
        unset($container);
        if (isset($game)) {
            $game->saveAction();
            unset($game);
        }
    }
}