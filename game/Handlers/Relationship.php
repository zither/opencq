<?php

namespace Xian\Handlers;

use Xian\AbstractHandler;
use Xian\Event;
use Xian\Helper;

class Relationship extends AbstractHandler
{

    public function showRelationship()
    {
        // 关系类型
        $type = $this->params['type'] ?? 1;
        $players = $this->db()->select('player_relationship', ['[>]game1' => ['tid' => 'id']], [
            'game1.id',
            'game1.name',
        ], [
            'uid' => $this->uid(),
            'type' => $type,
        ]);
        $data = [];
        $data['players'] = $players;
        $data['type'] = $type;
        $this->display('friends', $data);
    }

    /**
     * 添加好友
     */
    public function addRelationship()
    {
        $tid = $this->params['tid'] ?? null;
        $type = $this->params['type'] ?? null;
        if (!$tid || !$type) {
            $this->flash->error('无效参数');
            $this->doRawCmd($this->lastAction());
        }
        $exists = $this->db()->count('player_relationship', [
            'uid' => $this->uid(),
            'tid' => $tid,
            'type' => $type,
        ]);
        if ($exists) {
            $this->flash->error('玩家已在关系列表');
            $this->doRawCmd($this->lastAction());
        }
        $this->db()->insert('player_relationship', [
            'uid' => $this->uid(),
            'tid' => $tid,
            'type' => $type
        ]);

        $this->flash->success('添加成功');
        $this->doRawCmd($this->lastAction());
    }

    /**
     * 移除关系
     */
    public function deleteRelationship()
    {
        $tid = $this->params['tid'] ?? null;
        $type = $this->params['type'] ?? null;
        if (!$tid || !$type) {
            $this->flash->error('无效参数');
            $this->doRawCmd($this->lastAction());
        }
        $this->db()->delete('player_relationship', [
            'uid' => $this->uid(),
            'tid' => $tid,
            'type' => $type
        ]);
        $this->flash->success('删除成功');
        $this->doRawCmd($this->lastAction());
    }
}