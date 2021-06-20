<?php

namespace Xian\Handlers;

use Xian\AbstractHandler;
use Xian\Helper;
use Xian\Object\Pet;

class Chongwu extends AbstractHandler
{
    public function showChongwu()
    {
        $db = $this->game->db;

        $player = \player\getPlayerById($db, $this->uid(), true);
        $pets = Pet::getAllByUid($this->db(), $this->uid());
        foreach ($pets as $pet) {
            $pet->isOut = $pet->id == $player->cw ? 1 : 0;
        }
        $data = [];
        $data['pets'] = $pets;
        $data['player'] = $player;
        $this->display('chongwu', $data);
    }

    public function chouqu()
    {
        $db = $this->game->db;
        $cost = 20;
        if (\player\changeczb(2, $cost, $this->uid(), $db)) {
            $data = $this->db()->get('system_data', '*', ['level' => 1]);
            $pet = [
                'name' => '灵兽',
                'level' => 1,
                'exp' => 0,
                'max_exp' => $data['player_exp'],
                'uid' => $this->uid(),
                'hp' => $data['player_hp'],
                'maxhp' => $data['player_hp'],
                'wugong' => $data['player_gongji'],
                'fagong' => $data['player_gongji'],
                'wufang' => $data['player_fangyu'],
                'fafang' => $data['player_fangyu'],
                'baqi' => $data['player_baqi'],
                'mingzhong' => $data['player_mingzhong'],
                'shanbi' => $data['player_shanbi'],
                'baoji' => $data['player_baoji'],
                'shenming' => $data['player_shenming'],
                'quality' => rand(0, 5),
            ];
            $petId = Pet::add($this->db(), $pet);
            if (!empty($petId)) {
                $this->flash->success('成功购买一颗灵兽蛋');
            } else {
                $this->flash->error('抽取失败');
            }
        } else {
            $this->flash->error('极品灵石不足');
        }
        $this->doRawCmd("cmd=chongwu");
    }

    public function chuzhan()
    {
        $db = $this->game->db;
        $cwid = $this->params['cwid'] ?? 0;
        \player\changeplayersx('cw', $cwid, $this->uid(), $db);
        $this->flash->success('成功召唤宝宝');
        $this->doRawCmd("cmd=chongwu");
    }

    public function shouhui()
    {
        $db = $this->game->db;
        \player\changeplayersx('cw',0, $this->uid(), $db);
        $this->flash->success('成功召回宝宝');
        $this->doRawCmd("cmd=chongwu");
    }

    public function fangsheng()
    {
        $cwid = $this->params['cwid'] ?? 0;
        $this->db()->delete('player_pet', ['id' => $cwid, 'uid' => $this->uid()]);
        $this->flash->success('成功放生宝宝');
        $this->doRawCmd('cmd=chongwu');
    }

    public function cwInfo()
    {
        $db = $this->game->db;
        $cwid = $this->params['cwid'] ?? 0;
        $pet = Pet::get($this->db(), $cwid);
        $pet->color = $this->getColor($pet->quality);
        $pet->cwpz = $pet->quality * 10;
        $data = [];
        $data['chongwu'] = $pet;
        $this->display('cwinfo', $data);
    }

    public function doFuhua()
    {
        $cwid = $this->params['id'] ?? 0;
        $pet = Pet::get($this->db(), $cwid);
        if ($pet->uid == $this->uid()) {
            Pet::update($this->db(), $cwid, ['is_born' => 1]);
        }
        $this->flash->success('你的灵兽已成功孵化');
        $this->doRawCmd('cmd=cwinfo&cwid=' . $cwid);
    }

    protected function getPinzhi(int $pinzhi)
    {
        $index = $pinzhi;
        $pzarr = array('普通', '优秀', '卓越', '非凡', '完美', '逆天');
        if (!isset($pzarr[$index])) {
            return $pzarr[0];
        }
        return $pzarr[$index];
    }

    protected function getColor(int $pinzhi)
    {
        $index = $pinzhi;
        $colors = ['灰', '绿', '蓝', '紫', '橙', '金' ];
        if (!isset($colors[$index])) {
            return $colors[0];
        }
        return $colors[$index];
    }

}