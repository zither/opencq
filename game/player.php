<?php
namespace player;

use Medoo\Medoo;
use Xian\Helper;
use Xian\Layer;
use Xian\Object\Location;
use Xian\Object\Pet;

trait FromArray
{
    public static function fromArray(array $m): self
    {
        $mid = new static();
        $formattedColumns = [];
        foreach ($m  as $k => $v) {
            $formattedColumns[Helper::littleCamelCase($k)] = $v;
        }
        $vars = (new \ReflectionObject($mid))->getProperties(\ReflectionProperty::IS_PUBLIC);
        foreach ($vars as $v) {
            $key = $v->name;
            if (isset($formattedColumns[$key])) {
                $mid->$key = $formattedColumns[$key];
            }
        }
        return $mid;
    }
}

class Player
{
    public $id;
    public $name;//昵称
    public $sid;//sid
    public $level;//等级
    public $uyxb;//游戏币
    public $uczb;//充值币
    public $exp;//经验
    public $maxExp;//经验上限
    public $sex;//性别
    public $vip;//vip
    public $nowmid;//当前地图
    public $endtime;

    /**
     * @var int 武器装备编号
     */
    public $tool1;

    /**
     * @var int 衣服
     */
    public $tool2;

    /**
     * @var int 头盔
     */
    public $tool3;

    /**
     * @var int 项链
     */
    public $tool4;

    /**
     * @var int 手镯(左)
     */
    public $tool5;

    /**
     * @var int 手镯(右)
     */
    public $tool6;
    /**
     * @var int 戒指(左)
     */
    public $tool7;

    /**
     * @var int 戒指(右)
     */
    public $tool8;

    /**
     * @var int 鞋子
     */
    public $tool9;

    /**
     * @var int 腰带
     */
    public $tool10;

    /**
     * @var int 宝石
     */
    public $tool11;

    /**
     * @var int 勋章
     */
    public $tool12;

    public $jingjie;
    public $sfxl;
    public $sfzx;
    public $xiuliantime;
    public $yp1;
    public $yp2;
    public $yp3;
    public $cw;
    public $jn1;
    public $jn2;
    public $jn3;
    public $ispvp;
    public $cengci;

    // 受伤
    public $hurt = false;
    // 战斗前的血量
    public $originHp = 0;

    /** 人物属性 */

    // 最大血量
    public $maxhp;
    // 最大蓝量
    public $maxmp;

    /** 战斗属性 */
    // 气血
    public $hp;
    // 灵气
    public $mp;
    // 霸气
    public $baqi;
    // 淬体
    public $wufang;
    // 破体
    public $wugong;
    // 固魂
    public $fafang;
    // 伤魂
    public $fagong;
    // 身法
    public $shanbi;
    // 命中
    public $mingzhong;
    // 会心
    public $baoji;
    // 神明
    public $shenming;

    // 境界编号
    public $layerId;
    // 境界名称
    public $layerName;
    // 功法编号
    public $manualId;
    // 功法等级
    public $manualLevelId;
    // 角色当前功法编号
    public $playerManualId;

    public $playerManualName;
    // 功法境界序号
    public $manualSequence;
    // 是否达到功法最大等级
    public $isMaxManualLevel;
    // 经验池
    public $expPool;

    /**
     * @var Pet
     */
    public $pet;

    /**
     * @var int 负重上限
     */
    public $liftingCapacity;

    /**
     * @var int 队伍编号
     */
    public $partyId;

    public $qq;

    public $vipEndedAt;

    public $masterId;

    use FromArray;
}

function getPlayerById(Medoo $db, int $id, $withoutDetails = false): Player
{
    $columns = $db->get('game1', '*', ['id' => $id]);
    $player = Player::fromArray($columns);

    if ($withoutDetails) {
        return $player;
    }

    $equipIds = [];
    for ($i = 1; $i <= 12; $i++) {
        $type = sprintf('tool%d', $i);
        if ($player->$type != 0) {
            $equipIds[] = $player->$type;
        }
    }
    $equipIds = array_filter($equipIds, function ($v) {
        return $v > 0;
    });
    $equips = getPlayerEquipsByIds($db, $equipIds);

    // 人物属性乘修
    $playerMultiply = [
        'hp' => 0,
        'mp' => 0,
        'baqi' => 0,
        'wugong' => 0,
        'wufang' => 0,
        'fagong' => 0,
        'fafang' => 0,
        'shanbi' => 0,
        'mingzhong' => 0,
        'baoji' => 0,
        'shenming' => 0,
    ];
    // 人物属性加修
    $playerAdd = [
        'hp' => 0,
        'mp' => 0,
        'baqi' => 0,
        'wugong' => 0,
        'wufang' => 0,
        'fagong' => 0,
        'fafang' => 0,
        'shanbi' => 0,
        'mingzhong' => 0,
        'baoji' => 0,
        'shenming' => 0,
    ];

    foreach ($equips as $v) {
        // 装备自身属性乘修
        $equipMultiply = [
            'hp' => 0,
            'mp' => 0,
            'baqi' => 0,
            'wugong' => 0,
            'wufang' => 0,
            'fagong' => 0,
            'fafang' => 0,
            'shanbi' => 0,
            'mingzhong' => 0,
            'baoji' => 0,
            'shenming' => 0,
        ];
        // 装备自身属性加修
        $equipAdd = [
            'hp' => 0,
            'mp' => 0,
            'baqi' => 0,
            'wugong' => 0,
            'wufang' => 0,
            'fagong' => 0,
            'fafang' => 0,
            'shanbi' => 0,
            'mingzhong' => 0,
            'baoji' => 0,
            'shenming' => 0,
        ];
        foreach ($v->keywords as $keyword) {
            if (!$keyword['is_column']) {
                continue;
            }
            // 修正字段
            $column = $keyword['column'];
            // 跳过无效字段
            if (!in_array($column, array_keys($playerAdd))) {
                continue;
            }

            // 装备自身属性修正
            if ($keyword['target'] == 1) {
                if ($keyword['effect_type'] == 1) {
                    // 装备自身属性乘法修正
                    $equipMultiply[$column] += $keyword['amount'];
                } else {
                    // 装备自身属性加法修正
                    $equipAdd[$column] += $keyword['amount'];
                }
            } else {
                if ($keyword['effect_type'] == 1) {
                    // 人物属性乘法修正
                    $playerMultiply[$column] += $keyword['amount'];
                } else {
                    // 人物属性加法修正
                    $playerAdd[$column] += $keyword['amount'];
                }
            }
        }
        // 装备属性修正，先乘后加，方便调整
        $player->maxhp += ($v->hp + $v->qualityHp) * (1 + 0.1 * $v->qianghua) * (1 + $equipMultiply['hp'] / 100) + $equipAdd['hp'];
        $player->maxmp += ($v->mp + $v->qualityMp) * (1 + 0.1 * $v->qianghua) * (1 + $equipMultiply['mp'] / 100) + $equipAdd['mp'];
        $player->baqi+= ($v->baqi + $v->qualityBaqi) * (1 + 0.1 * $v->qianghua) * (1 + $equipMultiply['baqi'] / 100) + $equipAdd['baqi'];
        $player->wugong += ($v->wugong + $v->qualityWugong) * (1 + 0.1 * $v->qianghua) * (1 + $equipMultiply['wugong'] / 100) + $equipAdd['wugong'];
        $player->fagong += ($v->fagong + $v->qualityFagong) * (1 + 0.1 * $v->qianghua) * (1 + $equipMultiply['fagong'] / 100) + $equipAdd['fagong'];
        $player->wufang += ($v->wufang + $v->qualityWufang) * (1 + 0.1 * $v->qianghua) * (1 + $equipMultiply['wufang'] / 100) + $equipAdd['wufang'];
        $player->fafang += ($v->fafang + $v->qualityFagong) * (1 + 0.1 * $v->qianghua) * (1 + $equipMultiply['fafang'] / 100) + $equipAdd['fafang'];
        $player->shanbi+= ($v->shanbi + $v->qualityShanbi) * (1 + 0.1 * $v->qianghua) * (1 + $equipMultiply['shanbi'] / 100) + $equipAdd['shanbi'];
        $player->mingzhong += ($v->mingzhong + $v->qualityMingzhong) * (1 + 0.1 * $v->qianghua) * (1 + $equipMultiply['mingzhong'] / 100) + $equipAdd['mingzhong'];
        $player->baoji += ($v->baoji + $v->qualityBaoji) * (1 + 0.1 * $v->qianghua) * (1 + $equipMultiply['baoji'] / 100) + $equipAdd['baoji'];
        $player->shenming += ($v->shenming + $v->qualityShenming) * (1 + 0.1 * $v->qianghua) * (1 + $equipMultiply['shenming'] / 100) + $equipAdd['shenming'];
    }
    // 人物属性修正
    $player->maxhp = floor($player->maxhp * (1 + $playerMultiply['hp'] / 100) + $playerAdd['hp']);
    $player->maxmp = floor($player->maxmp * (1 + $playerMultiply['mp'] / 100) + $playerAdd['mp']);
    $player->baqi = floor($player->baqi * (1 + $playerMultiply['baqi'] / 100) + $playerAdd['baqi']);
    $player->wugong = floor($player->wugong * (1 + $playerMultiply['wugong'] / 100) + $playerAdd['wugong']);
    $player->fagong = floor($player->fagong * (1 + $playerMultiply['fagong'] / 100) + $playerAdd['fagong']);
    $player->wufang = floor($player->wufang * (1 + $playerMultiply['wufang'] / 100) + $playerAdd['wufang']);
    $player->fafang = floor($player->fafang * (1 + $playerMultiply['fafang'] / 100) + $playerAdd['fafang']);
    $player->shanbi = floor($player->shanbi * (1 + $playerMultiply['shanbi'] / 100) + $playerAdd['shanbi']);
    $player->mingzhong = floor($player->mingzhong * (1 + $playerMultiply['mingzhong'] / 100) + $playerAdd['mingzhong']);
    $player->baoji = floor($player->baoji * (1 + $playerMultiply['baoji'] / 100) + $playerAdd['baoji']);
    $player->shenming = floor($player->shenming * (1 + $playerMultiply['shenming'] / 100) + $playerAdd['shenming']);

    if (empty($player->playerManualId)) {
        // 设置默认功法
        $player->playerManualId = 16;
    }

    $manual = $db->get('player_manual', [
        '[>]manual' => ['manual_id' => 'id'],
        '[>]manual_level' => ['manual_level_id' => 'id']
    ], [
        'player_manual.id',
        'player_manual.uid',
        'player_manual.manual_id',
        'player_manual.manual_level_id',
        'player_manual.max_manual_level_id',
        'player_manual.level',
        'manual.name',
        'manual.info',
        'manual_level.name(manual_level_name)',
        'manual_level.sub_name(manual_level_sub_name)',
        'manual_level.layer',
        'manual_level.sequence',
        'manual_level.is_max',
    ], [
        'player_manual.id' => $player->playerManualId,
    ]);

    $player->jingjie = Layer::name($manual['layer']);
    $player->cengci = $manual['manual_level_name'];
    $player->playerManualName = $manual['name'];
    $player->manualId = $manual['manual_id'];
    $player->manualLevelId = $manual['manual_level_id'];
    $player->manualSequence = $manual['sequence'];
    $player->isMaxManualLevel = $manual['is_max'];
    $player->layerId = $manual['layer'];

    return $player;
}

