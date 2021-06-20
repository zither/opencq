<?php
use Workerman\Worker;
use Workerman\Lib\Timer;
use Workerman\Db\MedooPool;
use Workerman\Db\RedisPool;
use Xian\Helper;
use Xian\Game;
use Workerman\Protocols\Http\RawRequest;
use Workerman\Protocols\Http\Response;
use Xian\Collection;
use Xian\Session;
use Symfony\Component\Yaml\Yaml;
use Xian\ResqueClient;
use Laminas\Diactoros\ServerRequest;

include_once __DIR__ . "/bootstrap.php";
include_once ROOT . '/third/Workerman/Autoloader.php';

Warriorman\Worker::rename(); // 将Workerman改为Workerman
Warriorman\Runtime::enableCoroutine(); // hook相关函数

$worker = new Worker("tcp://0.0.0.0:8888");
$worker->count = 2;
$worker->name = "workerman";
$worker->medooPool = null;
$worker->redisPool = null;

$routers = require_once ROOT . '/game/routers.php';
$resqueConfigs = Yaml::parse(file_get_contents(ROOT . '/configs/resque.yml'));
$configs = Helper::loadConfigs(ROOT . '/configs/config.json');
Response::init();

$worker->onWorkerStart = function ($worker) {
    $configs = Helper::$configs;
    $config = [];
    $config["host"] = $configs['db_host'];
    $config["port"] = $configs['db_port'] ?? 3306;
    $config["username"] = $configs['db_user'];
    $config["password"] = $configs['db_password'];
    $config["db_name"] = $configs['db_database'];
    $config["min"] = 1;
    $config["max"] = 15;
    $config["spareTime"] = 15;
    $worker->medooPool = new MedooPool($config);
    $worker->redisPool= new RedisPool([
        'scheme' => 'tcp',
        'host' => '127.0.0.1',
        'port' => 6379,
        'auth' => 'cloud',
        'min' => 1,
        'max' => 15,
        'spareTime' => 15,
    ]);

    if (isset($configs['debug']) && $configs['debug']) {
        error_reporting(E_ALL);
        define('DEBUG', true);
    } else {
        error_reporting(0);
        define('DEBUG', false);
    }
};

$worker->onMessage = function ($connection, $data) use ($worker, $routers, $resqueConfigs) {
    try {
        RawRequest::enableCache(false);
        $request = new RawRequest($data);
        $container = new Collection();
        $container['configs'] = Helper::$configs;
        $redis = $worker->redisPool->get();
        if (!$redis) {
            throw new RuntimeException('Can not get redis object from pool');
        }
        $session = new Session($redis);
        if ($sessionId = $request->cookie($session->getName())) {
            $session->setId($sessionId);
        }
        $session->start();
        $container['session'] = $session;

        $query = $request->queryString();
        parse_str( $query ?: '', $queryString);
        $serverRequest = new ServerRequest(
            [],
            $request->file(),
            $request->uri(),
            $request->method(),
            'php://memory',
            $request->header(),
            $request->cookie(),
            $queryString,
            $request->post(),
            $request->protocolVersion()
        );
        $container['request'] = $serverRequest;

        /** @var Medoo\Medoo $db */
        $container['db'] = $db = $worker->medooPool->get();
        if (!$db) {
            throw new RuntimeException('Can not get medoo object from pool');
        }
        $container['resque'] = new ResqueClient($redis, $resqueConfigs);

        $game = new Game($container);
        $game->registerRouters($routers);
        $queryString = $request->queryString() ?: '/';
        $response = $game->handle($queryString);
        $realResponse = new Response(
            $response->getStatusCode(),
            $response->getHeaders(),
            $response->getBody()->getContents()
        );
        // 设置 cookie
        if (!$sessionId) {
            $realResponse->cookie(
                $session->getName(),
                $session->getId(),
                time() + 3600 * 24,
                '/',
                '',
                false,
                true
            );
        }
        $connection->send((string)$realResponse);
    } catch (Throwable $e) {
        if (DEBUG) {
            $content = $e->getMessage() . $e->getTraceAsString();
        } else {
            $content = '500';
        }
        $realResponse = new Response(
            500,
            [],
            $content
        );
        $connection->send((string)$realResponse);
    } finally {
        if ($container['db'] ?? false) {
            if ($game ?? false) {
                $game->saveAction();
            }
            $worker->medooPool->put($container['db']);
        }
        if ($redis ?? false) {
            $session->commit();
            $worker->redisPool->put($redis);
        }
        if (isset($response)) {
            $response->getBody()->close();
        }
        if (isset($serverRequest)) {
            $serverRequest->getBody()->close();
        }

        // 手动释放
        unset($session, $redis, $container, $game, $request, $response, $serverRequest);

        $requestNum = $worker->requestNum();
        // 1w次请求重启一次worker
        if ($requestNum >= 10000) {
            //error_log("Current request num: $requestNum, worker({$worker->workerId})'s process is starting to reload");
            $worker->reload();
        }
        //gc_collect_cycles();
    }
};

Worker::runAll();