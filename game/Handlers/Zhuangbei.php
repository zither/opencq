<?php

namespace Xian\Handlers;

use Medoo\Medoo;
use player\PlayerEquip;
use Xian\AbstractHandler;
use function player\getgameconfig;
use function player\getItem;
use function player\getPlayerBoundProperty;

class Zhuangbei extends AbstractHandler
{
    use CommonTrait;

    /**
     * @var int[int]
     */
    protected $qianghuaRates = [
        1 => 100,
        2 => 100,
        3 => 100,
        4 => 100,
        5 => 50,
        6 => 50,
        7 => 50,
        8 => 50,
        9 => 30,
        10 => 30,
        11 => 20,
        12 => 20,
    ];

    public function zbInfo()
    {
        $db = $this->game->db;
        $zbnowid = $this->params['zbnowid'] ?? null;

        $data = [];

        $player = \player\getPlayerById($db, $this->uid(), true);
        $gonowmid = $this->encode("cmd=gomid&newmid=$player->nowmid");
        $zhuangbei = \player\getPlayerEquip($this->db(), $zbnowid);

        foreach ($zhuangbei->keywords as &$v) {
            $v['desc'] = $this->keywordDescription($v);
        }

        $arr = [
            $player->tool1,
            $player->tool2,
            $player->tool3,
            $player->tool4,
            $player->tool5,
            $player->tool6,
            $player->tool7,
            $player->tool8,
            $player->tool9,
            $player->tool10,
            $player->tool11,
            $player->tool12,
        ];

        $data['is_mine'] = $zhuangbei->uid == $player->id;
        $data['is_wearable'] = $zhuangbei->uid == $player->id && !in_array($zhuangbei->id, $arr);
        $data['setzbwz'] = $this->encode("cmd=setzbwz&zbwz={$zhuangbei->equipType}&zbnowid=$zhuangbei->id");
        $data['is_wearing'] = $zhuangbei->uid == $player->id && in_array($zhuangbei->id, $arr);
        $data['is_bound'] = $zhuangbei->isBound ?: getPlayerBoundProperty($this->db(), $this->uid());
        $data['tool'] = $this->equipTypes[$zhuangbei->equipType];
        $data['gonowmid'] = $gonowmid;
        $data['zhuangbei'] = $zhuangbei;
        $data['attributes'] = $this->attributes;
        $data['quality_color'] = $this->getQualityColor($zhuangbei->quality);
        $data['manuals'] = [6 => '战士', 7 => '法师', 8 => '道士'];
        $this->display('zbinfo', $data);
    }

    protected function keywordDescription(array $keyword)
    {
        if ($keyword['is_column']) {
            switch ($keyword['column']) {
                case 'baqi':
                    $column = '神力';
                    break;
                case 'wugong':
                    $column = '物攻';
                    break;
                case 'fagong':
                    $column = '法攻';
                    break;
                case 'wufang':
                    $column = '物防';
                    break;
                case 'fafang':
                    $column = '法防';
                    break;
                case 'mingzhong':
                    $column = '命中';
                    break;
                case 'shanbi':
                    $column = '闪避';
                    break;
                case 'baoji':
                    $column = '暴击';
                    break;
                case 'shenming':
                    $column = '抗暴';
                    break;
                case 'hp':
                    $column = '生命值';
                    break;
                case 'max_hp':
                    $column = '生命值上限';
                    break;
            }
        } else {
            if ($keyword['is_wushang']) {
                $column = '物伤';
            } else if ($keyword['is_fashang']) {
                $column = '法伤';
            } else if ($keyword['is_wumian']) {
                $column = '物免';
            } else if ($keyword['is_famian']) {
                $column = '法免';
            } else if ($keyword['is_mingzhong']) {
                $column = '命中率';
            } else if ($keyword['is_shanbi']) {
                $column = '闪避率';
            } else if ($keyword['is_baoji']) {
                $column = '暴击率';
            } else if ($keyword['is_shenming']) {
                $column = '抗暴率';
            }
        }

        if ($keyword['effect_type'] == 1) {
            $type = $keyword['amount'] > 0 ? '+' : '-';
        } else {
            $type = $keyword['amount'] > 0 ? '+' : '-';
        }
        $target = '装备自身';
        if ($keyword['target'] == 2) {
            $target = '人物';
        }

        $amount = abs($keyword['amount']);
        $percent = $keyword['effect_type'] == 1 ? '%': '';

        return "{$target}{$column}：{$type}{$amount}{$percent}";
    }

