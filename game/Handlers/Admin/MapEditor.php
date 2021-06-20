<?php

namespace Xian\Handlers\Admin;

use Xian\AbstractHandler;
use Xian\Event;
use Xian\Helper;
use Xian\Object\Location;

class MapEditor extends AbstractHandler
{
    public function showMid()
    {
        $mid = $this->params['mid'] ?? null;
        $player = \player\getPlayerById($this->db(), $this->uid(), true);
        if (empty($mid)) {
            $mid = $player->nowmid;
        }
        if (!$this->session['is_admin']) {
            $this->doRawCmd('cmd=gomid');
        }

        // 清除操作来源
        unset($this->session['admin_operation_source']);
        
        $location = Location::get($this->db(), $mid);
        $directions = Helper::generateMap($location, 5, 5);
        $x = 2;
        $y = 2;
        foreach ($directions as $m => $dir) {
            foreach ($dir as $n => $loc) {
                if (is_object($loc) || is_string($loc)) {
                    continue;
                }

                if ($m == $y - 2 && $n == $x) {
                    $directions[$m][$n] = 'up';
                    $directions[$y - 1][$n] = '|';
                    continue;
                }
                if ($m == $y + 2 && $n == $x) {
                    $directions[$m][$n] = 'down';
                    $directions[$y + 1][$n] = '|';
                    continue;
                }
                if ($m == $y && $n == $x - 2) {
                    $directions[$m][$n] = 'left';
                    $directions[$m][$x - 1] = '—';
                    continue;
                }
                if ($m == $y && $n == $x + 2) {
                    $directions[$m][$n] = 'right';
                    $directions[$m][$x + 1] = '—';
                    continue;
                }
            }
        }

        $doors = [
            'up' => $location->up,
            'left' => $location->left,
            'right' => $location->right,
            'down' => $location->down,
        ];
        foreach ($doors as $k => &$v) {
            if ($v == 0) {
                $v = [];
                continue;
            }
            foreach ($directions as $m => $dir) {
                foreach ($dir as $n => $loc) {
                    if (is_object($loc) && $loc->id == $v) {
                        $v = $loc;
                        continue 3;
                    }
                }
            }
        }

        $npcIds = explode(',', $location->npcIds);
        $allNpcs = \player\getAllValidNpc($player->id, $location->id, $npcIds, $this->game->db);

        $monsterIds = explode(',', $location->monsterIds);
        $monsters = [];
        if (!empty($monsterIds)) {
            $monsters = $this->db()->select('guaiwu', '*', ['id' => $monsterIds]);
        }

        $ornamentIds = explode(',', $location->ornaments);
        $ornaments = [];
        if (!empty($ornamentIds)) {
            $ornaments = $this->db()->select('ornament', '*', ['id' => $ornamentIds]);
        }

        $this->db()->update('game1', ['nowmid' => $mid], ['id' => $player->id]);
        $data = [
            'directions' => $directions,
            'location' => $location,
            'pvphtml' => $location->ispvp? '[<span class="text-red-600">危险</span>]' : '[<span class="text-green-600">安全</span>]',
            'npcs' => $allNpcs,
            'monsters' => $monsters,
            'ornaments' => $ornaments,
            'doors' => $doors,
        ];
        $this->display('admin/map_editor', $data);
    }

    public function showCreateMid()
    {
        $direction = $this->params['direction'];
        $fromId = $this->params['origin_mid'];

        $data = [];
        $data['origin'] = Location::get($this->db(), $fromId);
        $data['direction'] = $direction;

        $this->display('admin/show_create_loc', $data);
    }

    public function createMid()
    {
        $direction = $this->params['direction'];
        $fromId = $this->params['origin_mid'];
        $name = Helper::filterVar($this->postParam('name'));
        $info = Helper::filterVar($this->postParam('info'));
        if (empty($name)) {
            $this->flash->error('请填写地图名称');
            $this->doRawCmd($this->lastAction());
        }

        $origin = Location::get($this->db, $fromId);
        $arr = [
            'mname' => $name,
            'midinfo' => $info,
            'mqy' => $origin->areaId,
        ];
        switch ($direction) {
            case 'up':
                $arr['mdown'] = $origin->id;
                break;
            case 'down':
                $arr['mup'] = $origin->id;
                break;
            case 'left':
                $arr['mright'] = $origin->id;
                break;
            case 'right':
                $arr['mleft'] = $origin->id;
                break;
        }
        $this->db()->insert('mid', $arr);
        $id = $this->db()->id();
        $column = "m$direction";
        $this->db()->update('mid', [$column => $id], ['mid' => $origin->id]);

        $this->doRawCmd("cmd=show-loc&mid=$fromId");
    }

