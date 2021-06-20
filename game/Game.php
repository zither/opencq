<?php
namespace Xian;

use Exception;
use Laminas\Diactoros\Request;
use Laminas\Diactoros\Response;
use League\Plates\Engine;
use Medoo\Medoo;

class Game
{
    /**
     * @var FlashMessage
     */
    public $flash;

    public $routes = [];

    /**
     * @var EncoderInterface;
     */
    public $encoder;

    /**
     * @var Medoo
     */
    public $db;

    /**
     * @var array 配置数组
     */
    public $configs;

    /**
     * @var Engine;
     */
    public $template;

    /**
     * @var string
     */
    public $action;

    /**
     * @var Request
     */
    public $request;

    /**
     * @var Session
     */
    public $session;

    /**
     * @var Event
     */
    public $event;

    /**
     * @var Lock
     */
    public $lock;

    /**
     * @var Collection
     */
    public $container;

    public function __construct(Collection $container)
    {
        $this->container = $container;
        $configs = $container->get('configs');
        // 初始化链接加密类
        switch ($configs['link_encoder'] ?? 'session') {
            case 'session':
                $this->session = $container->get('session');
                $this->encoder = new SessionEncoder($this->session);
                break;
            default:
                $this->encoder = new Encoder($configs['link_encoder_secret'] ?? null);
        }

        $this->db = $container->get('db');
        $this->configs = $configs;
        $this->template = new Engine(ROOT . '/templates');
        $this->flash = new FlashMessage($this->session);
        $this->event = new Event($this->session, $this->db);
        $this->lock = new Lock($this->session);
    }

    public function setRequest($request)
    {
        $this->request = $request;
    }

    /**
     * @param string $cmd
     * @param string $handler
     * @throws Exception
     */
    public function register(string $cmd, string $handler, string $method, bool $saveHistory = true)
    {
        if (isset($this->routes[$cmd])) {
            throw new Exception(sprintf('%s 已注册，请勿重复操作', $cmd));
        }
        $this->routes[$cmd] = [$handler, $method, $saveHistory];
    }

    /**
     * @param array $routers
     */
    public function registerRouters(array $routers)
    {
        $this->routes = $routers;
    }

    /**
     * @param string $queryString
     * @throws Exception
     */
    public function handle(string $queryString, $isFake = false): Response
    {
        parse_str($queryString, $result);
        if (isset($result['cmd'])) {
            if (!$isFake) {
                $plaintext = $this->encoder->decode($result['cmd']);
                unset($result['cmd']);
            } else {
                $plaintext = $queryString;
            }
            parse_str($plaintext, $result);
        }
        foreach ($result as $k => $v) {
            if (strpos($k, '_') === 0) {
                unset($result[$k]);
            }
        }

        $cmd = $result['cmd'] ?? (isset($this->session['uid']) ? 'gomid' :'index');

        // 获取请求锁
        $locked = $this->lock->acquire();

        if ($this->configs['is_under_maintenance'] ?? false) {
            // 优先检查维护模式
            $cmd = 'maintenance';
        } else if ($locked) {
            // 请求锁模式
            $cmd = 'locked';
        } else {
            // 查询当前是否有优先级的事件
            list($event, $whiteList, $rawEvent) = $this->event->current(true);
            // 如果有优先事件，且当前操作不在白名单中
            if (!empty($event) && !in_array($cmd, $whiteList)) {
                $result = $event;
                $cmd = $event['cmd'];
            }
            // 临时事件只执行一次
            if ($rawEvent['is_temporary'] ?? false) {
                $this->event->remove();
            }
        }

        if (DEBUG) {
            Helper::dump($result);
        }

        if (!isset($this->routes[$cmd])) {
            throw new Exception(sprintf('路由 %s 缺少 handler', $cmd));
        }
        $router = $this->routes[$cmd];
        $handlerClass = $router[0];
        $handlerMethod = $router[1];
        $saveHistory = $router[2] ?? true;

        $handler = new $handlerClass($this, $result);
        if (!$handler instanceof AbstractHandler) {
            throw new Exception(sprintf('无效 handler: %s', $cmd));
        }
        // 锁定操作不计入历史
        if (!$locked && $saveHistory) {
            $this->action = http_build_query($result);
        }

        $response = $handler->run($handlerMethod);
        $handler->gc();
        return $response;
    }

    /**
     * 保存历史操作记录
     */
    public function saveAction()
    {
        if (!empty($this->action)) {
            $this->event->saveCurrentAction($this->action);
        }
    }

    public function __destruct()
    {
        unset(
            $this->template,
            $this->db,
            $this->request,
            $this->encoder,
            $this->session,
            $this->event,
            $this->lock,
            $this->configs,
            $this->container,
            $this->flash
        );
    }
}