function getplayer1($uid, Medoo $db): Player
{
    $columns = $db->get('game1', '*', ['id' => $uid]);
    if (empty($columns)) {
        return new Player();
    }
    $player = Player::fromArray($columns);
    return $player;
}

class Item
{
    public $id;
    public $name;
    public $uiName;
    public $info;
    public $uiInfo;
    public $type;
    public $subType;
    public $price;
    public $rechargePrice;
    public $quality;
    public $event;
    public $extra;
    public $isBound;
    public $isStackable;
    public $isSellable;
    public $isTask;
    public $isLaunched;
    public $launchedShopType;
    public $isPackage;
    public $packageItems;

    /**
     * @var string 道具可触发的操作
     */
    public $operations;

    use FromArray;
}

class Guaiwu
{
    const FLAG_PRIVATE = 0x01;
    const FLAG_ONCE = 0x02;

    var $name;//昵称
    var $info;
    public $group;
    public $sex;
    public $gzb;//装备
    public $dljv;//装备几率
    public $gdj;//道具
    public $djjv;//道具几率
    public $gyp;//药品
    public $ypjv;//药品几率
    public $id;
    public $level;
    public $exp;//经验

    public $jingjie;
    public $isGroup;
    public $isAggressive;
    public $type;
    public $flags;

    public $hp;
    public $mp;
    public $maxhp;
    public $maxmp;
    public $baqi;
    public $wugong;
    public $wufang;
    public $fagong;
    public $fafang;
    public $mingzhong;
    public $shanbi;
    public $baoji;
    public $shenming;
    public $isPrivate;
    public $maxAmount;
    public $manualLevelId;
    public $skills;

    use FromArray;

    /**
     * @return int 是否是私有怪
     */
    public function isPrivate()
    {
        return $this->flags & self::FLAG_PRIVATE;
    }

    /**
     * @return int  是否是一次性怪物
     */
    public function isOnce()
    {
        return $this->flags & self::FLAG_ONCE;
    }

    // 筑基 1-30
    // 金丹 31-60
    // 元婴 61-90
    // 化神 91-120
    public static function create(array $columns)
    {
        $guaiwu = static::fromArray($columns);
        return $guaiwu;
    }
}

class MidGuaiwu extends Guaiwu
{
    public $mid;
    public $sid;
    public $uid;
    public $gid;
}

function getPlayerMonstersInfoByMid(int $mid, int $uid, Medoo $db): array
{
    $monsters = $db->select('midguaiwu', ['[>]guaiwu' => ['gid' => 'id']], [
        'midguaiwu.id',
        'midguaiwu.name',
        'midguaiwu.level',
        'midguaiwu.mid',
        'midguaiwu.gid',
        'midguaiwu.exp',
        'midguaiwu.uid',
        'midguaiwu.hp',
        'midguaiwu.maxhp',
        'midguaiwu.baqi',
        'midguaiwu.wugong',
        'midguaiwu.wufang',
        'midguaiwu.fagong',
        'midguaiwu.fafang',
        'midguaiwu.mingzhong',
        'midguaiwu.shanbi',
        'midguaiwu.baoji',
        'midguaiwu.shenming',
        'guaiwu.is_group',
        'guaiwu.is_aggressive',
        'guaiwu.type',
        'guaiwu.flags',
        'guaiwu.max_amount',
        'guaiwu.manual_level_id',
        'guaiwu.skills',
    ],  ['midguaiwu.mid' => $mid, 'midguaiwu.uid' => $uid]);
    return $monsters;
}

function getMidGuaiwu(Medoo $db, $gid): MidGuaiwu
{
    $columns = $db->get('midguaiwu', ['[>]guaiwu' => ['gid' => 'id']], [
        'midguaiwu.id',
        'midguaiwu.name',
        'midguaiwu.level',
        'midguaiwu.mid',
        'midguaiwu.gid',
        'midguaiwu.exp',
        'midguaiwu.uid',
        'midguaiwu.hp',
        'midguaiwu.maxhp',
        'midguaiwu.baqi',
        'midguaiwu.wugong',
        'midguaiwu.wufang',
        'midguaiwu.fagong',
        'midguaiwu.fafang',
        'midguaiwu.mingzhong',
        'midguaiwu.shanbi',
        'midguaiwu.baoji',
        'midguaiwu.shenming',
        'guaiwu.is_group',
        'guaiwu.is_aggressive',
        'guaiwu.type',
        'guaiwu.flags',
        'guaiwu.manual_level_id',
        'guaiwu.skills',
    ],  ['midguaiwu.id' => $gid]);
    if (empty($columns)) {
        return new MidGuaiwu();
    }
    return MidGuaiwu::fromArray($columns);
}

function getGroupMonsters(int $mid, int $uid, Medoo $db): array
{
    $data = $db->select('midguaiwu', ['[>]guaiwu' => ['gid' => 'id']], [
        'midguaiwu.id',
        'midguaiwu.name',
        'midguaiwu.level',
        'midguaiwu.mid',
        'midguaiwu.gid',
        'midguaiwu.exp',
        'midguaiwu.uid',
        'midguaiwu.hp',
        'midguaiwu.maxhp',
        'midguaiwu.baqi',
        'midguaiwu.wugong',
        'midguaiwu.wufang',
        'midguaiwu.fagong',
        'midguaiwu.fafang',
        'midguaiwu.mingzhong',
        'midguaiwu.shanbi',
        'midguaiwu.baoji',
        'midguaiwu.shenming',
        'guaiwu.is_group',
        'guaiwu.is_aggressive',
        'guaiwu.type',
        'guaiwu.flags',
        'guaiwu.manual_level_id',
        'guaiwu.skills',
    ],  ['midguaiwu.mid' => $mid, 'midguaiwu.uid' => $uid]);
    if (empty($data)) {
        return [];
    }

    $monsters = [];
    foreach ($data as $columns) {
        $guaiwu = MidGuaiwu::create($columns);
        $monsters[] = $guaiwu;
    }
    return $monsters;
}

//获取怪物库怪物
function getGuaiwu($gyid, Medoo $db): Guaiwu
{
    $columns = $db->get('guaiwu', '*', ['id' => $gyid]);
    if (empty($columns)) {
        return new Guaiwu();
    }
    $guaiwu = Guaiwu::fromArray($columns);
    return $guaiwu;
}


class Clmid
{
    public $mname;//
    public $mgid;
    public $mid;//
    public $mnpc;//经验
    public $upmid;
    public $downmid;
    public $leftmid;
    public $rightmid;
    public $mgtime;
    public $midboss;
    public $ms;
    public $midinfo;
    public $mqy;
    public $playerinfo;
    public $ispvp;
    public $resources;
    public $lingqi;
    public $enterCondition;
    public $ornaments;

    use FromArray;
}

/**
 * @param int $mid
 * @param Medoo $db
 * @return Clmid
 */
function getmid(int $mid, Medoo $db): Clmid
{
    if ($mid <= 0) {
        return new Clmid();
    }
    $clmid = $db->get('mid', [
        'mname',
        'mgid',
        'mid',
        'mup(upmid)',
        'mdown(downmid)',
        'mleft(leftmid)',
        'mright(rightmid)',
        'mnpc',
        'mgtime',
        'midboss',
        'ms',
        'midinfo',
        'mqy',
        'playerinfo',
        'ispvp',
        'resources',
        'lingqi',
        'enter_condition',
        'ornaments',
    ], ['mid' => $mid]);
    return Clmid::fromArray($clmid);
}

