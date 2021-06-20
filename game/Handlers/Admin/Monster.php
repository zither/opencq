<?php

namespace Xian\Handlers\Admin;

use Xian\AbstractHandler;
use Xian\Event;
use Xian\Helper;
use Xian\Object\Location;

class Monster extends AbstractHandler
{
    public function monsterList()
    {
        $player = \player\getPlayerById($this->db(), $this->uid(), true);
        $location = Location::get($this->db(), $player->nowmid);
        $exists = empty($location->monsterIds) ? [] : explode(',', $location->monsterIds);
        $map = [];
        foreach ($exists as $v) {
            $v = explode('|', $v);
            $map[$v[0]] = $v[1] ?? 1;
        }

        $arr = $this->db()->select('guaiwu', ['id', 'name', 'level']);
        foreach ($arr as &$v) {
            if (isset($map[$v['id']])) {
                $v['exists'] = true;
            } else {
                $v['exists'] = false;
            }
            $v['amount'] = $map[$v['id']] ?? 0;
        }
        $data = [];
        $data['monsters'] = $arr;
        $this->display('admin/monster_list', $data);
    }

    public function setLocation()
    {
        $id = $this->params['id'];
        $player = \player\getPlayerById($this->db(), $this->uid(), true);
        $location = Location::get($this->db(), $player->nowmid);
        $arr = empty($location->monsterIds) ? [] : explode(',', $location->monsterIds);
        $map = [];
        foreach ($arr as $v) {
            $v = explode('|', $v);
            $ids[] = $v[0];
            $map[$v[0]] = $v[1] ?? 1;
        }
        if (!isset($map[$id])) {
            $arr[] = "$id|1";
            $this->db()->update('mid', ['mgid' => implode(',', $arr)], ['mid' => $location->id]);
            $this->flash->success("怪物放置成功，当前怪物数量为1");
        } else {
            $amount = ++$map[$id];
            $newArr = [];
            foreach ($map as $k => $v) {
                $newArr[] = "$k|$v";
            }
            $this->db()->update('mid', ['mgid' => implode(',', $newArr)], ['mid' => $location->id]);
            $this->flash->success("怪物放置成功，当前怪物数量为$amount");
        }
        $this->doRawCmd("cmd=admin-monster-list");
    }

    public function unsetLocation()
    {
        $id = $this->params['id'];
        $player = \player\getPlayerById($this->db(), $this->uid(), true);
        $location = Location::get($this->db(), $player->nowmid);
        $arr = empty($location->monsterIds) ? [] : explode(',', $location->monsterIds);
        $map = [];
        foreach ($arr as $v) {
            $v = explode('|', $v);
            $ids[] = $v[0];
            $map[$v[0]] = $v[1] ?? 1;
        }
        if (isset($map[$id])) {
            $amount = --$map[$id];
            if ($amount <= 0) {
                unset($map[$id]);
            }
            $arr = [];
            foreach ($map as $k => $v) {
                $arr[] = "$k|$v";
            }
            $this->db()->update('mid', ['mgid' => implode(',', $arr)], ['mid' => $location->id]);
            if ($amount <= 0) {
                $this->flash->success("怪物移除成功");
            } else {
                $this->flash->success("怪物数量减少1，当前怪物数量为$amount");
            }
            $this->db()->delete('midguaiwu', ['gid' => $id, 'mid' => $location->id]);

        }
        $this->doRawCmd("cmd=admin-monster-list");
    }

    public function showCreate()
    {
        $id = $this->params['id'] ?? 0;
        $level = $this->params['level'] ?? null;

        $monster = [];
        if ($id) {
            $monster = $this->db()->get('guaiwu', '*', ['id' => $id]);
        }

        if (empty($id) || !empty($level)) {
            if (empty($id) && empty($level)) {
                $level = 1;
            }
            $systemData = $this->db()->get('system_data', '*', ['level' => $level]);
            $monster['level'] = $level;
            $monster['baqi'] = $systemData['monster_baqi'];
            $monster['exp'] = $systemData['monster_exp'];
            $monster['hp'] = $systemData['monster_hp'];
            $monster['wugong'] = $systemData['monster_gongji'];
            $monster['fagong'] = $systemData['monster_gongji'];
            $monster['wufang'] = $systemData['monster_fangyu'];
            $monster['fafang'] = $systemData['monster_fangyu'];
            $monster['mingzhong'] = $systemData['monster_mingzhong'];
            $monster['shanbi'] = $systemData['monster_shanbi'];
            $monster['baoji'] = $systemData['monster_baoji'];
            $monster['shenming'] = $systemData['monster_shenming'];
        }

        $data = ['monster' => $monster];
        $this->display('admin/show_create_monster', $data);
    }

    public function doCreate()
    {
        $id = $this->postParam('id') ?? 0;
        $arr = [
            'name' => $this->postParam('name'),
            'level' => $this->postParam('level'),
            'info' => $this->postParam('info'),
            'sex' => $this->postParam('sex'),
            'gdj' => $this->postParam('gdj'),
            'is_group' => $this->postParam('is_group'),
            'is_aggressive' => $this->postParam('is_aggressive'),
            'type' => $this->postParam('type'),
            'flags' => $this->postParam('flags'),
            'hp' => $this->postParam('hp'),
            'mp' => $this->postParam('mp'),
            'baqi' => $this->postParam('baqi'),
            'wugong' => $this->postParam('wugong'),
            'fagong' => $this->postParam('fagong'),
            'wufang' => $this->postParam('wufang'),
            'fafang' => $this->postParam('fafang'),
            'shanbi' => $this->postParam('shanbi'),
            'mingzhong' => $this->postParam('mingzhong'),
            'baoji' => $this->postParam('baoji'),
            'shenming' => $this->postParam('shenming'),
            'exp' => $this->postParam('exp'),
            'is_private' => $this->postParam('is_private'),
            'max_amount' => $this->postParam('max_amount'),
            'manual_level_id' => $this->postParam('manual_level_id'),
            'skills' => $this->postParam('skills'),
        ];
        if (empty($id)) {
            $this->db()->insert('guaiwu', $arr);
            $id = $this->db()->id();
        } else {
            $this->db()->update('guaiwu', $arr, ['id' => $id]);
        }
        $this->flash->success('操作成功');
        $this->doRawCmd('cmd=admin-monster-list');
    }

    public function delete()
    {
        $id = $this->params['id'] ?? 0;
        $this->db()->delete('guaiwu', ['id' => $id]);
        $this->flash->success('删除成功');
        $this->doRawCmd('cmd=admin-monster-list');
    }
}