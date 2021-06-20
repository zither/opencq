<?php
namespace Xian;

use Laminas\Diactoros\Response\RedirectResponse;
use Laminas\Diactoros\Response;
use Laminas\Diactoros\ServerRequest;
use Laminas\Diactoros\StreamFactory;
use Medoo\Medoo;
use Exception;
use League\Plates\Engine;
use player\Player;
use Xian\Job\CheckPlayerOnlineStatus;

abstract class AbstractHandler
{
    /**
     * @var Engine;
     */
    public $template;

    /**
     * @var Game
     */
    public $game;

    /**
     * @var Encoder
     */
    public $encoder;

    /**
     * @var FlashMessage
     */
    public $flash;

    /**
     * @var array
     */
    protected $params = [];

    /**
     * @var string
     */
    protected $handler;

    /**
     * @var array view data
     */
    protected $data = [];

    /**
     * @var array
     */
    protected $disablePreHooks = [];

    /**
     * @var array
     */
    protected $disableSessionChecker = [];

    /**
     * @var array
     */
    protected $playerInfo;

    /**
     * @var bool 是否组队
     */
    protected $isParty = false;

    /**
     * @var bool 是否队长
     */
    protected $isLeader = false;

    /**
     * @var bool 是否跟随队员
     */
    protected $isFollower = false;

    /**
     * @var Session
     */
    protected $session;

    /**
     * @var Player
     */
    public $player;

    /**
     * @var ServerRequest
     */
    public $request;

    /**
     * @var Medoo
     */
    public $db;

    /**
     * AbstractHandler constructor.
     * @param Game $game
     * @param array $params
     */
    public function __construct(Game $game, array $params)
    {
        $this->game = $game;
        if (!empty($params)) {
            $this->params = $params;
        }
        $this->session = $this->game->session;
        $this->template = $this->game->template;
        $encoder = $this->encoder = $this->game->encoder;
        $this->flash = $flash = $game->flash;
        $this->request = $this->game->container->get('request');
        $this->db = $this->game->db;
        $this->template->registerFunction('hasMessage', function ($key) use ($flash) {
            return $flash->has($key);
        });
        $this->template->registerFunction('getMessage', function ($key, $default = '') use ($flash) {
            if (!$flash->has($key)) {
                return $default;
            }
            return $flash->get($key);
        });
        // 在模板中加密链接参数
        $this->template->registerFunction('_l', function() use ($encoder) {
            $params = func_get_args();
            if (empty($params)) {
                return '/';
            }
            $link = array_shift($params);
            if (!empty($params)) {
                $link = sprintf($link, ...$params);
            }
            return sprintf('/?cmd=%s', $encoder->encode($link));
        });
        // 获取装备名称
        $this->template->registerFunction('getPlayerEquipName', function($playerEquip) {
            return Helper::getPlayerEquipName($playerEquip);
        });
        // 获取VIP名称
        $this->template->registerFunction('getVipName', function($player) {
            return Helper::getVipName($player);
        });
        // 获取品质颜色
        $this->template->registerFunction('getQualityColor', function($quality) {
            return Helper::getQualityColor($quality);
        });
        $event = $this->game->event;
        // 获取品质颜色
        $this->template->registerFunction('event', function() use($event) {
            return $event;
        });
    }

    /**
     * @param string $handler
     * @throws Exception
     */
    public function run(string $handler): Response
    {
        try {
            $this->handler = $handler;
            // 检查登录信息
            if (!isset($this->disableSessionChecker[$handler])) {
                $this->checkSession();
                $this->checkOnlineStatus();
            }

            if (!isset($this->disablePreHooks[$handler])) {
                $this->preHooks();
                $this->checkPlayerStatus();
            }

            if (!method_exists($this, $handler)) {
                throw new Exception(sprintf('Invalid handler: %s', $handler));
            }

            return call_user_func([$this, $handler]);
        } catch (DisplayException $e) {
            $content = $e->getMessage();
            $factory = new StreamFactory();
            $body = $factory->createStream($content);
            $response = new Response();
            return $response->withBody($body);
        } catch (RedirectException $e) {
            // 跳转移除访问锁
            $this->game->lock->release();
            return new RedirectResponse($e->getMessage());
        }
    }

    /**
     * 检查登录 session
     */
    protected function checkSession()
    {
        if (empty($this->session['uid']) || empty($this->session['user_id'])) {
            throw new RedirectException('/');
        }
    }

