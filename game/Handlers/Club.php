<?php

namespace Xian\Handlers;

use Xian\AbstractHandler;
use Xian\Helper;

class Club extends AbstractHandler
{
    public function showList()
    {
        $db = $this->game->db;
        $player = \player\getPlayerById($db, $this->uid(), true);

        $data = [];
        $data['gonowmid'] = $this->encode("cmd=gomid&newmid=$player->nowmid");
        $allclub = \player\getclub_all($db);
        foreach ($allclub as &$v) {
            $v['info_link'] = $this->encode("cmd=club&clubid={$v['clubid']}");
        }
        $data['clubs'] = $allclub;

        $this->display('club_list', $data);
    }

    public function showClub()
    {
        $db = $this->game->db;
        $canshu = $this->params['canshu'] ?? null;
        $zhiwei = $this->params['zhiwei'] ?? null;
        $clubid = $this->params['clubid'] ?? null;

        $player = \player\getPlayerById($db, $this->uid(), true);
        $clubplayer = \player\getclubplayer_once($this->db(), $player->id);
        $gonowmid = $this->encode("cmd=gomid&newmid=$player->nowmid");
        $clublist = $this->encode("cmd=clublist");

        $data = [];
        if (is_null($clubid) && $clubplayer) {
            $clubid = $clubplayer->clubid;
            $club = \player\getclub($clubid, $db);
        }

        if (isset($canshu)){
            switch ($canshu){
                case "renzhi":
                    if ($clubplayer){
                        if (isset($zhiwei)){
                            $renzhiPlayers = $db->select('clubplayer', ['uid'], [
                                'clubid' => $clubplayer->clubid,
                                'uclv[>]' => $clubplayer->uclv
                            ]);
                            foreach ($renzhiPlayers as $k => &$uiditem){
                                $uid = $uiditem['uid'];
                                if ($uid == $player->id){
                                    unset($renzhiPlayers[$k]);
                                    continue;
                                }
                                $otherplayer = \player\getplayer1($uid, $db);
                                $uiditem['info_link'] = $this->encode("cmd=zhiwei-club&zhiwei=$zhiwei&uid=$uid");
                                $uiditem['name'] = $otherplayer->name;

                            }
                            $data['renzhi_players'] = array_values($renzhiPlayers);
                            goto display;
                        }

                        $jobs = [
                            '副掌门' => 2,
                            '长老' => 3,
                            '执事' => 4,
                            '精英' => 5,
                            '弟子' => 6,
                        ];
                        $caozuo = [];
                        foreach ($jobs as $k => $v) {
                            if ($clubplayer->uclv >= $v) {
                                continue;
                            }
                            $caozuo[] = [
                                'title' => $k,
                                'cmd' => $this->encode("cmd=club&canshu=renzhi&zhiwei=$v")
                            ];
                        }
                        $data['caozuo'] = $caozuo;
                    }
                    break;
            }
        }

        if (isset($clubid) || $clubplayer) {
            if ($clubplayer) {
                $data['has_club'] = true;
                if (isset($clubid)) {
                    if ($clubplayer->clubid != $clubid) {
                        goto noclub;
                    }
                } else {
                    $clubid = $clubplayer->clubid;
                }
                $data['outclubcmd'] = $this->encode("cmd=out-club");
                if ($clubplayer->uclv == 1) {
                    $data['outclubcmd'] = $this->encode("cmd=delete-club");
                    $data['renzhicmd'] = $this->encode("cmd=club&canshu=renzhi");
                }
            } else {
                $data['joincmd'] = $this->encode("cmd=join-club&clubid=$clubid");
            }
            noclub:
            $club = \player\getclub($clubid, $db);
            $data['cboss'] = \player\getplayer1($club->clubno1, $db);
            $data['cbosscmd'] = $this->encode("cmd=getplayerinfo&uid=$club->clubno1");


            $members = $db->select('clubplayer', ['uid', 'uclv'], [
                'clubid' => $clubid,
                'ORDER' => ['uclv' => 'ASC']
            ]);
            foreach ($members  as &$v) {
                switch ($v['uclv']) {
                    case 1:
                        $chenhao = "掌门";
                        break;
                    case 2:
                        $chenhao = "副掌门";
                        break;
                    case 3:
                        $chenhao = "长老";
                        break;
                    case 4:
                        $chenhao = "执事";
                        break;
                    case 5:
                        $chenhao = "精英";
                        break;
                    default:
                        $chenhao = "弟子";
                }
                $v['chenghao'] = $chenhao;
                $otherplayer = \player\getplayer1($v['uid'], $db);
                $v['info_link'] = $this->encode("cmd=getplayerinfo&uid={$v['uid']}");
                $v['name'] = $otherplayer->name;
            }
            $data['members'] = $members;
        }

        display:

        $data['is_member'] = $clubplayer && $clubplayer->clubid == $club->clubid;
        $data['club'] = $club ?? null;
        $data['clubplayer'] = $clubplayer;
        $data['gonowmid'] = $gonowmid;
        $data['clublist'] = $clublist;

        $this->display('club', $data);
    }

    public function join()
    {
        $db = $this->game->db;
        $player = \player\getPlayerById($db, $this->uid(), true);
        $gonowmid = $this->encode("cmd=gomid&newmid=$player->nowmid");

        $clubid = $this->params['clubid'] ?? null;
        $clubplayer = \player\getclubplayer_once($this->db(), $player->id);

        $club = \player\getclub($clubid, $db);
        if (!$club->clubid) {
            $this->doCmd($gonowmid);
        }

        $back = $this->encode(sprintf('cmd=club&clubid=%d', $clubid));
        if ($clubplayer) {
            $this->flash->set('error', '你已经有门派了');
            $this->doCmd($back);
        }
        $db->insert('clubplayer', [
            'clubid' => $clubid,
            'uid' => $player->id,
            'sid' => $player->sid,
            'uclv' => 6
        ]);
        $this->flash->set('success', '恭喜你成功加入');
        $this->doCmd($back);
    }

    public function out()
    {
        $db = $this->game->db;

        $clubplayer = \player\getclubplayer_once($this->db(), $this->uid());

        $back = $this->encode(sprintf('cmd=club&clubid=%d', $clubplayer->clubid));

        if ($clubplayer) {
            $db->delete('clubplayer', ['uid' => $this->uid()]);
        }

        $this->flash->set('success', '退出成功');
        $this->doCmd($back);
    }

    public function delete()
    {
        $db = $this->game->db;
        $player = \player\getPlayerById($db, $this->uid(), true);

        $clubplayer = \player\getclubplayer_once($this->db(), $this->uid());
        $back = $this->encode("cmd=gomid&newmid=$player->nowmid");

        if ($clubplayer) {
            if ($clubplayer->uclv == 1){
                $db->delete('clubplayer', ['clubid' => $clubplayer->clubid]);
                $db->delete('club', ['clubid' => $clubplayer->clubid]);
                $this->flash->set('success', '门派解散成功');
            }
        }
        $this->doCmd($back);
    }

    public function setZhiwei()
    {
        $db = $this->game->db;
        $zhiwei = $this->params['zhiwei'] ?? null;
        $uid = $this->params['uid'] ?? null;
        $clubplayer = \player\getclubplayer_once($this->db(), $this->uid());
        $back = $this->encode(sprintf('cmd=club&clubid=%d', $clubplayer->clubid));
        if ($clubplayer && $zhiwei && $uid) {
            $db->update('clubplayer', ['uclv' => $zhiwei], ['uid' => $uid, 'clubid' => $clubplayer->clubid]);
            $this->flash->set('success', '任职成功');
        }
        $this->doCmd($back);
    }
}