<?php

namespace Xian\Handlers\Admin;

use Xian\AbstractHandler;
use Xian\Event;
use Xian\Helper;
use function player\getplayer;
use function player\getPlayerById;

class Area extends AbstractHandler
{
    public function areaList()
    {
        $areas = $this->db()->select('qy', [
            'qyid(id)',
            'qyname(name)',
            'mid',
            'teleport',
            'type',
        ]);
        $data = [];
        $data['areas'] = $areas;
        $this->display('admin/area_list', $data);
    }

    public function showCreate()
    {
        $id = $this->params['id'] ?? 0;
        $area = [];
        if ($id) {
            $area = $this->db()->get('qy', [
                'qyid(id)',
                'qyname(name)',
                'mid',
                'teleport',
                'type',
            ], ['qyid' => $id]);
        }
        $data = [];
        $data['area'] = $area;
        $this->display('admin/show_create_area', $data);
    }

    public function save()
    {
        $id = Helper::filterVar($this->postParam('id'), 'INT');
        $create = empty($id);
        $arr = [
            'qyname' => Helper::filterVar($this->postParam('name'), 'STRING'),
            'mid' => Helper::filterVar($this->postParam('mid'), 'INT'),
            'teleport' => Helper::filterVar($this->postParam('teleport'), 'INT'),
            'type' => Helper::filterVar($this->postParam('type'), 'INT'),
        ];
        if (empty($id) && $arr['teleport']) {
            $arr['teleport'] = 0;
        }
        if ($id && $arr['teleport'] == 0) {
            $this->flash->error('必须设置传送点');
            $this->doRawCmd($this->lastAction());
        }
        if (empty($id)) {
            $this->db()->insert('qy', $arr);
            $id = $this->db()->id();
        } else {
            $this->db()->update('qy', $arr, ['qyid' => $id]);
        }
        if ($create) {
            $this->db()->insert('mid', [
                'mname' => $arr['qyname'] .  '_传送点',
                'mqy' => $id,
            ]);
            $teleportId = $this->db()->id();
            $this->db()->update('qy', ['teleport' => $teleportId], ['qyid' => $id]);
        }
        $this->flash->success('操作成功');
        $this->doRawCmd('cmd=admin-area-list');
    }

    public function delete()
    {
        $id = $this->params['id'] ?? 0;
        $this->db()->delete('qy', ['qyid' => $id]);
        $this->flash->success('删除成功');
        $this->doRawCmd('cmd=admin-area-list');
    }
}