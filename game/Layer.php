<?php
namespace Xian;

class Layer
{
    const TYPE_FANREN = 0;
    const TYPE_LIANQI = 1;
    const TYPE_ZHUJI = 2;
    const TYPE_JINDAN = 3;
    const TYPE_YUANSHEN = 4;
    const TYPE_ZHENXIAN = 5;

    /**
     * @var array 境界名称
     */
    protected static  $layerNames = [
        self::TYPE_FANREN => '凡人期',
        self::TYPE_LIANQI => '炼气期',
        self::TYPE_ZHUJI=> '筑基期',
        self::TYPE_JINDAN=> '金丹期',
        self::TYPE_YUANSHEN=> '元神期',
        self::TYPE_ZHENXIAN=> '真仙期',
    ];

    /**
     * 获取境界名称
     * @param int $type
     * @return string
     */
    public static function name(int $type): string
    {
        return isset(self::$layerNames[$type]) ? self::$layerNames[$type] : self::$layerNames[self::TYPE_FANREN];
    }
}