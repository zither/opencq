<?php
declare(strict_types=1);

use Xian\Helper;
use Symfony\Component\Yaml\Yaml;
use Workerman\Protocols\Http\RawRequest;
use Workerman\Protocols\Http\Response;
use Laminas\Diactoros\ServerRequest;
use Xian\Collection;
use Xian\Session;
use Xian\DB\MedooPool;
use Xian\DB\RedisPool;
use Xian\ResqueClient;
use Xian\Game;

include_once __DIR__ . "/bootstrap.php";
$routers = require_once ROOT . '/game/routers.php';
$resqueConfigs = Yaml::parse(file_get_contents(ROOT . '/configs/resque.yml'));
$configs = Helper::loadConfigs(ROOT . '/configs/config.json');
Response::init();
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
$medooPool = new MedooPool($config);
$redisPool= new RedisPool([
    'scheme' => 'tcp',
    'host' => $configs['redis_host'],
    'port' => $configs['redis_port'],
    'auth' => $configs['redis_password'],
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

$server = new Swow\Socket(Swow\Socket::TYPE_TCP);
$server->bind('127.0.0.1', 8888)->listen();

// 简单的定时任务
Swow\Coroutine::run(function() use ($redisPool, $resqueConfigs) {
    $runner = new \Xian\Task\Runner($redisPool, $resqueConfigs);
    $runner->addTask(new \Xian\Task\TruncateCmdHistoryTask(3600));
    $runner->addTask(new \Xian\Task\DeleteCombatTask(60));
    $runner->execute();
});

while (true) {
    $client = $server->accept();
    Swow\Coroutine::run(function () use ($client, $medooPool, $redisPool, $routers, $resqueConfigs) {
        $buffer = new Swow\Buffer();
        try {
            /** Receive HTTP Request */
            $offset = 0;
            while (true) {
                $length = $client->recv($buffer);
                if ($length === 0) {
                    throw new \Swow\Exception("Receive Request Failed");
                }
                $eof = strpos($buffer->toString(), "\r\n\r\n", $offset);
                if ($eof > 0) {
                    break;
                }
                $offset += $length;
            }

            /** Start To Handle Request */
            RawRequest::enableCache(false);
            $request = new RawRequest($buffer->toString());
            $container = new Collection();
            $container['configs'] = Helper::$configs;
            $redis = $redisPool->get();
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
            $container['db'] = $db = $medooPool->get();
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
            $client->sendString((string)$realResponse);
            /** Response Sent */

        } catch (Swow\Socket\Exception $exception) {
            echo "No.{$client->getFd()} goaway! {$exception->getMessage()}" . PHP_EOL;
        } catch (PDOException $exception) {
            // 处理数据库断开逻辑
            if (strpos($exception->getMessage(), 'server has gone away') !== false) {
                $db->pdo = null;
                unset($db);
            }
            throw $exception;
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
            $client->sendString((string)$realResponse);
        } finally {
            $buffer->clear();
            isset($game) && $game->saveAction();
            isset($db) && $medooPool->put($db);
            isset($session) && $session->commit();
            isset($redis) && $redisPool->put($redis);
            isset($response) && $response->getBody()->close();
            isset($serverRequest) && $serverRequest->getBody()->close();
            unset($buffer, $session, $redis, $container, $game, $request, $response, $serverRequest);
        }
    });
}