function getOverride(int $uid, int $nid, Medoo $db): array
{
    $override = $db->select('npc_override_rel', '*', [
        'player_id' => $uid,
        'npc_id' => $nid
    ]);
    return $override;
}

/**
 * 接受任务时设置 NPC 覆盖信息
 *
 * @param int $uid
 * @param int $nid
 * @param int $oid
 * @param int $mid
 * @param Medoo $db
 */
function setOverride(int $uid, int $nid, int $oid, int $mid, Medoo $db)
{
    // 直接删除已有覆盖
    $db->delete('npc_override_rel', [
        'AND' => [
            'player_id' => $uid,
            'npc_id' => $nid,
        ]
    ]);
    // 当参数为零，表示恢复原装，不需再插入记录
    if ($oid == 0 || $mid == 0) {
        return;
    }
    // 插入新的状态覆盖
    $db->insert('npc_override_rel', [
        'player_id' => $uid,
        'npc_id' => $nid,
        'npc_override_id' => $oid,
        'mid' => $mid,
    ]);
}

/**
 * 提交任务时更新 NPC 覆盖信息，当 mid 为 0 时代表删除当前覆盖，其他情况视为更新操作
 *
 * @param int $uid
 * @param int $nid
 * @param int $oid
 * @param int $mid
 * @param Medoo $db
 */
function updateOverride(int $uid, int $nid, int $oid, int $mid, Medoo $db)
{
    if ($oid == 0 || $mid == 0) {
        // 直接删除已有覆盖
        $db->delete('npc_override_rel', [
            'player_id' => $uid,
            'npc_id' => $nid,
        ]);
    } else {
        setOverride($uid, $nid, $oid, $mid, $db);
    }
}

function getAllValidNpc(int $uid, int $mid, array $ids, Medoo $db): array
{
    // 获取所有与当前场景有关的覆盖信息
    $overrides = $db->select('npc_override_rel', '*', [
        'player_id' => $uid,
        'OR' => [
            'mid' => $mid,
            'npc_id' => $ids
        ],
    ]);
    // 添加新的 NPC 编号
    $overrideIds = [];
    $overrideMids = [];
    foreach ($overrides as $v) {
        $overrideIds[$v['npc_id']] = $v['npc_override_id'];
        $overrideMids[$v['npc_id']] = $v['mid'];
        $ids[] = $v['npc_id'];
    }
    $ids = array_unique($ids);

    // 筛选属于当前场景的 NPC
    $validNpc = [];
    $validOverride = [];
    $allRawNpc = $db->select('npc', '*', ['id' => $ids]);
    foreach ($allRawNpc as $v) {
        $id = $v['id'];
        if (!isset($overrideMids[$id])) {
            // 场景原有 NPC
            $v['override_id'] = 0;
            $validNpc[$id] = $v;
        } else if ($overrideMids[$id] == $mid) {
            // 覆盖到当前场景的新 NPC
            $v['override_id'] = $overrideIds[$id];
            $validNpc[$id] = $v;
            $validOverride[] = $overrideIds[$id];
        }
    }

    return array_values($validNpc);
}

/**
 * 检查角色是否到达了瓶颈
 *
 * 目前设定角色的功法等级不会设置到瓶颈条目，也就是说瓶颈不作为功法等级使用。
 *
 * @param $sid
 * @param Medoo $db
 * @return int
 */
function istupo($uid, Medoo $db)
{
    return false;
    $player = getPlayerById($db, $uid);
    // 经验未满，不需要突破
    if ($player->exp < $player->maxExp) {
        return 0;
    }

    // 瓶颈的信息
    $manualLevel = $db->get('manual_level', ['level'], [
        'manual_id' => $player->manualId,
        'sequence' => $player->manualSequence,
        'is_max_exp' => 1,
        'level[>=]' => $player->level,
        'ORDER' => ['level' => 'ASC']
    ]);

    // 角色达到了瓶颈等级，且经验已经存满
    if ($manualLevel['level'] == $player->level && $player->exp >= $player->maxExp) {
        return 1;
    }

    return 0;
}

function changeexp(Medoo $db, int $uid, $exp)
{
    $player = getPlayerById($db, $uid, true);
    $totalExp = $player->expPool + $exp;
    do {
        //@notice
        checkUpLevel:
        // 经验已经满了，并且未遇到瓶颈可以升级
        if ($player->exp == $player->maxExp) {
            $r = upplayerlv($db, $uid);
            if ($r) {
                // 还有剩余经验，升级后重新开始循环
                if ($totalExp > 0) {
                    // 重新获取角色最大经验数据
                    $player = getPlayerById($db, $uid, true);
                    continue;
                }
                break;
            }
            exit('FORBIDEN');
        }
        // 所有经验加起来不够升级
        if ($totalExp + $player->exp <= $player->maxExp) {
            $db->update('game1', ['exp' => $totalExp + $player->exp, 'exp_pool' => 0], ['id' => $player->id]);
            $player->exp += $totalExp;
            $totalExp = 0;
            //@todo 优化逻辑，正确处理 $totalExp 刚好够升级的情况
            if ($player->exp == $player->maxExp) {
                // 经验刚好够升级，继续循环，进行上面的升级处理逻辑
                // 这里不能使用 continue，由于 totalExp 已经为 0，continue 会直接跳出循环
                goto checkUpLevel;
            }
            //经验不够升级时直接跳出循环
            break;
        }
        // 先将当前等级经验加到 max_exp，剩余保存到经验池，剩余经验继续在循环开头判断是否可以继续
        $max = $player->maxExp - $player->exp;
        $totalExp -= $max;
        $player->exp += $max;
        $db->update('game1', ['exp' => $player->maxExp, 'exp_pool' => $totalExp], ['id' => $player->id]);
    } while ($totalExp > 0);

    return $exp;
}

function upplayerlv(Medoo $db, int $uid)
{
    $player = getPlayerById($db, $uid, true);
    if ($player->exp < $player->maxExp){
        return false;
    }
    $nextLevel = getPlayerPropertyByLevel($db, $player->level + 1);

    if (empty($nextLevel)) {
        return false;
    }

    $db->update('game1', [
        'exp' => 0,
        'max_exp' => $nextLevel['player_exp'],
        'level[+]' => 1,
        'hp' => $nextLevel['player_hp'],
        'maxhp' => $nextLevel['player_hp'],
        'baqi' => $nextLevel['player_baqi'],
        'wugong' => $nextLevel['player_gongji'],
        'fagong' => $nextLevel['player_gongji'],
        'wufang' => $nextLevel['player_fangyu'],
        'fafang' => $nextLevel['player_fangyu'],
        'mingzhong' => $nextLevel['player_mingzhong'],
        'shanbi' => $nextLevel['player_shanbi'],
        'baoji' => $nextLevel['player_baoji'],
        'shenming' => $nextLevel['player_shenming'],
    ], ['id' => $player->id]);

    $level = $player->level + 1;
    $message = [
        'uid' => 0,
        'tid' => $uid,
        'type' => 1,
        'content' => "恭喜你成功升到{$level}级!",
    ];
    $db->insert('im', $message);

    return true;
}

/**
 * @param int $level
 * @return array|bool|mixed
 */
function getPlayerPropertyByLevel(Medoo $db, int $level)
{
    if ($level < 1 || $level > 120) {
        return [];
    }
    return $db->get('system_data', [
        'player_exp',
        'player_hp',
        'player_baqi',
        'player_gongji',
        'player_fangyu',
        'player_mingzhong',
        'player_shanbi',
        'player_baoji',
        'player_shenming',
    ], ['level' => $level]);
}

class Npc{
    public $id;
    public $name;
    public $sex;
    public $info;
    public $taskid;
    public $muban;
    public $overrideId = 0;

    use FromArray;
}

function getnpc($nid, Medoo $db): Npc
{
    $columns = $db->get('npc', [
        'id',
        'name',
        'sex',
        'info',
        'taskid',
        'muban',
    ], ['id' => $nid]);

    if (empty($columns)) {
        return new Npc();
    }
    return Npc::fromArray($columns);
}

/**
 * 获取 NPC 信息
 * @param int $uid
 * @param int $nid
 * @param Medoo $db
 * @return npc
 */
function getOverrideNpc(int $uid, int $nid, Medoo $db)
{
    $npc = new npc();
    $raw = $db->get('npc', '*', ['id' => $nid]);
    $npc->id = $raw['id'];
    $npc->name = $raw['name'];
    $npc->sex = $raw['sex'];
    $npc->info = $raw['info'];
    $npc->taskid = $raw['taskid'];
    $npc->muban = $raw['muban'];

    $rel = $db->get('npc_override_rel', '*', ['player_id' => $uid, 'npc_id' => $nid]);
    if (!empty($rel)) {
        $override = $db->get('npc_override', '*', ['id' => $rel['npc_override_id']]);
        if (!empty($override)) {
            !empty($override['name']) && $npc->nname = $override['name'];
            !empty($override['sex']) && $npc->nsex = $override['sex'];
            !empty($override['info']) && $npc->info = $override['info'];
            !empty($override['muban']) && $npc->muban = $override['muban'];
            !empty($override['taskid']) && $npc->taskid = sprintf('%s,%s', $npc->taskid, $override['taskid']);
            $npc->override_id = $override['id'];
        }
    }
    return $npc;
}

