<?php

namespace Xian\Player;

class Effects
{
    /**
     * 增加角色属性
     */
    const TYPE_INC_PLAYER_ATTRIBUTE = 1;

    /**
     * 减少角色属性
     */
    const TYPE_SUB_PLAYER_ATTRIBUTE = 2;

    /**
     * 增加角色效果
     */
    const TYPE_ADD_PLAYER_EFFECT = 3;

    /**
     * 移除角色效果
     */
    const TYPE_REMOVE_PLAYER_EFFECT = 4;

    /**
     * 增加角色效果的值
     */
    const TYPE_INC_PLAYER_EFFECT_VALUE = 5;

    /**
     * 减少角色效果的值
     */
    const TYPE_SUB_PLAYER_EFFECT_VALUE = 6;

    /**
     * 增加角色效果的持续时间
     */
    const TYPE_INC_PLAYER_EFFECT_DURATION = 7;

    /**
     * 减少角色效果的持续时间
     */
    const TYPE_SUB_PLAYER_EFFECT_DURATION = 8;
}