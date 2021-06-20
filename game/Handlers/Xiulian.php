<?php

namespace Xian\Handlers;

use Xian\AbstractHandler;
use Xian\Event;
use Xian\Helper;
use function player\istupo;

class Xiulian extends AbstractHandler
{
    /**
     * @throws \Exception
     */
    public function showXiulian()
    {
        $db = $this->game->db;
        $player = \player\getPlayerById($db, $this->uid(), true);
        $gonowmid = $this->encode("cmd=gomid&newmid=$player->nowmid");
        $nowdate = date('Y-m-d H:i:s');
        $xlsjc = 0;
        $xlexp = 0;
        $xiaohao = 32 * $player->level;
        $jpxiaohao = round(($player->level + 1)/2);

        $pingjing = $this->db()->select('manual_level', '*', [
            'manual_id' => $player->manualId,
            'sequence' => $player->manualSequence,
            'is_max_exp' => 1,
            'level[>=]' => $player->level,
            'ORDER' => ['level' => 'ASC']
        ]);

        $pingjingExp = 0;
        $playerExp = 0;
        if (!empty($pingjing)) {
            $first = $pingjing[0];
            if ($player->level == 1) {
                // 等级为1时特殊处理
                $playerExp = $player->exp;
                $pingjingExp = $this->db()->sum('system_data', ['player_exp'], [
                    'level[<=]' => $first['level']
                ]);
            } else {
                // 玩家等级高于1级
                $currentLevel = $this->db()->get('manual_level', '*', [
                    'manual_id' => $player->manualId,
                    'sequence' => $player->manualSequence,
                    'level[<]' => $player->level,
                    'ORDER' => ['level' => 'DESC']
                ]);
                $compareLevel = $currentLevel['is_min_exp'] ? $currentLevel['level'] : $currentLevel['level'] + 1;
                $pingjingExp = $this->db()->sum('system_data', ['player_exp'], [
                    'level[>=]' => $compareLevel,
                    'level[<=]' => $first['level']
                ]);
                $playerExp = $this->db()->sum('system_data', ['player_exp'], [
                    'level[>=]' => $compareLevel,
                    'level[<]' => $player->level,
                ]);
                $playerExp += $player->exp;
            }
        }

        // 获取匹配玩家等级的挂机经验
        $rate = $db->get('system_data', ['exp_per_min'], ['level' => $player->level]);

        $mid = $this->db()->get('mid', ['mname(name)', 'lingqi'], ['mid' => $player->nowmid]);
        $expRate = $rate['exp_per_min'] * (1 - 0.15 + ($mid['lingqi'] * 60 / 100) / 100);

        if ($player->sfxl == 1){
            $one = strtotime($nowdate) ;
            $tow = strtotime($player->xiuliantime);
            $xlsjc=floor(($one-$tow)/60);
            $maxMinutes = 8 * 60;
            if ($xlsjc > $maxMinutes){
                $xlsjc = $maxMinutes;
            }
            // 计算总经验
            $xlexp = round($xlsjc * $expRate);
        }

        $data = [];
        $data['gonowmid'] = $gonowmid;
        $data['xlsjc'] = $xlsjc;
        $data['xlexp'] = $xlexp;
        $data['xiaohao'] = $xiaohao;
        $data['jpxiaohao'] = $jpxiaohao;
        $data['player'] = $player;
        $data['pingjing'] = $pingjing;
        $data['pingjingExp'] = $pingjingExp;
        $data['playerExp'] = $playerExp;
        $data['expRate'] = $expRate;
        $data['mid'] = $mid;

        $this->display('xiulian', $data);
    }

    /**
     * 开始修炼
     */
    public function startXiulian()
    {
        $db = $this->game->db;
        $player = \player\getPlayerById($db, $this->uid(), true);
        $nowdate = date('Y-m-d H:i:s');
        $back = $this->encode("cmd=goxiulian");

        if ($player->sfxl == 1) {
            $this->flash->set('error', '你已经在 修炼中了');
            $this->doCmd($back);
        }
        \player\changeplayersx('xiuliantime',$nowdate,$this->uid(),$db);
        \player\changeplayersx('sfxl',1,$this->uid(),$db);
        $this->flash->set('success', '开始修炼...');

        $this->game->event->set($player->id, 'cmd=goxiulian', ['endxiulian']);

        $this->doCmd($back);
    }

    /**
     * 结束修炼
     */
    public function endXiulian()
    {
        $db = $this->game->db;
        $player = \player\getPlayerById($db, $this->uid());
        $nowdate = date('Y-m-d H:i:s');
        $back = $this->encode("cmd=goxiulian");

        if ($player->sfxl == 1){
            $one = strtotime($nowdate) ;
            $tow = strtotime($player->xiuliantime);
            $xlsjc=floor(($one-$tow)/60);
            $maxMinutes = 8 * 60;
            if ($xlsjc > $maxMinutes){
                $xlsjc = $maxMinutes;
            }

            // 获取匹配玩家等级的挂机经验
            $rate = $db->get('system_data', ['exp_per_min'], ['level' => $player->level]);
            // 计算总经验
            $xlexp = round($xlsjc * $rate['exp_per_min']);
            \player\changeexp($this->db(), $player->id, $xlexp);
            \player\changeplayersx('sfxl',0, $this->uid(), $db);
            $this->flash->set('success', sprintf('结束修炼...<br/>修炼时间：%d 分钟<br/>获得修为：%d 点<br/>', $xlsjc, $xlexp));

            $this->game->event->remove();
        }else{
            $this->flash->set('error', '你还没有开始修炼...');
        }

        $this->doCmd($back);

    }
}