    public function shengxingList()
    {
        $uid = $this->session['uid'] ?? 0;
        // 获取已装备的装备编号
        $equips = $this->db()->get('game1', $this->playerTools, ['id' => $uid]);
        $ids = [];
        foreach ($equips as $k => $v) {
            if (empty($v)) {
                continue;
            }
            $ids[] = $v;
        }

        $equips = $this->db()->select('player_item', [
            '[>]item' => ['item_id' => 'id'],
            '[>]player_equip_info' => ['sub_item_id' => 'id'],
        ], [
            'player_item.id',
            'player_item.uid',
            'player_item.item_id',
            'player_item.sub_item_id',
            'player_equip_info.name',
            'player_equip_info.ui_name',
            'player_equip_info.level',
            'player_equip_info.equip_type',
            'player_equip_info.shengxing',
            'player_equip_info.qianghua',
            'player_equip_info.quality',
        ], [
            'player_item.uid' => $uid,
            'player_item.storage' => [1, 2],
            'item.type' => 2, // 装备
        ]);
        foreach ($equips as &$v) {
            $v['quality_color'] = $this->getQualityColor($v['quality']);
        }
        $data = [
            'uid' => $uid,
            'equips' => $equips
        ];
        $this->display('shengxing_list', $data);
    }

    public function showShengxingMaterial()
    {
        $id = $this->params['id'] ?? 0;
        if (!$id) {
            $this->flash->error('无效装备编号');
            $this->doRawCmd($this->lastAction());
        }
        $uid = $this->session['uid'];
        $playerEquip = \player\getPlayerEquip($this->db(), $id);
        if ($playerEquip->uid != $uid) {
            $this->flash->error('无效装备');
            $this->doRawCmd($this->lastAction());
        }
        $requiredAmount = $playerEquip->level + $playerEquip->shengxing;
        // 升星石编号
        $configs = getgameconfig($this->db(), ['shengxing_item_1'], true);
        $itemId = $configs['v'];
        $item = getItem($this->db(), $itemId);
        $data = [
            'equip' => $playerEquip,
            'amount' => $requiredAmount,
            'item' => $item,
            'cost' => 10000 * (1 + $playerEquip->shengxing),
        ];
        $this->display('show_shengxing_material', $data);
    }

    public function shengxing()
    {
        $id = $this->params['id'] ?? 0;
        if (!$id) {
            $this->flash->error('无效装备编号');
            $this->doRawCmd($this->event()->lastAction());
        }
        $uid = $this->session['uid'];
        $playerEquip = \player\getPlayerEquip($this->db(), $id);
        if ($playerEquip->uid != $uid) {
            $this->flash->error('无效装备');
            $this->doRawCmd($this->event()->lastAction());
        }
        $originalEquip = \player\getEquip($this->db(), $playerEquip->itemId);
        if (empty($originalEquip->id)) {
            $this->flash->error('该装备无法升星');
            $this->doRawCmd($this->event()->lastAction());
        }
        if ($playerEquip->equipType == 9 || $playerEquip->equipType == 10) {
            $this->flash->error('特殊装备暂时未开放升星');
            $this->doRawCmd($this->event()->lastAction());
        }
        // 获取升星材料编号
        $configs = getgameconfig($this->db(), ['shengxing_item_1'], true);
        $itemId = $configs['v'];
        $requiredAmount = $playerEquip->level + $playerEquip->shengxing;
        // 升星石编号
        $amount = $this->db()->get('player_item', ['id', 'amount'], [
            'uid' => $uid,
            'item_id' =>  $itemId,
            'storage' => 1
        ]);
        if (empty($amount) || $amount['amount'] < $requiredAmount) {
            $this->flash->error('缺少升星材料');
            $this->doRawCmd($this->db()->lastAction());
        }

        $cost = 10000 * (1 + $playerEquip->shengxing);
        $money = $this->db()->get('game1', ['uyxb'], ['id' => $this->uid()]);
        if ($money['uyxb'] < $cost) {
            $this->flash->error('缺少金币，无法升星');
            $this->doRawCmd($this->event()->lastAction());
        }

        $keys = [
            'hp',
            'mp',
            'baqi',
            'wugong',
            'fagong',
            'wufang',
            'fafang',
            'mingzhong',
            'shanbi',
            'baoji',
            'shenming',
        ];
        $updated = [
            'hp[+]' => 0,
            'mp[+]' => 0,
            'baqi[+]' => 0,
            'wugong[+]' => 0,
            'fagong[+]' => 0,
            'wufang[+]' => 0,
            'fafang[+]' => 0,
            'mingzhong[+]' => 0,
            'shanbi[+]' => 0,
            'baoji[+]' => 0,
            'shenming[+]' => 0,
            'shengxing[+]' => 1,
        ];
        foreach ($keys as $k) {
            if ($originalEquip->$k < 1) {
                continue;
            }
            $updated["{$k}[+]"] += ($originalEquip->$k / 100) + rand(1, 10);
        }
        $this->db()->update('player_equip_info', $updated, ['item_id' => $playerEquip->id]);
        $this->db()->update('player_item', ['amount[-]' => $requiredAmount], [
            'uid' => $uid,
            'id' => $amount['id'],
        ]);
        // 扣除金币
        $this->db()->update('game1', ['uyxb[-]' => $cost], ['id' => $this->uid()]);
        $this->flash->success('升星成功');
        $this->doRawCmd(sprintf('cmd=show-shengxing-material&id=%d', $playerEquip->id));
    }