class Equip extends Item
{
    public $equipInfoId;
    public $baqi;
    public $wugong;
    public $fagong;
    public $baoji;
    public $shenming;
    public $shanbi;
    public $mingzhong;
    public $wufang;
    public $fafang;
    public $hp;
    public $mp;
    public $level;
    public $equipType;
    public $keywords = [];
    public $shengxing = 0;
    public $qianghua = 0;
    public $sex;
    public $manualId;
}

class PlayerEquip extends Equip
{
    public $itemId;
    public $subItemId;
    public $uid;

    /**
     * @var int 品质随机血量
     */
    public $qualityHp;

    /**
     * @var int 品质随机蓝量
     */
    public $qualityMp;

    /**
     * @var int 品质随机神力
     */
    public $qualityBaqi;

    /**
     * @var int 品质随机物攻
     */
    public $qualityWugong;

    /**
     * @var int 品质随机法攻
     */
    public $qualityFagong;

    /**
     * @var int 品质随机物防
     */
    public $qualityWufang;

    /**
     * @var int 品质随机法防
     */
    public $qualityFafang;

    /**
     * @var int 品质随机闪避
     */
    public $qualityShanbi;

    /**
     * @var int 品质随机命中
     */
    public $qualityMingzhong;

    /**
     * @var int 品质随机暴击
     */
    public $qualityBaoji;

    /**
     * @var int 品质随机神明
     */
    public $qualityShenming;

    /**
     * @var int 装备品质
     */
    public $quality;

    /**
     * @var string 品质颜色
     */
    public $qualityColor =  '';

    /**
     * @var int 存储位置
     */
    public $storage;

    /**
     * @var string 装备来源地图
     */
    public $sourceLocation = '';

    /**
     * @var string 装备来源对象
     */
    public $sourceMonster = '';

    /**
     * @var string 装备来源玩家
     */
    public $sourcePlayer = '';

    /**
     * @var string 装备来源时间
     */
    public $sourceTimestamp = '';
}

function getMultiPlayerEquips(Medoo $db, int $uid, int $storage, int $offset, int $size): array
{
    $arr = $db->select('player_item', [
        '[>]item' => ['item_id' => 'id'],
        '[>]player_equip_info' => ['sub_item_id' => 'id']
    ], [
        'player_item.id',
        'player_item.uid',
        'player_item.item_id',
        'player_item.storage',
        'player_item.is_bound',
        'item.info',
        'item.is_sellable',
        'player_equip_info.name',
        'player_equip_info.ui_name',
        'player_equip_info.quality',
        'player_equip_info.level',
        'player_equip_info.equip_type',
        'player_equip_info.manual_id',
        'player_equip_info.sex',
        'player_equip_info.shengxing',
        'player_equip_info.qianghua',
    ], [
        'player_item.uid' => $uid,
        'player_item.storage' => $storage,
        'item.type' => 2,
        'LIMIT' => [$offset, $size],
        'ORDER' => ['player_item.id' => 'DESC'],
        ]);
    return $arr;
}

function countPlayerEquips(Medoo $db, int $uid, int $storage = 0): int
{
    $condition = [
        'player_item.uid' => $uid,
        'item.type' => 2,
    ];
    if ($storage) {
        $condition['player_item.storage'] = $storage;
    }
    $arr = $db->count('player_item', [
        '[>]item' => ['item_id' => 'id'],
    ], [
        'player_item.id'
    ], $condition);
    return $arr;
}

function getEquip(Medoo $db, $id)
{
    $columns = $db->get('item', [
        '[>]equip_info' => ['id' => 'item_id'],
    ], [
        'item.id',
        'item.name',
        'item.ui_name',
        'item.info',
        'item.is_bound',
        'item.is_sellable',
        'equip_info.id(equip_info_id)',
        'equip_info.hp',
        'equip_info.mp',
        'equip_info.baqi',
        'equip_info.wugong',
        'equip_info.fagong',
        'equip_info.wufang',
        'equip_info.fafang',
        'equip_info.mingzhong',
        'equip_info.shanbi',
        'equip_info.baoji',
        'equip_info.shenming',
        'equip_info.level',
        'equip_info.equip_type',
        'equip_info.manual_id',
        'equip_info.sex',
    ], ['item.id' => $id]);
    if (empty($columns)) {
        return new Equip();
    }
    $equip = Equip::fromArray($columns);
    return $equip;
}


function getPlayerEquip(Medoo $db, int $id)
{
    $columns = $db->get('player_item', [
        '[>]item' => ['item_id' => 'id'],
        '[>]player_equip_info' => ['sub_item_id' => 'id'],
    ], [
        'player_item.id',
        'player_item.uid',
        'player_item.item_id',
        'player_item.sub_item_id',
        'player_item.storage',
        'player_item.is_bound',
        'item.info',
        'item.price',
        'item.is_sellable',
        'player_equip_info.name',
        'player_equip_info.ui_name',
        'player_equip_info.hp',
        'player_equip_info.mp',
        'player_equip_info.baqi',
        'player_equip_info.wugong',
        'player_equip_info.fagong',
        'player_equip_info.wufang',
        'player_equip_info.fafang',
        'player_equip_info.mingzhong',
        'player_equip_info.shanbi',
        'player_equip_info.baoji',
        'player_equip_info.shenming',
        'player_equip_info.quality_hp',
        'player_equip_info.quality_mp',
        'player_equip_info.quality_baqi',
        'player_equip_info.quality_wugong',
        'player_equip_info.quality_fagong',
        'player_equip_info.quality_wufang',
        'player_equip_info.quality_fafang',
        'player_equip_info.quality_mingzhong',
        'player_equip_info.quality_shanbi',
        'player_equip_info.quality_baoji',
        'player_equip_info.quality_shenming',
        'player_equip_info.quality',
        'player_equip_info.source_location',
        'player_equip_info.source_monster',
        'player_equip_info.source_player',
        'player_equip_info.source_timestamp',
        'player_equip_info.level',
        'player_equip_info.equip_type',
        'player_equip_info.shengxing',
        'player_equip_info.qianghua',
        'player_equip_info.manual_id',
        'player_equip_info.sex',
    ], [
        'player_item.id' => $id,
    ]);
    if (empty($columns)) {
        return new PlayerEquip();
    }
    $equip = PlayerEquip::fromArray($columns);
    $equip->keywords = $db->select('player_equip_keyword', '*', [
        'item_id' => $equip->id,
        'ORDER' => ['effect_type' => 'DESC']
    ]);

    return $equip;
}

/**
 * @param int[] $ids
 * @return PlayerEquip[]
 */
function getPlayerEquipsByIds(Medoo $db, array $ids, bool $withKeywords = true): array
{
    $equips = [];
    if (empty($ids)) {
        return $equips;
    }
    $equipArr = $db->select('player_item', [
        '[>]item' => ['item_id' => 'id'],
        '[>]player_equip_info' => ['sub_item_id' => 'id'],
    ], [
        'player_item.id',
        'player_item.uid',
        'player_item.item_id',
        'player_item.sub_item_id',
        'player_item.is_bound',
        'item.info',
        'item.is_sellable',
        'player_equip_info.name',
        'player_equip_info.ui_name',
        'player_equip_info.hp',
        'player_equip_info.mp',
        'player_equip_info.baqi',
        'player_equip_info.wugong',
        'player_equip_info.fagong',
        'player_equip_info.wufang',
        'player_equip_info.fafang',
        'player_equip_info.mingzhong',
        'player_equip_info.shanbi',
        'player_equip_info.baoji',
        'player_equip_info.shenming',
        'player_equip_info.quality_hp',
        'player_equip_info.quality_mp',
        'player_equip_info.quality_baqi',
        'player_equip_info.quality_wugong',
        'player_equip_info.quality_fagong',
        'player_equip_info.quality_wufang',
        'player_equip_info.quality_fafang',
        'player_equip_info.quality_mingzhong',
        'player_equip_info.quality_shanbi',
        'player_equip_info.quality_baoji',
        'player_equip_info.quality_shenming',
        'player_equip_info.quality',
        'player_equip_info.level',
        'player_equip_info.equip_type',
        'player_equip_info.shengxing',
        'player_equip_info.qianghua',
        'player_equip_info.manual_id',
        'player_equip_info.sex',
    ], [
        'player_item.id' => $ids,
    ]);
    if (empty($equipArr)) {
        return [];
    }

    $keywordMap = [];
    if ($withKeywords) {
        $keywords = $db->select('player_equip_keyword', '*', [
            'item_id' => $ids,
            'ORDER' => ['effect_type' => 'DESC']
        ]);
        foreach ($keywords as $v) {
            if (!isset($keywordMap[$v['item_id']])) {
                $keywordMap[$v['item_id']] = [];
            }
            $keywordMap[$v['item_id']][] = $v;
        }
    }

    foreach ($equipArr as $v) {
        $playerEquip = PlayerEquip::fromArray($v);
        if (isset($keywordMap[$playerEquip->id])) {
            $playerEquip->keywords = $keywordMap[$playerEquip->id];
        }
        $equips[] = $playerEquip;
    }
    return $equips;
}

class PlayerItem extends Item
{
    public $itemId;
    public $subItemId;
    public $uid;
    public $amount;

    /**
     * @var int 存储位置，1背包，2身上，3仓库
     */
    public $storage;
}

