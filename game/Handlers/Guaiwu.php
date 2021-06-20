<?php

namespace Xian\Handlers;

use Xian\AbstractHandler;
use Xian\Helper;
use Xian\Layer;

class Guaiwu extends AbstractHandler
{
    use CommonTrait;

    public function gInfo()
    {
        $db = $this->game->db;
        $player = \player\getPlayerById($db, $this->uid(), true);
        $nowmid = $this->params['nowmid'] ?? 0;
        $gid = $this->params['gid'] ?? 0;
        $gyid = $this->params['gyid'] ?? 0;
        $data = [];
        $guaiwu = \player\getMidGuaiwu($this->db(), $gid);
        $yguaiwu = \player\getGuaiwu($gyid,$db);
        $level = [];
        if ($yguaiwu->manualLevelId > 0) {
            $manualLevel = $db->get('manual_level', '*', ['id' => $yguaiwu->manualLevelId]);
            $level['jingjie'] = Layer::name($manualLevel['layer']);
        }

        $pvecmd = $this->encode("cmd=do-pve&gid=$gid&begin=1&nowmid=$nowmid");

        $area = $this->getAreaInfoByMid($guaiwu->mid);
        $daojuArr = $this->db()->select('loot', '*', [
            'OR' => [
                'loot.monster_id' => $yguaiwu->id,
                'AND' => [
                    'loot.area_id' => $area['area_id'],
                    'loot.monster_id' => 0,
                ]
            ],
            'ORDER' => ['loot.monster_id' => 'DESC'],
        ]);

        $daoju = [];
        foreach ($daojuArr as $v) {
            $id = $v['item_id'];
            if (!isset($daoju[$id])) {
                $daoju[$id] = $v;
            }
        }

        $data['daoju'] = array_values($daoju);
        $data['pvecmd'] = $pvecmd;
        $data['yguaiwu'] = $yguaiwu;
        $data['guaiwu'] = $guaiwu;
        $data['levelInfo'] = $level;
        $data['canBattle'] = $nowmid == $player->nowmid;

        $this->display('ginfo', $data);
    }
}