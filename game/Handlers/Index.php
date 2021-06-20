<?php

namespace Xian\Handlers;

use HansOtt\PSR7Cookies\SetCookie;
use Laminas\Diactoros\Response\RedirectResponse;
use Xian\AbstractHandler;
use Xian\Condition;
use Xian\Helper;

class Index extends AbstractHandler
{
    protected $disablePreHooks = [
        'showLogin' => true,
        'doLogin' => true,
        'showRegister' => true,
        'doRegister' => true,
        'showAbout' => true,
        'showMaintenance' => true,
        'showLocked' => true,
        'test' => true,
    ];

    protected $disableSessionChecker = [
        'showLogin' => true,
        'doLogin' => true,
        'showRegister' => true,
        'doRegister' => true,
        'showAbout' => true,
        'showMaintenance' => true,
        'showLocked' => true,
        'test' => true,
    ];

    public function showLogin()
    {
        $this->display('index', []);
    }

    public function doLogin()
    {
        $username = Helper::filterVar($this->postParam('username') ?? '', 'ALPHA');
        $userpass = $this->postParam('userpass');
        $db = $this->game->db;

        if (strlen($username) < 6 || strlen($userpass) < 6) {
            $this->flash->error('账号或密码错误');
            $this->redirect('/');
        }
        $user = $db->get('userinfo', '*', ['username' => $username]);
        if (empty($user)) {
            $this->flash->error('账号或密码错误');
            $this->redirect('/');
        }
        if (!password_verify($userpass, $user['password'])) {
            $this->flash->error('账号或密码错误');
            $this->redirect('/');
        }
        $currentSid = $this->session->getId();
        // 保存登录用户编号
        $this->session['user_id'] = $user['id'];
        $game1 = $db->get('game1', ['id', 'sid', 'nowmid', 'sfzx'], ['user_id' => $user['id']]);
        if (empty($game1)) {
            $cmd = "cmd=cj&user_id=" . $user['id'];
        } else {
            // 角色已处于在线状态
            if ($game1['sfzx'] && !empty($game1['sid']) && $game1['sid'] != $currentSid) {
                $this->flash->error('帐号已在其他窗口登录，已将其退出登录');
                $this->session->destroySessionById($game1['sid']);
            }
            // 保存登录 session
            $this->session['sid'] = $this->session->getId();
            $this->session['uid'] = $game1['id'];
            $nowdate = date('Y-m-d H:i:s');
            $db->update('game1', [
                'endtime' => $nowdate,
                'sfzx' => 1,
                'sid' => $this->session->getId()
            ], ['id' => $game1['id']]);
            $cmd = sprintf("cmd=gomid");
        }
        $cmd = $this->encode($cmd);
        $this->doCmd($cmd);
    }

    public function logout()
    {
        $this->game->db->update('game1', ['sfzx' => 0], ['id' => $this->uid()]);
        $cookie = SetCookie::thatDeletesCookie($this->session->getName());
        $this->session->destroy();
        $response = $cookie->addToResponse(new RedirectResponse('/'));
        return $response;
    }

    public function showRegister()
    {
        $this->display('register', []);
    }

    public function doRegister()
    {
        $db = $this->game->db;
        $back = $this->encode('cmd=register');
        $username = Helper::filterVar($this->postParam('username') ?? '', 'ALPHA');
        $userpass = $this->postParam('userpass');
        $userpass2 = $this->postParam('userpass2');
        if (strlen($username) < 6 or strlen($userpass) < 6) {
            $this->flash->error('账号或密码长度请大于或等于6位');
            $this->doCmd($back);
        }
        if ($userpass2 != $userpass) {
            $this->flash->error('两次输入密码不一致');
            $this->doCmd($back);
        }
        $existUser = $db->get('userinfo', '*', ['username' => $username]);
        if ($existUser) {
            $this->flash->error('注册失败,账号' . $username . '已经存在');
            $this->doCmd($back);
        }
        $password = password_hash($userpass, PASSWORD_BCRYPT);
        $res = $db->insert('userinfo', ['username' => $username, 'password' => $password]);
        $this->flash->success('注册成功，请登录');
        $this->redirect('/');
    }

    public function showAbout()
    {
        $this->display('about');
    }

    public function showTutorial()
    {
        $this->display('tutorial');
    }

    public function showMaintenance()
    {
        $data = [
            'message' => '系统维护中，请稍后再试...',
        ];
        $this->display('maintenance', $data);
    }

    public function showLocked()
    {
        $data = ['cmd' => http_build_query($this->params)];
        $this->display('locked', $data);
    }

    public function test()
    {
        $t = new Condition($this->db);
        $this->display('about');
    }
}