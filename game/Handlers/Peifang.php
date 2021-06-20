<?php
namespace Xian\Handlers;

use Xian\Object\Location;
use function player\addPlayerEquip;
use function player\addPlayerStackableItem;
use player\Player;
use Xian\AbstractHandler;

class Peifang extends AbstractHandler
{
    public function peifangInfo()
    {
        $pfid = $this->params['pfid'];
        $make = $this->params['make'] ?? null;
        $encode = $this->game->encoder;
        $db = $this->game->db;
        $player = \player\getPlayerById($db, $this->uid(), true);

        // 返回游戏链接
        $peifang = $db->get('peifang', '*', ['id' => $pfid]);
        $ingredientsArray = explode(',', $peifang['ingredients']);
        $ingredientIds = [];
        foreach ($ingredientsArray as $v) {
            list($id, $num) = explode('|', $v);
            $ingredientIds[$id] = (int)$num;
        }
        // 制作物品
        if (isset($make) && $make) {
            $this->make($player, $ingredientIds, $peifang);
        }

        $data['ingredients'] = $db->select('item', '*', ['id' => array_keys($ingredientIds)]);
        $data['makeCmd'] = $encode->encode("cmd=pfinfo&pfid=$pfid&make=1");
        $data['peifang'] = $peifang;
        $data['ingredientIds'] = $ingredientIds;
        $data['target'] = $this->target($peifang['type'], $peifang['product']);
        $data['player'] = $player;
        $data['playerPfInfo'] = $db->get('player_peifang', '*', [
            'uid' => $player->id,
            'peifang_id' => $peifang['id']
        ]);
        $this->display('pfinfo', $data);
    }

    /**
     * 获取配方炼制物品信息
     * @param $type
     * @param $id
     * @return array
     */
    protected function target($type, $id): array
    {
        $db = $this->game->db;
        switch ($type) {
            case 1:
                $t = \player\getEquip($this->db(), $id);
                return [
                    'name' => $t->name,
                    'class' => 'zbys',
                ];
            case 2:
                $t = \player\getItem($this->db(), $id);
                if (!$t || !$t->id) {
                    break;
                }
                return [
                    'name' => $t->name,
                    'class' => sprintf('quality-%d', $t->quality),
                ];
            case 3:
                $t = \player\getMedicine($this->db(), $id);
                if (!$t->id) {
                    break;
                }
                return [
                    'name' => $t->name,
                    'class' => 'ypys'
                ];

        }
        return [];
    }

    protected function make(Player $player, array $ingredientIds, array $peifang)
    {
        $db = $this->game->db;
        $back = $this->encoder->encode("cmd=pfinfo&pfid={$this->params['pfid']}");

        // 统一变量
        $creation = [];
        if ($peifang['type'] == 3) {
            $yao = \player\getMedicine($this->db(),$peifang['product']);
            $creation['name'] = $yao->name;
        } else if ($peifang['type'] == 1) {
            $yao = \player\getEquip($this->db(),$peifang['product']);
            $creation['name'] = $yao->name;
        } else if ($peifang['type'] == 2) {
            $daoju = \player\getItem($this->db(),$peifang['product']);
            if ($daoju !== false) {
                $creation['name'] = $daoju->name;
            }
        }
        $playerIngredients = $db->select('player_item', '*', [
            'uid' => $player->id,
            'item_id' => array_keys($ingredientIds)
        ]);
        $owned = [];
        foreach ($playerIngredients as $v) {
            $owned[$v['item_id']] = $v['amount'];
        }

        foreach ($ingredientIds as $k => $v) {
            if (!isset($owned[$k]) || $owned[$k] < $v) {
                $this->flash->set('message', '缺少配方材料，制作失败');
                $this->doCmd($back);
            }
        }

        $db->pdo->beginTransaction();
        foreach ($ingredientIds as $k => $v) {
            $res = \player\deledjsum($this->db(), $k, $v, $player->id);
            if ($res === false) {
                $db->pdo->rollBack();
                $this->flash->set('messsage', '扣除材料失败，请重新制作');
                $this->doCmd($back);
            }
        }

        if ($peifang['type'] == 1) {
            $loc = Location::get($this->db(), $player->nowmid);
            $source = [
                'location' => $loc->name,
                'monster' => '配方制造',
                'player' => $player->name,
            ];
            addPlayerEquip($this->db(), $player->id, ['id' => $peifang['product']], $source);
        } else {
            addPlayerStackableItem($this->db(), $player->id, ['id' => $peifang['product']], 1);
        }

        \player\uppeifangrate($player->id, $peifang['id'], $peifang['up_rate'], $db);
        $res = $db->pdo->commit();
        if (!$res) {
            $this->flash->set('message', sprintf('制作 %s 失败，请稍后再试', $creation['name']));
            $this->doCmd($back);
        }

        $this->flash->set('message', sprintf('制作成功，获得 %s x1', $creation['name']));
        $this->doCmd($back);
    }
}