    public function qianghuaList()
    {
        $uid = $this->session['uid'] ?? 0;
        // 获取已装备的装备编号
        $equips = $this->db()->get('game1', $this->playerTools, ['id' => $uid]);
        $ids = [];
        foreach ($equips as $k => $v) {
            if (empty($v)) {
                continue;
            }
            $ids[] = $v;
        }

        $equips = $this->db()->select('player_item', [
            '[>]item' => ['item_id' => 'id'],
            '[>]player_equip_info' => ['sub_item_id' => 'id'],
        ], [
            'player_item.id',
            'player_item.uid',
            'player_item.item_id',
            'player_item.sub_item_id',
            'player_equip_info.name',
            'player_equip_info.ui_name',
            'player_equip_info.level',
            'player_equip_info.equip_type',
            'player_equip_info.shengxing',
            'player_equip_info.qianghua',
            'player_equip_info.quality',
        ], [
            'player_item.uid' => $uid,
            'player_item.storage' => [1, 2],
            'item.type' => 2, // 装备
        ]);
        foreach ($equips as &$v) {
            $v['quality_color'] = $this->getQualityColor($v['quality']);
        }
        $data = [
            'uid' => $uid,
            'equips' => $equips
        ];
        $this->display('qianghua_list', $data);
    }

    public function showQianghuaMaterial()
    {
        $id = $this->params['id'] ?? 0;
        if (!$id) {
            $this->flash->error('无效装备编号');
            $this->doRawCmd($this->game->event->lastAction());
        }
        $uid = $this->session['uid'];
        $playerEquip = \player\getPlayerEquip($this->db(), $id);
        if ($playerEquip->uid != $uid) {
            $this->flash->error('无效装备');
            $this->doRawCmd($this->game->event->lastAction());
        }
        $itemsMap = $this->getQianghuaMinerals($this->db(), $playerEquip);
        $continue = true;
        // 如果未设置强化物品，显示错误信息
        if (empty($itemsMap)) {
            $this->flash->now('error', '当前选择装备无法继续强化');
            $continue = false;
            //$this->doRawCmd(Event::lastAction());
        }

        $items = [];
        if (!empty($itemsMap)) {
            $itemIds = array_keys($itemsMap);
            $items = $this->db()->select('item', ['id', 'name', 'ui_name'], ['id' => $itemIds]);
            foreach ($items as &$v) {
                $v['amount'] = $itemsMap[$v['id']];
                unset($v);
            }
        }
        $playerEquip->qualityColor = $this->getQualityColor($playerEquip->quality);
        $data = [
            'equip' => $playerEquip,
            'items' => $items,
            'continue' => $continue,
            'rate' => $this->qianghuaRates[$playerEquip->qianghua + 1] ?? 0,
            'cost' => 2000,
        ];
        $this->display('show_qianghua_material', $data);
    }