/**
 * 检查用户的物品绑定属性
 *
 * @param Medoo $db
 * @param int $uid
 * @return int
 */
function getPlayerBoundProperty(Medoo $db, int $uid): int
{
    $data = $db->get('game1', ['vip', 'qq', 'master_id'], ['id' => $uid]);
    if (
        empty($data)
        // 非会员且未认证的独立帐号直接绑定物品
        || ($data['vip'] == 0 && empty($data['qq'] && empty($data['master_id'])))
    ) {
        return 1;
    }
    return 0;
}

function addPlayerEquip(Medoo $db, int $uid, array $item, array $source, bool $random = false): bool
{
    if (Helper::isOverloaded($db, $uid)) {
        $message = [
            'uid' => 0,
            'tid' => $uid,
            'type' => 1,
            'content' => '背包负重已达到上限，无法继续获得装备',
        ];
        $db->insert('im', $message);
        return false;
    }

    $equip = getEquip($db, $item['id']);
    if (!$equip->id || !$equip->equipInfoId) {
        $message = [
            'uid' => 0,
            'tid' => $uid,
            'type' => 1,
            'content' => '道具不存在，获取失败',
        ];
        $db->insert('im', $message);
        return false;
    }

    $isBound = $equip->isBound;
    // 非绑定装备还需要检查人物的绑定属性
    if (!$isBound) {
        //$isBound = getPlayerBoundProperty($db, $uid);
    }

    $db->insert('player_item', [
        'uid' => $uid,
        'item_id' => $equip->id,
        'sub_item_id' => 0,
        'amount' => 1,
        'is_bound' => $isBound,
    ]);
    $playerItemId = $db->id();
    $playerEquipInfo = [
        'name' => $equip->name,
        'ui_name' => $equip->uiName,
        'item_id' => $playerItemId,
        'level' => $equip->level,
        'hp' => $equip->hp,
        'mp' => $equip->mp,
        'baqi' => $equip->baqi,
        'wugong' => $equip->wugong,
        'fagong' => $equip->fagong,
        'wufang' => $equip->wufang,
        'fafang' => $equip->fafang,
        'shanbi' => $equip->shanbi,
        'mingzhong' => $equip->mingzhong,
        'baoji' => $equip->baoji,
        'shenming' => $equip->shenming,
        'equip_type' => $equip->equipType,
        'manual_id' => $equip->manualId,
        'sex' => $equip->sex,
        'source_location' => $source['location'] ?? '',
        'source_monster' => $source['monster'] ?? '',
        'source_player' => $source['player'] ?? '',
    ];
    if ($random) {
       //随机装备
        $randomNum = rand(1, 1000);
        $ranges = [5 => 4, 10 => 3, 100 => 2, 200 => 1];
        $quality = 0;
        foreach ($ranges as $k => $v) {
            if ($randomNum <= $k) {
                $quality = $v;
                break;
            }
        }
        // 极品装备属性提升
        if ($quality) {
            $rate = 0.1;
            $randomAttr = [
                'hp', 'mp', 'baqi', 'wugong', 'fagong', 'wufang', 'fafang', 'shanbi', 'mingzhong', 'baoji', 'shenming'
            ];
            $prefixArray = [
                1 => '优',
                2 => '精',
                3 => '极',
                4 => '神',
            ];
            // 打乱属性排列
            shuffle($randomAttr);
            // 随机词条数量
            $num = 0;
            $maxQuality = 0;
            foreach ($randomAttr as $k) {
                if ($num >= $quality) {
                    break;
                }
                $q = rand(1, $quality);
                if (empty($playerEquipInfo[$k]) || !$q) {
                    continue;
                }
                $maxQuality = max($q, $maxQuality);
                $num++;
                $playerEquipInfo["quality_$k"] = ceil($playerEquipInfo[$k] * ($rate * $q));
            }
            $quality = max($maxQuality, $num);
            $playerEquipInfo['name'] = sprintf('(%s)%s', $prefixArray[$quality], $playerEquipInfo['name']);
            $playerEquipInfo['ui_name'] = sprintf('(%s)%s', $prefixArray[$quality], $playerEquipInfo['ui_name']);
            $playerEquipInfo['quality'] = $quality;
        }
    }
    $db->insert('player_equip_info', $playerEquipInfo);
    $infoId = $db->id();
    // 更新玩家装备的详情编号
    $db->update('player_item', ['sub_item_id' => $infoId], ['id' => $playerItemId]);

    // 添加关键字属性
    $keywords = $db->select('equip_keyword', '*', ['item_id' => $equip->id]);
    if (!empty($keywords)) {
        foreach ($keywords as &$v) {
            unset($v['id']);
            $v['item_id'] = $playerItemId;
        }
        $db->insert('player_equip_keyword', $keywords);
    }

    // 更新道具对应的未完成任务条件
    changeAllPlayerTaskConditionsByItemId($db, $uid, $item['id'], 1);
    // 更新任务状态
    updateTaskStatusWhenFinished($db, $uid);
    return true;
}

function addPlayerStackableItem(Medoo $db, int $uid, array $item, int $amount): bool
{
    // 目前只支持添加到背包
    $storage = 1;

    // @FIXME 这里需要考虑绑定属性，
    $itemObject = getItem($db, $item['id']);
    $isBound = $itemObject->isBound;
    // 非绑定装备还需要检查人物的绑定属性
    if (!$isBound) {
        //$isBound = getPlayerBoundProperty($db, $uid);
    }

    $playerItem = getPlayerItem($db, $item['id'], $uid, $storage);
    if ($playerItem->id) {
        // 更新时也更新绑定属性，之前绑定的物品也会变成非绑定状态
        $db->update('player_item', [
            'amount[+]' => $amount,
            'is_bound' => $isBound,
            ], [ 'uid' => $uid, 'id' => $playerItem->id]);
    } else {
        // 检查角色负重
        if (Helper::isOverloaded($db, $uid)) {
            //@FIXME
            //Game::flash()->error('背包负重已达到上限，无法继续获得道具');
            return false;
        }
        if ($item['type'] == 4) {
            $info = $db->get('formula_info', ['id'], ['item_id' => $item['id']]);
        }
        $db->insert('player_item', [
            'uid' => $uid,
            'item_id' => $item['id'],
            'sub_item_id' => !empty($info) ? $info['id'] : 0,
            'amount' => $amount,
            'storage' => $storage,
            'is_bound' => $isBound,
        ]);
    }
    // 更新道具对应的未完成任务条件
    changeAllPlayerTaskConditionsByItemId($db, $uid, $item['id'], $amount);
    // 更新任务状态
    updateTaskStatusWhenFinished($db, $uid);
    return true;
}

function adddj(Medoo $db, int $uid, Item $item, int $amount, array $source = [])
{
    if ($item->type == 2) {
        addPlayerEquip($db, $uid, (array)$item, $source);
    } else {
        addPlayerStackableItem($db, $uid, (array)$item, $amount);
    }
    changeAllPlayerTaskConditionsByItemId($db, $uid, $item->id, $amount);
}

function changeTaskMonsterCondition($db, array $taskIds, int $gid, int $uid)
{
    $db->update('player_task_condition', ['amount[+]' => 1], [
        'uid' => $uid,
        'task_id' => $taskIds,
        'type' => 2,
        'condition_id' => $gid,
        'amount[<]' => Medoo::raw('required_amount')
    ]);
}

function changeTaskItemCondition(Medoo $db, array $taskIds, int $itemId, int $amount, int $uid)
{
    $db->update('player_task_condition', ['amount[+]' => $amount], [
        'uid' => $uid,
        'task_id' => $taskIds,
        'type' => 1,
        'condition_id' => $itemId,
        'amount[<]' => Medoo::raw('required_amount')
    ]);
    $db->update('player_task_condition', ['amount' => Medoo::raw('required_amount')], [
        'uid' => $uid,
        'task_id' => $taskIds,
        'type' => 1,
        'condition_id' => $itemId,
        'amount[>]' => Medoo::raw('required_amount')
    ]);
}

function changeAllPlayerTaskConditionsByItemId(Medoo $db, int $uid, $itemId, $amount)
{
    $tasks = getPlayerUnfinishedTasks($db, $uid);
    if (empty($tasks)) {
        return;
    }
    $taskIds = array_map(function($v) {
        return $v['task_id'];
    }, $tasks);
    changeTaskItemCondition($db, $taskIds, $itemId, $amount, $uid);
}

function getUnfinishedTasks(Medoo $db, int $uid)
{
    return $db->select('player_task', [
        '[>]task_info' => ['task_info_id' => 'id']
    ], [
        'player_task.task_id'
    ], [
        'player_task.uid' => $uid,
        'player_task.status[!]' => 3,
        'task_info.type[!]' => 3,
    ]);
}

function getPlayerUnfinishedTasks(Medoo $db, int $uid)
{
    return $db->select('player_task', [
        '[>]task_info' => ['task_info_id' => 'id']
    ], [
        'player_task.id',
        'player_task.task_id',
        'player_task.status',
        'task_info.type',
        'task_info.mode',
        'task_info.name',
    ], [
        'player_task.uid' => $uid,
        'player_task.status[!]' => 3,
        'task_info.type[!]' => 4, //跳过剧情类
    ]);
}