    /**
     *  检查角色在线状态
     */
    protected function checkOnlineStatus()
    {
        $player = $this->getPlayerInfo();
        if (!$player['sfzx']) {
            // 出现多个窗口登录时，一个session退出，其他session会进入无限跳转
            // 因此只要不在线，直接清除 session
            $this->session->destroy();
            throw new RedirectException('/');
        }
        $date = date('Y-m-d H:i:s');
        $this->game->db->update('game1', ['endtime' => $date, 'sfzx' => 1], ['id' => $player['id']]);

        // 每1分钟发出一次在线检查任务，5分钟离线
        if (!isset($this->session['check_timestamp'])) {
            $time = 60 * 5;
            $this->game->container->get('resque')->later($time, CheckPlayerOnlineStatus::class, ['uid' => $player['id'], 'time' => $time]);
            $this->session['check_timestamp'] = time();
        }

        if (!empty($player['vip'])) {
            $this->session['vip'] = $player['vip'];
        } else {
            unset($this->session['vip']);
        }
    }

    protected function checkPlayerStatus()
    {
        $player = $this->getPlayerInfo();
        if ($player['hp'] <= 0 && $player['nowguaiwu'] == 0) {
            $mid = $this->game->db->get('mid', '*', ['mid' => $player['nowmid']]);
            $area = $this->game->db->get('qy', '*', ['qyid' => $mid['mqy']]);
            $this->flash->now('tips', '你正处于重伤状态!');
            if ($mid['mid'] != $area['mid']) {
                $this->game->db->update('game1', ['nowmid' => $area['mid']], ['id' => $player['id']]);
            }
        }
        $adminId = $this->game->configs['admin_id'] ?? 0;
        $this->session['is_admin'] = $adminId && (int)$player['id'] === $adminId ? true : false;

        // 删除无效的效果
        $this->game->db->delete('player_effects', [
            'uid' => $player['id'],
            'is_temporary' => 1,
            'end_at[<]' => date('Y-m-d H:i:s')
        ]);

        // 检查人物组队情况
        if ($player['party_id']) {
            $member = $this->game->db->get('player_party_member', '*', [
                'uid' => $player['id'],
                'party_id' => $player['party_id']
            ]);
            if (!empty($member)) {
                $this->isParty = true;
                $this->isLeader = $member['is_leader'];
                $this->isFollower = !$this->isLeader && $member['status'] == 2;
            }
        }
    }

    public function preHooks()
    {
        $cmd = $this->params['cmd'] ?? '';
        $uid = $this->uid() ?? null;
        if (empty($uid) && ($cmd != 'cj' && $cmd != 'cjplayer')) {
            throw new RedirectException('/');
        }

        // 删除被地图被该玩家攻击的怪物
        if ($cmd != 'pve' && $cmd != 'do-pve') {
            // 怪物有效期有十分钟，过期自动删除
            $invalidTime = date('Y-m-d H:i:s', time() - 10 * 60);
            $this->game->db->delete('midguaiwu', ['uid' => $this->uid(), 'active_time[<]' => $invalidTime]);
        }
    }

    protected function getPlayerInfo(): array
    {
        if (!empty($this->playerInfo)) {
            return $this->playerInfo;
        }
        $this->playerInfo = $player = $this->game->db->get('game1', '*', ['id' => $this->uid()]);
        return $player;
    }

    protected function display(string $template, array $params = [])
    {
        $content = $this->template->render($template, array_merge($this->data, $params));
        throw new DisplayException($content);
    }

    /**
     * @param string $url
     */
    protected function redirect(string $url)
    {
        // 跳转时删除访问锁
        $this->game->lock->release();
        throw new RedirectException($url, 302);
    }

    /**
     * @param string $cmd
     */
    protected function doCmd(string $cmd)
    {
        $this->redirect(sprintf('/?cmd=%s', $cmd));
    }

    /**
     * @param string $cmd
     */
    protected function doRawCmd()
    {
        $params = func_get_args();
        if (empty($params)) {
            $this->redirect('/');
        }
        $cmd = array_shift($params);
        $cmd = $this->encode(sprintf($cmd, ...$params));
        $this->redirect("/?cmd=$cmd");
    }

    protected function uid()
    {
        return $this->session['uid'] ?? null;
    }

    /**
     * @return \Medoo\Medoo|mixed|null
     */
    protected function db()
    {
        return $this->game->db;
    }

    /**
     * @return Event
     */
    protected function event(): Event
    {
        return $this->game->event;
    }

    protected function g(string $key)
    {
        return $this->game->$key ?? null;
    }

    protected function lastAction()
    {
        return $this->game->event->lastAction();
    }

    protected function postParam(string $key)
    {
        $post = $this->request->getParsedBody();
        return $post[$key] ?? null;
    }

    /**
     * @param string $string
     * @return string
     */
    protected function encode(string $string)
    {
        return $this->encoder->encode($string);
    }

    /**
     * 释放资源
     */
    public function gc()
    {
        unset(
            $this->game,
            $this->template,
            $this->session,
            $this->encoder,
            $this->flash,
            $this->request,
            $this->db,
            $this->event
        );
    }

    public function __destruct()
    {
        $this->gc();
        unset($this->data);
        unset($this->player);
    }
}