    public function showUpdateLoc()
    {
        $id = $this->params['id'] ?? 0;
        if (!$id) {
            $this->doRawCmd($this->lastAction());
        }
        $location = Location::get($this->db, $id);
        $data = ['location' =>  $location];
        $this->display('admin/show_update_loc', $data);
    }

    public function updateLoc()
    {
        $id = Helper::filterVar($this->postParam('id'), 'INT');
        $name = Helper::filterVar($this->postParam('name'), '');
        $info = Helper::filterVar($this->postParam('info'), '');
        if (!$id) {
            $this->doRawCmd($this->lastAction());
        }
        $this->db()->update('mid', ['mname' => $name, 'midinfo' => $info], ['mid' => $id]);
        $this->flash->success('修改信息成功');
        $this->doRawCmd(sprintf('cmd=show-loc&mid=%d', $id));
    }

    public function disconnect()
    {
        $dir = $this->params['dir'];
        $dirs = [
            'up' => 'down',
            'left' => 'right',
            'right' => 'left',
            'down' => 'up'
        ];
        if (!in_array($dir, array_keys($dirs))) {
            $this->flash->error('无效参数');
            $this->doRawCmd($this->lastAction());
        }

        $player = \player\getPlayerById($this->db(), $this->uid(), true);
        $location = Location::get($this->db(), $player->nowmid);
        // 未连接，直接返回
        if ($location->$dir == 0) {
            $this->doRawCmd($this->lastAction());
        }
        // 删除当前地点的连接
        $this->db()->update('mid', ["m$dir" => 0], ['mid' => $location->id]);

        // 删除目标地点的连接
        $target = Location::get($this->db(), $location->$dir);
        $targetDir = $dirs[$dir];
        $this->db()->update('mid', ["m$targetDir" => 0], ['mid' => $target->id]);

        $this->doRawCmd($this->lastAction());
    }

    public function showConnections()
    {
        $dir = $this->params['dir'];
        $dirs = [
            'up' => 'down',
            'right' => 'left',
            'left' => 'right',
            'down' => 'up'
        ];
        if (!in_array($dir, array_keys($dirs))) {
            $this->flash->error('无效参数');
            $this->doRawCmd($this->lastAction());
        }
        $player = \player\getPlayerById($this->db(), $this->uid(), true);
        $location = Location::get($this->db(), $player->nowmid);

        $targetDir = $dirs[$dir];
        $locations = $this->db()->select('mid', ['mid(id)', 'mname(name)'], [
            'mqy' => $location->areaId,
            "m$targetDir" => 0,
            'mid[!]' => $location->id,
        ]);
        $data = [
            'dir' => $dir,
            'locations' => $locations,
        ];
        $this->display('admin/show_connections', $data);
    }

    public function connect()
    {
        $dir = $this->params['dir'] ?? '';
        $id = $this->params['id'] ?? 0;
        $dirs = [
            'up' => 'down',
            'right' => 'left',
            'left' => 'right',
            'down' => 'up'
        ];
        if (!in_array($dir, array_keys($dirs)) || empty($id)) {
            $this->flash->error('无效参数');
            $this->doRawCmd($this->lastAction());
        }
        $player = \player\getPlayerById($this->db(), $this->uid(), true);
        $location = Location::get($this->db(), $player->nowmid);
        // 未连接，直接返回
        if ($location->$dir != 0) {
            $this->flash->error('该方向已连接，请断开后重试');
            $this->doRawCmd($this->lastAction());
        }
        // 删除目标地点的连接
        $target = Location::get($this->db(), $id);
        $targetDir = $dirs[$dir];
        if ($target->$targetDir != 0) {
            $this->flash->error('目标地方方向已连接到其他地点，连接失败');
            $this->doRawCmd($this->lastAction());
        }
        // 删除当前地点的连接
        $this->db()->update('mid', ["m$dir" => $id], ['mid' => $location->id]);
        $this->db()->update('mid', ["m$targetDir" => $location->id], ['mid' => $target->id]);

        // 返回
        $this->doRawCmd('cmd=show-loc');
    }

    protected function getDirection($num)
    {
        $dir = [
            '西北','北','东北','西','中','东','西南','南','东南'
        ];
        return $dir[$num];
    }
}