function getPlayerFinishedTasks(Medoo $db, int $uid)
{
    return $db->select('player_task', [
        '[>]task_info' => ['task_info_id' => 'id']
    ], [
        'player_task.id',
        'player_task.task_id',
        'player_task.status',
        'task_info.type',
        'task_info.mode',
        'task_info.name',
    ], [
        'player_task.uid' => $uid,
        'player_task.status' => 3,
        //'task_info.type[!]' => 4, // 跳过剧情类
        'ORDER' => ['id' => 'DESC']
    ]);
}

function countPlayerUnfinishedTasks(Medoo $db, int $uid)
{
    return $db->count('player_task', [
        '[>]task_info' => ['task_info_id' => 'id']
    ], [
        'player_task.id'
    ],[
        'player_task.uid' => $uid,
        'player_task.status[!]' => 3,
        'task_info.type[!]' => 4,
    ]);
}

function updateTaskStatusWhenFinished(Medoo $db, int $uid)
{
    // 获取未完成任务的条件
    $conditions =  $db->select('player_task_condition(c)', [
        '[>]task_info' => ['c.task_info_id' => 'id'],
        '[>]player_task' => ['c.player_task_id' => 'id']
    ], [
        'c.task_id',
        'c.amount',
        'c.required_amount'
    ], [
        'c.uid' => $uid,
        'player_task.status[!]' => 3,
        'task_info.type[!]' => 3,
    ]);

    if (empty($conditions)) {
        return;
    }
    // 判断是否所有条件都已达成
    $taskStatus = [];
    foreach ($conditions as $v) {
        $tid = $v['task_id'];
        if (!isset($taskStatus[$tid])) {
            $taskStatus[$tid] = true;
        }
        if ($taskStatus[$tid] === false) {
            continue;
        }
        if ($v['amount'] < $v['required_amount']) {
            $taskStatus[$tid] = false;
        }
    }
    $finishedIds = [];
    $unfinishedIds = [];
    foreach ($taskStatus as $k => $v) {
        if ($v === false) {
            $unfinishedIds[] = $k;
            continue;
        }
        $finishedIds[] = $k;
    }
    // 将所有条件都达成的任务标记为已完成
    if (!empty($finishedIds)) {
        $db->update('player_task', ['status' => 1], ['uid' => $uid, 'task_id' => $finishedIds]);
    }
    if (!empty($unfinishedIds)) {
        $db->update('player_task', ['status' => 2], ['uid' => $uid, 'task_id' => $unfinishedIds]);
    }
}

function getPlayerTaskConditionsById(Medoo $db, int $uid, int $id, int $type = 1)
{
    // 收集物品的条件
    if ($type === 1) {
        return $db->select('player_task_condition(c)', [
            '[>]task_info' => ['c.task_info_id' => 'id'],
            '[>]item' => ['c.condition_id' => 'id']

        ], [
            'task_info.name(task_name)',
            'item.name(target_name)',
            'c.task_id',
            'c.condition_id',
            'c.amount',
            'c.required_amount'
        ], [
            'c.uid' => $uid,
            'c.player_task_id' => $id,
            'c.type' => 1,
        ]);
    }

    // 击杀怪物的条件
    return $db->select('player_task_condition(c)', [
        '[>]player_task' => ['c.player_task_id' => 'id'],
        '[>]task_info' => ['c.task_info_id' => 'id'],
        '[>]guaiwu' => ['c.condition_id' => 'id']
    ], [
        'task_info.name(task_name)',
        'guaiwu.name(target_name)',
        'c.task_id',
        'c.condition_id',
        'c.amount',
        'c.required_amount'
    ], [
        'c.uid' => $uid,
        'c.player_task_id' => $id,
        'c.type' => 2,
    ]);
}

function getPlayerUnfinishedTasksConditions(Medoo $db, int $uid, int $conditionId, int $type = 1)
{
    // 收集物品的条件
    if ($type === 1) {
        return $db->select('player_task_condition(c)', [
            '[>]player_task' => ['c.player_task_id' => 'id'],
            '[>]task_info' => ['c.task_info_id' => 'id'],
            '[>]item' => ['c.condition_id' => 'id']

        ], [
            'task_info.name(task_name)',
            'item.name(target_name)',
            'c.task_id',
            'c.amount',
            'c.required_amount'
        ], [
            'c.uid' => $uid,
            'player_task.status[!]' => 3,
            'c.condition_id' => $conditionId,
            'c.type' => 1,
        ]);
    }

    // 击杀怪物的条件
    return $db->select('player_task_condition(c)', [
        '[>]player_task' => ['c.player_task_id' => 'id'],
        '[>]task_info' => ['c.task_info_id' => 'id'],
        '[>]guaiwu' => ['c.condition_id' => 'id']
    ], [
        'task_info.name(task_name)',
        'guaiwu.name(target_name)',
        'c.task_id',
        'c.amount',
        'c.required_amount'
    ], [
        'c.uid' => $uid,
        'player_task.status[!]' => 3,
        'c.condition_id' => $conditionId,
        'c.type' => 2,
    ]);
}

function getPlayerItemById(Medoo $db, int $id, int $uid)
{
    $columns = $db->get('player_item', [
        '[>]item' => ['item_id' => 'id']
    ], [
        'player_item.id',
        'item.name',
        'item.ui_name',
        'item.info',
        'item.ui_info',
        'item.type',
        'item.price',
        'item.recharge_price',
        'item.quality',
        'item.event',
        'item.extra',
        'item.is_stackable',
        'item.is_sellable',
        'item.is_task',
        'item.is_launched',
        'item.launched_shop_type',
        'item.is_package',
        'item.package_items',
        'player_item.item_id',
        'player_item.sub_item_id',
        'player_item.uid',
        'player_item.amount',
        'player_item.storage',
        'player_item.is_bound',
    ], [
        'player_item.id' => $id,
        'player_item.uid' => $uid,
    ]);
    if (empty($columns)) {
        return new PlayerItem();
    }
    return PlayerItem::fromArray($columns);
}

function getPlayerItem(Medoo $db, int $itemId, int $uid, int $storage = 1)
{
    $columns = $db->get('player_item', [
        '[>]item' => ['item_id' => 'id']
    ], [
        'player_item.id',
        'item.name',
        'item.ui_name',
        'item.info',
        'item.ui_info',
        'item.type',
        'item.price',
        'item.recharge_price',
        'item.quality',
        'item.event',
        'item.extra',
        'item.is_stackable',
        'item.is_sellable',
        'item.is_task',
        'item.is_launched',
        'item.launched_shop_type',
        'item.is_package',
        'item.package_items',
        'player_item.item_id',
        'player_item.sub_item_id',
        'player_item.uid',
        'player_item.amount',
        'player_item.storage',
        'player_item.is_bound',
    ], [
        'player_item.item_id' => $itemId,
        'player_item.uid' => $uid,
        'player_item.storage' => $storage,
        'LIMIT' => 1,
    ]);
    if (empty($columns)) {
        return new PlayerItem();
    }
    return PlayerItem::fromArray($columns);
}

function deleteOnePlayerEquipByItemId(Medoo $db, int $itemId, $uid)
{
    $item = $db->get('player_item', ['id', 'item_id', 'sub_item_id'], ['item_id' => $itemId, 'uid' => $uid]);
    if (!empty($item)) {
        $db->delete('player_item', ['id' => $item['id']]);
        $db->delete('player_equip_info', ['item_id' => $item['id']]);
    }
}

function getPlayerItemsByItemIds(Medoo $db, array $ids, int $uid, int $storage = 1)
{
    $items = $db->select('player_item', [
        '[>]item' => ['item_id' => 'id']
    ], [
        'player_item.id',
        'item.name',
        'item.ui_name',
        'item.info',
        'item.ui_info',
        'item.type',
        'item.price',
        'item.recharge_price',
        'item.quality',
        'item.event',
        'item.extra',
        'item.is_stackable',
        'item.is_sellable',
        'item.is_task',
        'item.is_launched',
        'item.launched_shop_type',
        'item.is_package',
        'item.package_items',
        'player_item.item_id',
        'player_item.sub_item_id',
        'player_item.uid',
        'player_item.amount',
        'player_item.is_bound',
    ], [
        'player_item.item_id' => $ids,
        'player_item.uid' => $uid,
        'player_item.storage' => $storage,
    ]);

    return $items;
}

function getplayerpeifang($uid, $pid, Medoo $db)
{
    return $db->get('player_peifang', '*', ['uid' => $uid, 'peifang_id' => $pid]);
}

function getPlayerPeifangAll($uid, Medoo $db)
{
    return $db->select('player_peifang', '*', ['uid' => $uid]);
}

function getpeifang($pid, Medoo $db)
{
    return $db->get('peifang', '*', ['id' => $pid]);
}

function uppeifangrate($uid, $pid, $rate, Medoo $db)
{
    $peifang = getplayerpeifang($uid, $pid, $db);
    $proficiency = $peifang['proficiency'] + $rate;
    if ($proficiency > 100) {
        $proficiency = 100;
    }
    return $db->update('player_peifang', ['proficiency' => $proficiency], ['uid' => $uid, 'peifang_id' => $pid]);
}

