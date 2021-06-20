<?php

namespace Xian\Handlers;

use Xian\AbstractHandler;

class Rank extends AbstractHandler
{
    use CommonTrait;

    public function showRanks()
    {
        $this->display('rank');
    }

    public function showLevelRank()
    {
        $db = $this->game->db;
        $player = \player\getPlayerById($db, $this->uid(), true);

        $ret = $db->select('game1', '*', [
            'ORDER' => ['level' => 'DESC', 'exp' => 'ASC'],
            'LIMIT' => 10]
        );
        $gonowmid = $this->encode("cmd=gomid&newmid=$player->nowmid");

        $top = [];
        for ($i = 0; $i < count($ret); $i++) {
            $clubName = '';
            $uname = $ret[$i]['name'];
            $ulv = $ret[$i]['level'];
            $uid = $ret[$i]['id'];
            $vip = $ret[$i]['vip'];
            $clubp = \player\getclubplayer_once($this->db(), $uid);
            if ($clubp) {
                $club = \player\getclub($clubp->clubid, $db);
                $clubName = $club->clubname;
            }
            $top[] = [
                'lv' => $ulv,
                'info_link' => $this->encode("cmd=getplayerinfo&uid=$uid"),
                'club' =>  $clubName ?: '',
                'name' => $uname,
                'vip' => $vip,
            ];
        }
        $data = [];
        $data['top'] = $top;
        $data['gonowmid'] = $gonowmid;
        $this->display('ranks/level', $data);
    }

    public function showFortuneRank()
    {
        $db = $this->game->db;
        $ret = $db->select('game1', ['id', 'name', 'uyxb', 'vip'], [
            'uyxb[>]' => 10000,
            'ORDER' => ['uyxb' => 'DESC', 'id' => 'ASC'],
            'LIMIT' => 10
        ]);

        $top = [];
        for ($i = 0; $i < count($ret); $i++) {
            $clubName = '';
            $uname = $ret[$i]['name'];
            $uid = $ret[$i]['id'];
            $vip = $ret[$i]['vip'];
            $clubp = \player\getclubplayer_once($this->db(), $uid);
            if ($clubp) {
                $club = \player\getclub($clubp->clubid, $db);
                $clubName = $club->clubname;
            }
            $top[] = [
                'info_link' => $this->encode("cmd=getplayerinfo&uid=$uid"),
                'club' =>  $clubName ?: '',
                'name' => $uname,
                'money' => floor($ret[$i]['uyxb'] / 10000),
                'vip' => $vip,
            ];
        }
        $data = [];
        $data['top'] = $top;
        $this->display('ranks/fortune', $data);
    }
}