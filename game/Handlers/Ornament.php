<?php
namespace Xian\Handlers;

use Xian\AbstractHandler;
use Xian\Condition;
use Xian\Event;
use Xian\Helper;
use function player\addPlayerStackableItem;
use function player\getgameconfig;
use function player\getItem;
use function player\getplayer;
use function player\getPlayerById;

class Ornament extends AbstractHandler
{
    public function showOrnament()
    {
        $id = $this->params['id'] ?? 0;
        $player = \player\getPlayerById($this->game->db, $this->uid());
        $back = $this->encode(sprintf('cmd=gomid&newmid=%d', $player->nowmid));
        if (!$id) {
            $this->flash->error('没有找到相关物件');
            $this->doCmd($back);
        }
        $ornamet = $this->db()->get('ornament', '*', ['id' => $id]);
        $operations = [];
        if (!empty($ornamet['operations'])) {
            $opIds = explode(',', $ornamet['operations']);
            $operations = $this->db()->select('operation', '*', ['id' => $opIds]);
            foreach ($operations as $k => &$v) {
                if ($v['condition']) {
                    $con = $this->db()->get('condition', '*', ['id' => $v['condition']]);
                    if (!empty($con)) {
                        $checker = new Condition($this->db(), $this->uid());
                        list($r, $info) = $checker->validate($con);
                        if (!$r) {
                            unset($operations[$k]);
                            continue;
                        }
                    }
                }
                switch ($v['type']) {
                    case 1:
                        $v['cmd'] = sprintf($v['cmd'], $ornamet['id']);
                        break;
                    default:
                        break;
                }
            }
        }
        $data = [
            'ornament' => $ornamet,
            'operations' => $operations,
            'gonowmid' => $back
        ];
        $this->display('ornament', $data);
    }

    public function operate()
    {
        $id = $this->params['id'] ?? 0;
        $player = \player\getPlayerById($this->db(), $this->uid(), true);
        $back = $this->lastAction();

        $operation = Helper::operate($this->db(), $id, $player);
        if (empty($operation)) {
            $this->flash->error('无效操作');
            $this->doCmd($back);
        }

        if ($operation['message']) {
            $this->flash->set('tips', $operation['message']);
        }

        if (!empty($operation['cmd'])) {
            $this->doCmd($this->encode($operation['cmd']));
        } else {
            $this->doCmd($back);
        }
    }

    public function mining()
    {
        $hasTool = false;
        $player = getPlayerById($this->db(), $this->uid(), true);
        if ($player->tool1) {
            $playerItem = $this->db()->get('player_item', ['item_id'], ['id' => $player->tool1]);
            $tool = getgameconfig($this->db(), ['mining_tool'], true);
            if ($playerItem['item_id'] == $tool['v']) {
                $hasTool = true;
            }
        }
        if (!$hasTool) {
            $this->flash->error('你没有装备鹤嘴锄，无法挖矿');
            $this->doRawCmd('cmd=gomid');
        }

        $minerals = $this->db()->select('item', [
            'id',
            'name',
            'ui_name',
            'type',
            'extra',
        ], [
            'type' => 1,
            'sub_type' => 3,
            'ORDER' => ['extra' => 'ASC'],
        ]);
        $lucky = false;
        foreach ($minerals as $item) {
            $random = rand(1, 10000);
            if ($random > (int)$item['extra']) {
                continue;
            }
            $lucky = true;
            addPlayerStackableItem($this->db(), $this->uid(), $item, 1);
            $name = $item['ui_name'] ?: $item['name'];
            $this->flash->success("恭喜你挖到了{$name}了!");
            // 每次只能挖到一个矿物
            break;
        }
        if (!$lucky) {
            $this->flash->push('message', '很遗憾，什么都没有挖到!');
        }
        $this->doRawCmd('cmd=gomid');
    }
}