function deledjsum(Medoo $db, int $itemId, int $djsum, int $uid)
{
    $daoju = \player\getPlayerItem($db,  $itemId, $uid);
    if ($daoju->id){
        if ($daoju->amount >= $djsum) {
            $r = $db->update('player_item', ['amount[-]' => $djsum], ['uid' => $uid, 'item_id' => $itemId]);
            $res = $r->rowCount() > 0;
            if ($res) {
                // 更新道具对应的未完成任务条件
                changeAllPlayerTaskConditionsByItemId($db, $uid, $itemId, -$djsum);
                // 更新任务状态
                updateTaskStatusWhenFinished($db, $uid);
            }
            return $res;
        }else{
            return false;
        }
    }else{
        return false;
    }
}

function getItem(Medoo $db, int $id)
{
    $columns = $db->get('item', '*', ['id' => $id]);
    if (empty($columns)) {
        return new Item();
    }
    return Item::fromArray($columns);
}

class Medicine extends Item
{
    /**
     * @var MedicineEffect[]
     */
    public $effects = [];
}

class MedicineEffect
{
    public $id;
    public $itemId;
    public $desc;
    public $info;
    public $uiInfo;
    public $column;
    public $amount;
    public $effectType;
    public $target;
    public $turns;
    public $effectTurn;
    public $isColumn;
    public $isWushang;
    public $isWumian;
    public $isFashang;
    public $isFamian;
    public $isMingzhong;
    public $isShanbi;
    public $isBaoji;
    public $isShemming;
    public $isDot;
    public $isTemporary;
    public $duration;
    public $isCombat;
    public $isCustom;
    public $customEffectType;
    public $identity;
    public $isUnique;
    public $isRaw;

    use FromArray;
}

class PlayerMedicine extends Medicine
{
    public $id;
    public $itemId;
    public $amount;

    /**
     * @var int 存储位置
     */
    public $storage;
}

function getMedicine(Medoo $db, int $id)
{
    $columns = $db->get('item', '*', ['id' => $id]);
    if (empty($columns)) {
        return new Medicine();
    }

    $medicine =  Medicine::fromArray($columns);
    $effects = $db->select('medicine_effects', '*', ['item_id' => $id]);
    foreach ($effects as $v) {
        $medicine->effects[] = MedicineEffect::fromArray($v);
    }

    return $medicine;
}

function getPlayerMedicine(Medoo $db, int $id)
{
    $columns = $db->get('player_item', [
        '[>]item' => ['item_id' => 'id'],
    ], [
        'player_item.id',
        'player_item.uid',
        'player_item.item_id',
        'player_item.amount',
        'player_item.storage',
        'player_item.is_bound',
        'item.name',
        'item.ui_name',
        'item.info',
        'item.ui_info',
        'item.type',
        'item.price',
        'item.recharge_price',
        'item.quality',
        'item.event',
    ], [
        'player_item.id' => $id,
    ]);
    if (empty($columns)) {
        return new PlayerMedicine();
    }
    $playerMedicine = PlayerMedicine::fromArray($columns);
    $effects = $db->select('medicine_effects', '*', ['item_id' => $playerMedicine->itemId]);
    foreach ($effects as $v) {
        $playerMedicine->effects[] = MedicineEffect::fromArray($v);
    }
    return $playerMedicine;
}

function getPlayerMedicineByItemId(Medoo $db, int $itemId, int $uid)
{
    $columns = $db->get('player_item', [
        '[>]item' => ['item_id' => 'id'],
    ], [
        'player_item.id',
        'player_item.uid',
        'player_item.item_id',
        'player_item.amount',
        'player_item.is_bound',
        'item.name',
        'item.ui_name',
        'item.info',
        'item.ui_info',
        'item.type',
        'item.price',
        'item.recharge_price',
        'item.quality',
        'item.event',
    ], [
        'player_item.uid' => $uid,
        'player_item.item_id' => $itemId,
    ]);
    if (empty($columns)) {
        return new PlayerMedicine();
    }
    $playerMedicine = PlayerMedicine::fromArray($columns);
    $effects = $db->select('medicine_effects', '*', ['item_id' => $itemId]);
    foreach ($effects as $v) {
        $playerMedicine->effects[] = MedicineEffect::fromArray($v);
    }
    return $playerMedicine;
}

function getPlayerYaopinById(Medoo $db, int $itemId, int $uid): PlayerMedicine
{
    $columns = $db->get('player_item', [
        '[>]item' => ['item_id' => 'id']
    ], [
        'player_item.id',
        'player_item.item_id',
        'player_item.amount',
        'player_item.is_bound',
        'item.name',
        'item.type',
        'item.quality',
    ], [
        'player_item.uid' => $uid,
        'player_item.item_id' => $itemId,
        'item.type' => 3
    ]);

    if (empty($columns)){
        return new PlayerMedicine();
    }
    return PlayerMedicine::fromArray($columns);
}


function getPlayerYaopinByIds(Medoo $db, array $ids, int $uid, bool $ignoreAmount = false)
{
    $conditions = [
        'player_item.id' => $ids,
        'player_item.uid' => $uid,
    ];
    if (!$ignoreAmount) {
        $conditions['player_item.amount[>]'] = 0;
    }
    $items = $db->select('player_item', [
        '[>]item' => ['item_id' => 'id']
    ], [
        'player_item.id',
        'player_item.item_id',
        'player_item.amount',
        'player_item.is_bound',
        'item.name',
        'item.type',
        'item.quality',
    ], $conditions);

    return $items;
}

function getplayeryaopinall(Medoo $db, int $uid, int $storage = 0)
{
    $condition = [
        'player_item.uid' => $uid,
        'player_item.amount[>]' => 0,
        'item.type' => 3
    ];
    if ($storage) {
        $condition['storage'] = $storage;
    }
    $all = $db->select('player_item', [
        '[>]item' => ['item_id' => 'id']
    ], [
        'player_item.id',
        'player_item.item_id',
        'player_item.amount',
        'player_item.is_bound',
        'item.name',
        'item.type',
        'item.quality',
    ], $condition);
    return $all;
}

function delYaopinById($uid, $id, $ypsum, Medoo $db)
{
    $r = $db->update('player_item', ['amount[-]' => $ypsum], ['id' => $id, 'amount[>=]' => $ypsum]);
    return $r ? true : false;
}

function deleyaopin(Medoo $db, int $itemId, int $amount, int $uid)
{
    $yaopin = getPlayerMedicineByItemId($db, $itemId, $uid);
    if ($yaopin) {
        if ($yaopin->amount >= $amount) {
            return $db->update('player_item', ['amount[-]' => $amount], ['id' => $yaopin->id]);
        }else{
            return false;
        }
    }else{
        return false;
    }
}

function changeplayersx($sx, $gaibian, $uid, Medoo $db)
{
    return $db->update('game1', [$sx => $gaibian], ['id' => $uid]);
}

function addplayersx($sx, $gaibian, $uid, Medoo $db)
{
    $column = sprintf('%s[+]', $sx);
    return $db->update('game1', [$column => $gaibian], ['id' => $uid]);
}

//改变货币
function changeyxb(Medoo $db, $lx, $gaibian, int $uid)
{
    if (empty($gaibian)) {
        return true;
    }
    if ($lx == 1) {
        $res = $db->update('game1', ['uyxb[+]' => $gaibian], ['id' => $uid]);
    } else if ($lx == 2) {
        $res = $db->update('game1', ['uyxb[-]' => $gaibian], [
            'id' => $uid,
            'uyxb[>=]' => $gaibian,
        ]);
    }
    return $res->rowCount() > 0;
}

//改变货币
function changeczb($lx,$gaibian,$uid, Medoo $db)
{
    $player = getPlayerById($db, $uid, true);
    if ($lx==1) {
        return $db->update('game1', ['uczb[+]' => $gaibian], ['id' => $uid]);
    }elseif($lx==2){
        if ($player->uczb - $gaibian >= 0){
            return $db->update('game1', ['uczb[-]' => $gaibian], ['id' => $uid]);
        }
    }
    return false;
}

class TaskInfo
{
    public $id;
    public $fromId;
    public $fromType;
    public $toId;
    public $toType;
    public $taskInfoId;
    public $name;
    public $summary;
    public $mode;
    public $level;
    public $maxLevel;
    public $money;
    public $exp;
    public $fromDesc;
    public $fromItem;
    public $fromItemCount;
    public $fromEquip;
    public $fromEquipCount;
    public $toDesc;
    public $toItem;
    public $toItemCount;
    public $toEquip;
    public $toEquipCount;
    public $type;
    public $item;
    public $itemCount;
    public $monster;
    public $monsterCount;
    public $monsterLevel;
    public $lua;
    public $previousTaskIds;
    public $npcOverride;
    public $updateNpcOverride;
    public $isLoop;

    /**
     * @var string 完成任务触发操作
     */
    public $toOperation;

    /**
     * @var string 接受任务触发操作
     */
    public $fromOperation;

    use FromArray;
}

class PlayerTask extends TaskInfo
{
    public $id;
    public $taskId;
    public $taskInfoId;
    public $status;
    public $updatedAt;
}

