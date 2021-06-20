<?php

namespace Xian\Handlers\Admin;

use Xian\AbstractHandler;
use Xian\Event;
use Xian\Helper;

class Operation extends AbstractHandler
{
    public function operationList()
    {
        $fromId = $this->params['from_id'] ?? 0;
        $fromType = $this->params['from_type'] ?? 0;
        $exist = $this->params['exist'] ?? '';

        if ($fromId && $fromType) {
            $this->session['admin_operation_source'] = [
                'from_id' => $fromId,
                'from_type' => $fromType,
                'exist' => $exist,
            ];
        }

        $operations = $this->db()->select('operation', '*');
        foreach ($operations as &$v) {
            if (in_array($v['id'], explode(',', $exist))) {
                $v['exists'] = true;
            } else {
                $v['exists'] = false;
            }
        }
        $data = [];
        $data['operations'] = $operations;
        $this->display('admin/operation_list', $data);
    }

    public function setOperation()
    {
        $id = $this->params['id'];
        $source  = $this->session['admin_operation_source'] ?? [];
        if (empty($source)) {
            $this->flash->error('缺少操作来源');
            $this->doRawCmd($this->lastAction());
        }
        if ($source['from_type'] == 1) {
            $ornament = $this->db()->get('ornament', ['operations'], ['id' => $source['from_id']]);
            $operations = explode(',', $ornament['operations']);
            if (!in_array($id, $operations)) {
                $operations[] = $id;
                $operations = array_filter($operations, function($v){
                    return !empty($v);
                });
                $this->db()->update('ornament', [
                    'operations' => implode(',', $operations)
                ], [
                    'id' => $source['from_id']
                ]);
                $this->flash->success('添加成功');
            }
            $this->doRawCmd("cmd=admin-show-ornament&id={$source['from_id']}");
        }
        $this->flash->error('不支持该来源');
        $this->doRawCmd($this->lastAction());
    }

    public function unsetOperation()
    {
        $id = $this->params['id'];
        $source  = $this->session['admin_operation_source'] ?? [];
        if (empty($source)) {
            $this->flash->error('缺少操作来源');
            $this->doRawCmd($this->lastAction());
        }
        if ($source['from_type'] == 1) {
            $ornament = $this->db()->get('ornament', ['operations'], ['id' => $source['from_id']]);
            $operations = explode(',', $ornament['operations']);
            if (in_array($id, $operations)) {
                $index = array_search($id, $operations);
                unset($operations[$index]);
                $this->db()->update('ornament', [
                    'operations' => implode(',', $operations)
                ], [
                    'id' => $source['from_id']
                ]);
                $this->flash->success('移除成功');
            }
            $this->doRawCmd("cmd=admin-show-ornament&id={$source['from_id']}");
        }
        $this->flash->error('不支持该来源');
        $this->doRawCmd($this->lastAction());
    }

    public function redirectToSource()
    {
        $source  = $this->session['admin_operation_source'] ?? [];
        if (empty($source)) {
            $this->flash->error('缺少操作来源');
            $this->doRawCmd($this->lastAction());
        }
        if ($source['from_type'] == 1) {
            $this->doRawCmd("cmd=admin-show-ornament&id={$source['from_id']}");
        }
        $this->flash->error('不支持该来源');
        $this->doRawCmd($this->lastAction());
    }

    public function showCreate()
    {
        $id = $this->params['id'] ?? 0;
        $operation = [];
        if ($id) {
            $operation = $this->db()->get('operation', '*', ['id' => $id]);
        }
        $dungeons = $this->db()->select('qy', ['qyid(id)', 'qyname(name)'], ['type' => 3]);
        $conditions = $this->db()->select('condition', ['id', 'notes']);

        $data = [];
        $data['operation'] = $operation;
        $data['dungeons'] = $dungeons;
        $data['conditions'] = $conditions;
        $this->display('admin/operation', $data);
    }

    public function save()
    {
        $id = Helper::filterVar($this->postParam('id'), 'INT');
        $name = Helper::filterVar($this->postParam('name'), 'STRING');
        $notes = Helper::filterVar($this->postParam('notes'), 'STRING');
        $cmd = $this->postParam('cmd');
        $type = Helper::filterVar($this->postParam('type'), 'INT');
        $condition = Helper::filterVar($this->postParam('condition'), 'INT');
        $message = Helper::filterVar($this->postParam('message'), 'STRING');
        $areaId = Helper::filterVar($this->postParam('area_id'), 'INT');
        $newTasks = Helper::filterVar($this->postParam('new_tasks'), 'STRING');
        $incIdentity = Helper::filterVar($this->postParam('inc_identity'));
        $getItems = Helper::filterVar($this->postParam('get_items'));
        $loseItems = Helper::filterVar($this->postParam('loseItems'));

        $arr = [
            'name' => $name,
            'notes' => $notes,
            'cmd' => $cmd,
            'type' => $type,
            'condition' => $condition,
            'message' => $message,
            'area_id' => $areaId,
            'new_tasks' => $newTasks ?: '',
            'inc_identity' => $incIdentity ?: '',
            'get_items' => $getItems ?: '',
            'lose_items' => $loseItems ?: '',
        ];
        if (empty($id)) {
            $this->db()->insert('operation', $arr);
            $id = $this->db()->id();
        } else {
            $this->db()->update('operation', $arr, ['id' => $id]);
        }
        $this->flash->success('操作成功');
        $this->doRawCmd('cmd=admin-operation-list');
    }
}