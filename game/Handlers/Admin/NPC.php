<?php

namespace Xian\Handlers\Admin;

use Xian\AbstractHandler;
use Xian\Event;
use Xian\Helper;
use Xian\Object\Location;

class NPC extends AbstractHandler
{
    public function npcList()
    {
        $player = \player\getPlayerById($this->db(), $this->uid(), true);
        $location = Location::get($this->db(), $player->nowmid);
        $exists = empty($location->npcIds) ? [] : explode(',', $location->npcIds);
        $npcArr = $this->db()->select('npc', '*');
        foreach ($npcArr as &$v) {
            if (in_array($v['id'], $exists)) {
                $v['exists'] = true;
            } else {
                $v['exists'] = false;
            }
        }
        $data = [];
        $data['npcs'] = $npcArr;
        $this->display('admin/npc_list', $data);
    }

    public function setLocation()
    {
        $id = $this->params['id'];
        $player = \player\getPlayerById($this->db(), $this->uid(), true);
        $location = Location::get($this->db(), $player->nowmid);
        $npcArr = empty($location->npcIds) ? [] : explode(',', $location->npcIds);
        if (!in_array($id, $npcArr)) {
            $npcArr[] = $id;
            $this->db()->update('mid', ['mnpc' => implode(',', $npcArr)], ['mid' => $location->id]);
            $this->flash->success("NPC 放置成功");
        }
        $this->doRawCmd("cmd=admin-npc-list");
    }

    public function unsetLocation()
    {
        $id = $this->params['id'];
        $player = \player\getPlayerById($this->db(), $this->uid(), true);
        $location = Location::get($this->db(), $player->nowmid);
        $npcArr = empty($location->npcIds) ? [] : explode(',', $location->npcIds);
        if (in_array($id, $npcArr)) {
            $index = array_search($id, $npcArr);
            unset($npcArr[$index]);
            $this->db()->update('mid', ['mnpc' => implode(',', $npcArr)], ['mid' => $location->id]);
            $this->flash->success("NPC 移除成功");
        }
        $this->doRawCmd("cmd=admin-npc-list");
    }

    public function showCreate()
    {
        $id = $this->params['id'] ?? 0;
        $npc = [];
        if ($id) {
            $npc = $this->db()->get('npc', '*', ['id' => $id]);
        }
        $data = ['npc' => $npc];
        $this->display('admin/show_create_npc', $data);
    }

    public function doCreate()
    {
        $id = Helper::filterVar($this->postParam('id'), 'INT');
        $name = Helper::filterVar($this->postParam('name'), 'STRING');
        $sex = Helper::filterVar($this->postParam('sex'), 'STRING');
        $muban = Helper::filterVar($this->postParam('muban'), 'STRING');
        $info = Helper::filterVar($this->postParam('info'), 'STRING');
        $exists = $this->db()->count('npc', ['id' => $id]);
        if ($exists) {
            $this->db()->update('npc', [
                'name' => $name,
                'sex' => $sex,
                'muban' => $muban ?? '',
                'info' => $info,
            ], ['id' => $id]);
        } else {
            $this->db()->insert('npc', [
                'name' => $name,
                'sex' => $sex,
                'muban' => $muban ?? '',
                'info' => $info,
                'taskid' => ''
            ]);
        }
        $this->flash->success('操作成功');
        $this->doRawCmd('cmd=admin-npc-list');
    }

    public function delete()
    {
        $id = $this->params['id'] ?? 0;
        $this->db()->delete('npc', ['id' => $id]);
        $this->flash->success('删除成功');
        $this->doRawCmd('cmd=admin-npc-list');
    }
}