function getNpcTasks(Medoo $db, array $ids)
{
    $tasks = $db->select('task',
        ['[>]task_info' => ['task_info_id' => 'id']],
        [
            'task.id',
            'task.from_id',
            'task.from_type',
            'task.to_id',
            'task.to_type',
            'task.task_info_id',
            'task_info.name',
            'task_info.mode',
            'task_info.level',
            'task_info.max_level',
            'task_info.money',
            'task_info.exp',
            'task_info.from_desc',
            'task_info.from_item',
            'task_info.from_item_count',
            'task_info.from_equip',
            'task_info.from_equip_count',
            'task_info.from_operation',
            'task_info.to_desc',
            'task_info.to_item',
            'task_info.to_item_count',
            'task_info.to_equip',
            'task_info.to_equip_count',
            'task_info.to_operation',
            'task_info.type',
            'task_info.item',
            'task_info.item_count',
            'task_info.monster',
            'task_info.monster_count',
            'task_info.monster_level',
            'task_info.lua',
            'task_info.npc_override',
            'task_info.update_npc_override',
            'task_info.previous_task_ids',
            'task_info.is_loop',
        ],
        [
            'OR' => [
                'AND #from' => [
                    'task.from_id' => $ids,
                    'task.from_type' => 1
                ],
                'AND #to' => [
                    'task.to_id' => $ids,
                    'task.to_type' => 1
                ],
            ]
        ]);
    return $tasks;
}

function getTaskById(Medoo $db, int $id): TaskInfo
{
    $task = $db->get('task',
        ['[>]task_info' => ['task_info_id' => 'id']],
        [
            'task.id',
            'task.from_id',
            'task.from_type',
            'task.to_id',
            'task.to_type',
            'task.task_info_id',
            'task_info.name',
            'task_info.summary',
            'task_info.mode',
            'task_info.level',
            'task_info.max_level',
            'task_info.money',
            'task_info.exp',
            'task_info.from_desc',
            'task_info.from_item',
            'task_info.from_item_count',
            'task_info.from_equip',
            'task_info.from_equip_count',
            'task_info.from_operation',
            'task_info.to_desc',
            'task_info.to_item',
            'task_info.to_item_count',
            'task_info.to_equip',
            'task_info.to_equip_count',
            'task_info.to_operation',
            'task_info.type',
            'task_info.item',
            'task_info.item_count',
            'task_info.monster',
            'task_info.monster_count',
            'task_info.monster_level',
            'task_info.lua',
            'task_info.npc_override',
            'task_info.update_npc_override',
            'task_info.is_loop',
        ],
        ['task.id' => $id]
    );
    if (empty($task)) {
        return new TaskInfo();
    }
    return TaskInfo::fromArray($task);
}


function getTasksByIds(Medoo $db, array $ids): array
{
    $tasks = $db->select('task',
        ['[>]task_info' => ['task_info_id' => 'id']],
        [
            'task.id',
            'task.from_id',
            'task.from_type',
            'task.to_id',
            'task.to_type',
            'task.task_info_id',
            'task_info.name',
            'task_info.summary',
            'task_info.mode',
            'task_info.level',
            'task_info.max_level',
            'task_info.money',
            'task_info.exp',
            'task_info.from_desc',
            'task_info.from_item',
            'task_info.from_item_count',
            'task_info.from_equip',
            'task_info.from_equip_count',
            'task_info.from_operation',
            'task_info.to_desc',
            'task_info.to_item',
            'task_info.to_item_count',
            'task_info.to_equip',
            'task_info.to_equip_count',
            'task_info.to_operation',
            'task_info.type',
            'task_info.item',
            'task_info.item_count',
            'task_info.monster',
            'task_info.monster_count',
            'task_info.monster_level',
            'task_info.npc_override',
            'task_info.update_npc_override',
            'task_info.is_loop',
        ],
        ['task.id' => $ids]
    );
    return $tasks;
}


/**
 * @param int $id
 * @param int $uid
 * @return bool|mixed
 */
function getPlayerTaskById(Medoo $db, int $id, int $uid): PlayerTask
{
    $arr = $db->get('player_task', '*', ['task_id' => $id, 'uid' => $uid]);
    if (empty($arr)) {
        return new PlayerTask();
    }
    return PlayerTask::fromArray($arr);
}

/**
 * 删除用户指定任务
 *
 * @param Medoo $db
 * @param int $id
 * @param int $uid
 */
function deletePlayerTaskById(Medoo $db, int $id, int $uid)
{
    $db->delete('player_task', ['id' => $id]);
    $db->delete('player_task_condition', ['uid' => $uid, 'player_task_id' => $id]);
}

/**
 * @param array $ids
 * @param int $uid
 * @return bool|int|mixed|string
 */
function countPlayerCompletedTasksByIds(Medoo $db, array $ids, int $uid)
{
    return $db->count('player_task', ['task_id' => $ids, 'uid' => $uid, 'status' => 3]);
}

/**
 * @param array $ids
 * @param int $uid
 * @return bool|mixed
 * @throws \Exception
 */
function getPlayerCompletedTasksByIds(Medoo $db, array $ids, int $uid)
{
    return $db->get('player_task', '*', ['task_id' => $ids, 'uid' => $uid, 'status' => 3]);
}

function useyaopin($ypid, $amount, $uid, Medoo $db)
{
    $player = getPlayerById($db, $uid);
    if ($player->hp <= 0){
        return false;
    }

    $ret = deleyaopin($db, $ypid, $amount, $player->id);
    if ($ret) {
        $yaopin = getMedicine($db, $ypid);
        // 处理药品效果
        foreach ($yaopin->effects as $effect) {
            $currentHpc = $player->maxhp - $player->hp;
            if ($effect->isColumn && $effect->column == 'hp') {
                if ($effect->effectType == 1) {
                    $hpc = $player->maxhp * $effect->amount;
                } else {
                    $hpc = $effect->amount;
                }
                $hpc = $hpc > $currentHpc ? $currentHpc : $hpc;
                addplayersx('hp', $hpc, $uid, $db);
                $player->hp += $hpc;
                continue;
            }

            if ($effect->isCustom) {
                if ($effect->customEffectType == 1) {
                    // 删除已存在的效果
                    $db->delete('player_effects', ['uid' => $player->id, 'column' => $effect->column]);
                    // 添加新的效果
                    $db->insert('player_effects', [
                        'uid' => $player->id,
                        'column' => $effect->column,
                        'amount' => $effect->amount,
                        'is_column' => 0,
                        'is_temporary' => $effect->isTemporary,
                        'duration' => $effect->duration,
                        'end_at' => $effect->isTemporary ? date('Y-m-d H:i:s', time() + $effect->duration) : null,
                        'desc' => $effect->info,
                    ]);
                } else {
                    $db->delete('player_effects', ['uid' => $player->id, 'column' => $effect->column]);
                }
                continue;
            }
        }
    }
    return $ret;
}

function getPlayerSkillById(int $id, int $uid, Medoo $db)
{
    $skill = $db->get('player_skill', [
        '[>]skills' => ['skill_id' => 'id']
    ], [
        'player_skill.id',
        'player_skill.skill_id',
        'player_skill.level',
        'player_skill.score',
        'player_skill.max_score',
        'skills.name',
        'skills.manual_id',
        'skills.equip_type',
        'skills.tiaoxi',
    ], [
        'player_skill.uid' => $uid,
        'player_skill.id' => $id,
    ]);

    return $skill;
}

/**
 * @param $uid
 * @param $pid
 * @param Medoo $db
 * @return bool
 * @throws \Exception
 */
function addPlayerPF($uid, $pid, Medoo $db)
{
    $pf = getplayerpeifang($uid, $pid, $db);
    if (!empty($pf)) {
        throw new \Exception(sprintf('配方 %s 已学习，无法重复', $pf['name']));
    }
    $pf = getpeifang($pid, $db);

    $res = $db->insert('player_peifang', [
        'name' => $pf['name'],
        'uid' => $uid,
        'proficiency' => 0,
        'peifang_id' => $pf['id'],
    ]);
    if (!$res) {
        throw new \Exception(sprintf('学习配方 %s 失败', $pf['name']));
    }
    return true;
}


class Mqy{
    public $qyname;
    public $qyid;
    public $mid;

    use FromArray;
}

/**
 * @param $qyid
 * @param Medoo $db
 * @return mqy
 */
function getqy($qyid, Medoo $db): Mqy
{
    $qy = $db->get('qy', '*', ['qyid' => $qyid]);
    return Mqy::fromArray($qy);
}

function getqy_all(Medoo $db)
{
        return $db->select('qy', '*', ['ORDER' => ['qyid' => 'ASC']]);
}

class GameConfig
{
    use FromArray;

    public $firstmid;
}

function getgameconfig(Medoo $db, array $keys, bool $oneRecord = false): array
{
    if (empty($keys)) {
        return [];
    }
    if ($oneRecord) {
        $ret = $db->get('game_config', '*', ['k' => $keys[0]]);
        if (empty($ret)) {
            return [];
        }
        return $ret;
    }
    return $db->select('game_config', '*', ['k' => $keys]);
}

class Club
{
    use FromArray;

    var $clubname;
    var $clubid;
    var $clublv;
    var $clubexp;
    var $clubno1;
    var $clubinfo;
    var $clubyxb;
    var $clubczb;
}

function getclub($clubid, Medoo $db)
{
    $columns = $db->get('club', '*', ['clubid' => $clubid]);
    if (empty($columns)) {
        return new Club();
    }
    return Club::fromArray($columns);
}

function getclub_all(Medoo $db)
{
    return $db->select('club', '*');
}

class ClubPlayer
{
    use FromArray;

    var $clubid;
    var $uid;
    var $sid;
    var $uclv;
}

function getclubplayer_once(Medoo $db, $uid)
{
    $columns = $db->get('clubplayer', '*', ['uid' => $uid]);
    if (empty($columns)) {
        return false;
    }
    return ClubPlayer::fromArray($columns);
}
