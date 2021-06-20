<?php

namespace Xian\Handlers\Admin;

use Xian\AbstractHandler;
use Xian\Event;
use Xian\Helper;
use Xian\Object\Location;

class Ornament extends AbstractHandler
{
    public function ornamentList()
    {
        $player = \player\getPlayerById($this->db(), $this->uid(), true);
        $location = Location::get($this->db(), $player->nowmid);
        $exists = empty($location->ornaments) ? [] : explode(',', $location->ornaments);
        $map = [];
        foreach ($exists as $v) {
            $v = explode('|', $v);
            $map[$v[0]] = $v[1] ?? 1;
        }

        $arr = $this->db()->select('ornament', '*');
        foreach ($arr as &$v) {
            if (isset($map[$v['id']])) {
                $v['exists'] = true;
            } else {
                $v['exists'] = false;
            }
            $v['amount'] = $map[$v['id']] ?? 0;
        }

        $data = [];
        $data['ornaments'] = $arr;
        $this->display('admin/ornament_list', $data);
    }

    public function setLocation()
    {
        $id = $this->params['id'];
        $player = \player\getPlayerById($this->db(), $this->uid(), true);
        $location = Location::get($this->db(), $player->nowmid);
        $arr = empty($location->ornaments) ? [] : explode(',', $location->ornaments);
        $map = [];
        foreach ($arr as $v) {
            $v = explode('|', $v);
            $ids[] = $v[0];
            $map[$v[0]] = $v[1] ?? 1;
        }
        if (!isset($map[$id])) {
            $arr[] = $id;
            $this->db()->update('mid', ['ornaments' => implode(',', $arr)], ['mid' => $location->id]);
            $this->flash->success("摆件放置成功");
        }
        $this->doRawCmd("cmd=admin-ornament-list");
    }

    public function unsetLocation()
    {
        $id = $this->params['id'];
        $player = \player\getPlayerById($this->db(), $this->uid(), true);
        $location = Location::get($this->db(), $player->nowmid);
        $arr = empty($location->ornaments) ? [] : explode(',', $location->ornaments);
        $map = [];
        foreach ($arr as $v) {
            $v = explode('|', $v);
            $ids[] = $v[0];
            $map[$v[0]] = $v[1] ?? 1;
        }
        if (isset($map[$id])) {
            unset($map[$id]);
            $arr = [];
            foreach ($map as $k => $v) {
                $arr[] = $k;
            }
            $this->db()->update('mid', ['ornaments' => implode(',', $arr)], ['mid' => $location->id]);
            $this->flash->success("摆件移除成功");

        }
        $this->doRawCmd("cmd=admin-ornament-list");
    }

    public function unsetCondition()
    {
        $id = $this->params['id'];
        $this->db()->update('ornament', ['show_condition' => 0], ['id' => $id]);
        $this->flash->success("条件移除成功");
        $this->doRawCmd("cmd=admin-show-ornament&id=$id");
    }

    public function showCreate()
    {
        $data = [];
        $this->display('admin/show_create_monster', $data);
    }

    public function doCreate()
    {
        $name = Helper::filterVar($this->postParam('name'));
        $sex = Helper::filterVar($this->postParam('sex'));
        $muban = Helper::filterVar($this->postParam('muban'));
        $info = Helper::filterVar($this->postParam('info'));
        $exists = $this->db()->get('npc', ['id'], ['name' => $name]);
        if (!empty($exists)) {
            $this->flash->error('名称重复');
            $this->doRawCmd($this->lastAction());
        }
        $this->db()->insert('npc', [
            'name' => $name,
            'sex' => $sex,
            'muban' => $muban ?? '',
            'info' => $info,
            'taskid' => ''
        ]);
        $this->flash->success('创建成功');
        $this->doRawCmd('cmd=admin-npc-list');
    }

    public function showOrnament()
    {
        $id = $this->params['id'] ?? 0;
        if (!$id) {
            $this->flash->error('没有找到相关物件');
            $this->doCmd($this->lastAction());
        }
        $ornamet = $this->db()->get('ornament', '*', ['id' => $id]);
        $operations = [];
        if (!empty($ornamet['operations'])) {
            $opIds = explode(',', $ornamet['operations']);
            $operations = $this->db()->select('operation', '*', ['id' => $opIds]);
            foreach ($operations as $k => &$v) {
                switch ($v['type']) {
                    case 1:
                        $v['cmd'] = sprintf($v['cmd'], $ornamet['id']);
                        break;
                    default:
                        break;
                }
            }
        }
        $condition = null;
        if ($ornamet['show_condition'] > 0) {
            $condition = $this->db()->get('condition', '*', ['id' => $ornamet['show_condition']]);
        }

        //  设置编辑操作的条件
        $this->session['admin_operation_source'] = [
            'from_id' => $ornamet['id'],
            'from_type' => 1,
            'exist' => $ornamet['operations'],
        ];

        $data = [
            'ornament' => $ornamet,
            'operations' => $operations,
            'showCondition'  => $condition,
        ];
        $this->display('admin/ornament', $data);
    }

    public function showEdit()
    {
        $id = $this->params['id'] ?? 0;
        $ornament = $this->db()->get('ornament', '*', ['id' => $id]);
        $conditions = $this->db()->select('condition', ['id','notes']);
        $data = [
            'ornament' => $ornament,
            'conditions' => $conditions
        ];
        $this->display('admin/show_create_ornament', $data);
    }

    public function save()
    {
        $id = Helper::filterVar($this->postParam('id'), 'INT');
        $name = Helper::filterVar($this->postParam('name'));
        $info = Helper::filterVar($this->postParam('info'));
        $condition = Helper::filterVar($this->postParam('show_condition'), 'INT');

        if (empty($id)) {
            $this->db()->insert('ornament', [
                'name' => $name,
                'info' => $info,
                'show_condition' => $condition,
            ]);
            $id = $this->db()->id();
        } else {
            $this->db()->update('ornament', [
                'name' => $name,
                'info' => $info,
                'show_condition' => $condition,
            ], ['id' => $id]);
        }
        $this->flash->success('操作成功');
        $this->doRawCmd("cmd=admin-show-ornament&id=$id");
    }

    public function deleteOrnament()
    {
        $id = $this->params['id'] ?? 0;
        if (!empty($id)) {
            $this->db()->delete('ornament', ['id' => $id]);
            $this->flash->success('删除成功');
        } else {
            $this->flash->error('未找到目标摆件');
        }
        $this->doRawCmd('cmd=admin-ornament-list');

    }
}