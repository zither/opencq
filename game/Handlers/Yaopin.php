<?php

namespace Xian\Handlers;

use Xian\AbstractHandler;

class Yaopin extends AbstractHandler
{
    public function info()
    {
        $db = $this->game->db;
        $player = \player\getPlayerById($db, $this->uid(), true);

        $ypid = $this->params['ypid'] ?? 0;
        $gonowmid = $this->encode("cmd=gomid&newmid=$player->nowmid");

        $yaopin = \player\getMedicine($this->db(), $ypid);
        $playeryp = \player\getPlayerMedicineByItemId($this->db(), $ypid, $player->id);
        $data = [];
        $data['useyp'] = $this->encode("cmd=useyp&ypid=$ypid");
        $data['yaopin'] = $yaopin;
        $data['gonowmid'] = $gonowmid;
        $data['has_it'] = $playeryp && $playeryp->amount > 0;
        $data['playerYaopin'] = $playeryp;
        $this->display('ypinfo', $data);
    }

    public function useYaopin()
    {
        $db = $this->game->db;
        $ypid = $this->params['ypid'] ?? 0;
        $back = $this->encode(sprintf('cmd=ypinfo&ypid=%d', $ypid));
        $userypret = \player\useyaopin($ypid,1, $this->uid(), $db);
        if ($userypret){
            $this->flash->set('success', '使用药品成功');
        }else{
            $this->flash->set('error', '使用药品失败');
        }
        $this->doCmd($back);
    }
}