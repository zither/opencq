<?php

namespace Xian\Object;

use Medoo\Medoo;

class Location
{
    /**
     * @var int 地点编号
     */
    public $id;

    /**
     * @var string 地点名称
     */
    public $name;

    /**
     * @var string 地点描述
     */
    public $info;

    /**
     * @var string
     */
    public $monsterIds;

    /**
     * @var string
     */
    public $npcIds;

    /**
     * @var int 上链接
     */
    public $up;

    /**
     * @var int 下链接
     */
    public $down;

    /**
     * @var int 左链接
     */
    public $left;

    /**
     * @var int 右链接
     */
    public $right;

    /**
     * @var string 怪物刷新时间
     */
    public $lastRefreshTime;
    /**
     * @var int 怪物刷新时间间隔
     */
    public $refreshDuration;

    /**
     * @var int 地区编号
     */
    public $areaId;

    /**
     * @var string 玩家走路信息
     */
    public $playerInfo;

    /**
     * @var int pvp 战斗编号
     */
    public $ispvp;

    /**
     * @var string 搜寻物质编号
     */
    public $resources;

    /**
     * @var int 灵气值
     */
    public $lingqi;

    /**
     * @var int 进入条件
     */
    public $enterCondition;

    /**
     * @var string 地图摆件列表
     */
    public $ornaments;

    use FromArrayTrait;

    public static function get(Medoo $db, int $id)
    {
        $arr = $db->get('mid', [
            'mid(id)',
            'mname(name)',
            'midinfo(info)',
            'mgid(monsterIds)',
            'mnpc(npcIds)',
            'mup(up)',
            'mdown(down)',
            'mleft(left)',
            'mright(right)',
            'mqy(areaId)',
            'mgtime(lastRefreshTime)',
            'ms(refreshDuration)',
            'playerinfo(playerInfo)',
            'ispvp',
            'resources',
            'lingqi',
            'enter_condition',
            'ornaments'
        ], ['mid' => $id]);
        if (empty($arr)) {
            return new self();
        }
        return self::fromArray($arr)->withDatabase($db);
    }
}