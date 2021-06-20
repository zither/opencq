<?php

namespace Xian;

class TimeCost
{
    /**
     * @var float
     */
    protected static $beginTime = 0;

    /**
     * @var float
     */
    protected static $endTime = 0;

    /**
     * @return float
     */
    public static function begin(): float
    {
        static::$beginTime = static::now();
        return static::$beginTime;
    }

    /**
     * @return float
     */
    public static function end(): float
    {
        static::$endTime = static::now();
        return static::$endTime;
    }

    /**
     * 获取时间差
     * @return float
     */
    public static function cost(): float
    {
        return intval((static::$endTime ?: static::now()) - static::$beginTime);
    }

    /**
     * 获取当前时间
     * @return float
     */
    public static function now(): float
    {
        return microtime(true) * 1000;
    }
}