    public function qianghua()
    {
        $id = $this->params['id'] ?? 0;
        if (!$id) {
            $this->flash->error('无效装备编号');
            $this->doRawCmd($this->game->event->lastAction());
        }
        $uid = $this->session['uid'];
        $playerEquip = \player\getPlayerEquip($this->db(), $id);
        if ($playerEquip->uid != $uid) {
            $this->flash->error('无效装备');
            $this->doRawCmd($this->event()->lastAction());
        }
        if ($playerEquip->equipType == 9 || $playerEquip->equipType == 10) {
            $this->flash->error('特殊装备暂时未开放强化');
            $this->doRawCmd($this->event()->lastAction());
        }

        $maxQianghua = $this->getMaxQianghuaLevel($playerEquip);
        // 暂时取消强化等级限制
        if ($playerEquip->qianghua >= min($maxQianghua, 12)) {
            $this->flash->error('装备已达到最大值，无法继续强化');
            $this->doRawCmd($this->event()->lastAction());
        }

        // 检查强化材料编号
        $itemsMap = $this->getQianghuaMinerals($this->db(), $playerEquip);
        $itemIds = array_keys($itemsMap);
        $items = $this->db()->select('player_item', ['id', 'item_id', 'amount'], [
            'uid' => $uid,
            'item_id' =>  $itemIds,
            'storage' => 1
        ]);
        $validItems = [];
        foreach ($items as $v) {
            $validItems[$v['item_id']] = $v;
        }
        foreach ($itemsMap as $k => $v) {
            if (!isset($validItems[$k]) || $validItems[$k]['amount'] < $v) {
                $this->flash->error('缺少强化材料');
                $this->doRawCmd($this->event()->lastAction());
            }
        }
        $cost = 2000;
        $money = $this->db()->get('game1', ['uyxb'], ['id' => $this->uid()]);
        if ($money['uyxb'] < $cost) {
            $this->flash->error('缺少金币，无法强化');
            $this->doRawCmd($this->event()->lastAction());
        }

        // 扣除材料
        foreach ($validItems as $k => $v) {
            $this->db()->update('player_item', ['amount[-]' => $itemsMap[$k]], [
                'uid' => $uid,
                'id' => $v['id'],
            ]);
        }
        // 扣除金币
        $this->db()->update('game1', ['uyxb[-]' => $cost], ['id' => $this->uid()]);
        // 强化成功率
        $rate = $this->qianghuaRates[$playerEquip->qianghua + 1] ?? 0;
        $random = rand(1, 100);
        if ($random <= $rate) {
            // 强化成功
            $this->db()->update('player_equip_info', ['qianghua[+]' => 1], ['item_id' => $playerEquip->id]);
            $this->flash->success('强化成功');
        } else {
            if ($playerEquip->qianghua < 5) {
                // 前4次不掉强化等级
                $isDamaged = false;
            } else if ($playerEquip->qianghua < 9) {
                // 5-9次一半机率掉强化等级
                $isDamaged = rand(1, 100) <= 50;
            } else {
                // 10次及以上必掉强化等级
                $isDamaged = true;
            }
            if ($isDamaged) {
                // 强化失败减少强化次数
                $this->db()->update('player_equip_info', ['qianghua[-]' => 1], [
                    'item_id' => $playerEquip->id,
                    // 防止强化减为负数
                    'qianghua[>]' => 0
                ]);
                $this->flash->error('强化失败，装备强化品质降低');
            } else {
                $this->flash->error('强化失败，装备品质未提升');
            }
        }
        $this->doRawCmd(sprintf('cmd=show-qianghua-material&id=%d', $playerEquip->id));
    }

    protected function getQianghuaMinerals(Medoo $db, PlayerEquip $playerEquip): array
    {
        $itemsMap = [];
        $lvl = 1 + $playerEquip->qianghua;
        $configs = getgameconfig($db, ["qianghua_level_$lvl"], true);
        if (empty($configs) || empty($configs['v'])) {
            return $itemsMap;
        }
        $arr = explode(',', $configs['v']);
        foreach ($arr as $v) {
            if (strpos($v, '|') === false) {
                continue;
            }
            list($itemId, $amount) = explode('|', $v);
            $itemsMap[$itemId] = $amount;
        }
        return $itemsMap;
    }

    /**
     * 计算装备最大强化次数
     *
     * @param PlayerEquip $playerEquip
     * @return false|float
     */
    protected function getMaxQianghuaLevel(PlayerEquip $playerEquip)
    {
        return ceil($playerEquip->level / 10) + floor($playerEquip->quality);
    }
}