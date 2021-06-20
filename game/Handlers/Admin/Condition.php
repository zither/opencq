<?php

namespace Xian\Handlers\Admin;

use Xian\AbstractHandler;
use Xian\Helper;

class Condition extends AbstractHandler
{
    public function conditionList()
    {
        $conditions= $this->db()->select('condition', '*');
        $data = [];
        $data['conditions'] = $conditions;
        $this->display('admin/condition_list', $data);
    }

    public function showCreate()
    {
        $id = $this->params['id'] ?? 0;
        $condition = [];
        if ($id) {
            $condition = $this->db()->get('condition', '*', ['id' => $id]);
        }
        $data = [];
        $data['condition'] = $condition;
        $this->display('admin/show_create_condition', $data);
    }

    public function save()
    {
        $id = Helper::filterVar($this->postParam('id'), 'INT');
        $notes = Helper::filterVar($this->postParam('notes'), 'STRING');
        $successInfo = Helper::filterVar($this->postParam('success_info'), 'STRING');
        $failureInfo = Helper::filterVar($this->postParam('failure_info'), 'STRING');
        $matchers = $this->postParam('matchers');

        $arr = [
            'notes' => $notes,
            'success_info' => $successInfo,
            'failure_info' => $failureInfo,
            'matchers' => $matchers,
        ];
        if (empty($id)) {
            $this->db()->insert('condition', $arr);
            $id = $this->db()->id();
        } else {
            $this->db()->update('condition', $arr, ['id' => $id]);
        }
        $this->flash->success('操作成功');
        $this->doRawCmd('cmd=admin-condition-list');
    }
}