<?php
namespace Xian\Handlers;

use Xian\AbstractHandler;
use Xian\Helper;

class CreateRole extends AbstractHandler
{
    /**
     * @var array 关闭登录检查
     */
    protected $disableSessionChecker = [
        'displayCreateView' => true,
        'create' => true,
    ];

    /**
     * @var array 关闭 preHooks
     */
    protected $disablePreHooks = [
        'displayCreateView' => true,
        'create' => true,
    ];

    /**
     * 显示创建页面视图
     * @param $params
     */
    public function displayCreateView()
    {
        $this->checkRole();
        $this->display('cj', ['token' => $this->session['user_id']]);
    }

    /**
     * 检查用户是否已创建角色
     */
    protected function checkRole()
    {
        // 检查登录用户编号
        if (!isset($this->session['user_id'])) {
            $this->redirect('/');
        }
        // 如果已有角色，直接进入游戏
        $game1 = $this->game->db->get('game1', ['id', 'sid', 'nowmid'], ['user_id' => $this->session['user_id']]);
        if (!empty($game1)) {
            $gonowmid = $this->encoder->encode("cmd=gomid&newmid={$game1['nowmid']}");
            $this->doCmd($gonowmid);
        }
    }

    /**
     * 创建角色
     * @throws \Exception
     */
    public function create()
    {
        $this->checkRole();
        $uid = $this->params['token'];
        $sex = Helper::filterVar($this->postParam('sex'), 'INT');
        $back = sprintf('?cmd=%s', $this->encoder->encode("cmd=cj&user_id=$uid"));
        $username = Helper::filterVar($this->postParam('username'), 'STRING');
        if (empty($username) || empty($sex)) {
            $this->redirect($back);
        }
        if (mb_strlen($username) < 2 || mb_strlen($username) > 6) {
            $this->flash->error("名称长度必须在2-6个之间");
            $this->redirect($back);
        }
        if (preg_match('/[^\x{4e00}-\x{9fa5}]/u',$username)) {
            $this->flash->error("角色名称只能使用中文");
            $this->redirect($back);
        }
        $exists = $this->game->db->get('game1', 'name', ['name' => $username]);
        if ($exists) {
            $this->flash->set('error', "用户名存在");
            $this->redirect($back);
        }
        $nowdate = date('Y-m-d H:i:s');
        $gameConfigs = \player\getgameconfig($this->db(), ['initial_mid'], true);
        $firstmid = $gameConfigs['v'];

        // 1级人物属性查表
        $level1 = $this->db()->get('system_data', [
            'player_exp',
            'player_hp',
            'player_baqi',
            'player_gongji',
            'player_fangyu',
            'player_mingzhong',
            'player_shanbi',
            'player_baoji',
            'player_shenming',
        ], ['level' => 1]);

        $ret = $this->game->db->insert('game1', [
            'user_id' => $this->session['user_id'],
            'sid' => $this->session->getId(),
            'name' => $username,
            'level' => 1,
            'uyxb' => 2000,
            'uczb' => 100,
            'exp' => 0,
            'max_exp' => $level1['player_exp'],
            'hp' => $level1['player_hp'],
            'maxhp' => $level1['player_hp'],
            'baqi' => $level1['player_baqi'],
            'wugong' => $level1['player_gongji'],
            'wufang' => $level1['player_fangyu'],
            'fagong' => $level1['player_gongji'],
            'fafang' => $level1['player_fangyu'],
            'shanbi' => $level1['player_shanbi'],
            'mingzhong' => $level1['player_mingzhong'],
            'baoji' => $level1['player_baoji'],
            'shenming' => $level1['player_shenming'],
            'sex' => (int)$sex,
            'vip' => 0,
            'nowmid' => $firstmid,
            'endtime' => $nowdate,
            'sfzx' => 1,
        ]);

        if (!$ret->rowCount()) {
            $this->flash->set('error', "角色创建失败");
            $this->redirect($back);
        }
        // 新建的人物编号
        $uid = $this->game->db->id();

        // 插入默认凡人功法
        $this->game->db->insert('player_manual', [
            'uid' => $uid,
            'manual_id' => 3, // 眨眼剑法
            'manual_level_id' => 18,
            'level' => 1
        ]);
        $manualId = $this->game->db->id();
        // 更新用户当前功法
        $this->game->db->update('game1', ['player_manual_id' => $manualId], ['id' => $uid]);
        // 增加用户基本技能
//        $this->game->db->insert('player_skill', [
//            'uid' => $uid,
//            'skill_id' => 3,
//            'manual_id' => 3,
//            'level' => 1
//        ]);
        // 赠送职业入职
        //$this->game->db->insert('player_item', [
        //    [
        //        'uid' => $uid,
        //        'item_id' => 18,
        //        'sub_item_id' => 0,
        //        'amount' => 1,
        //    ],
        //    [
        //        'uid' => $uid,
        //        'item_id' => 19,
        //        'sub_item_id' => 0,
        //        'amount' => 1,
        //    ],
        //    [
        //        'uid' => $uid,
        //        'item_id' => 20,
        //        'sub_item_id' => 0,
        //        'amount' => 1,
        //    ],
        //]);
        $this->game->db->insert('im', [
            'content' => "万中无一的{$username}加入了游戏!",
            'uid' => 0,
            'tid' => 0,
            'type' => 1
        ]);

        // 保存角色编号
        $this->session->set('uid', $uid);
        $gonowmid = $this->encoder->encode("cmd=gomid");
        $this->doCmd($gonowmid);
    }
}