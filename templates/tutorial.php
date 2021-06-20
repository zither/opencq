<?php $this->layout('layout', ['pvp' => $pvp ?? [], 'tupo' => $tupo ?? null]) ?>

<?php $this->insert('includes/message'); ?>

<div class="flex flex-col">
    <div class="">
        <span class="color-gray">[</span><span class="color-red">新手入门</span><span class="color-gray">]</span>
    </div>
    <div class="">
        <p>1.进入游戏后背包中会有三种职业的入职道具，点击使用可以加入对应的职业。</p>
        <p>2.先在银杏村长处领取新手宝石和新手勋章，然后去银杏书店购买学习初级技能。</p>
        <p>3.最低级的武器和衣服可以在银杏野外打鸡获得，新手装备爆率非常高，可以挑选品质高的装备。</p>
        <p>4.穿戴武器和衣服后不要直接打牛(5级)，15级前进入战斗都可以自动满血，推荐直接挂机打猪到10级左右，囤积金创药和练级技能。</p>
        <p>5.在10级左右时把背包中不需要的道具直接卖掉，然后看坊市有没有便宜的高品质10级装备买来穿上，然后可以尝试打牛，把全身装备都提升到10级。</p>
        <p>6.10级装备穿满后可以考虑挂机稻草人和鹿到15级左右，然后转战比奇矿区第一格，依靠战斗回血打蝙蝠获取15级装备，没血了直接逃跑后再进入会自动回满。</p>
        <p>7.15级装备穿满后就可以再回银杏野外挂机毒蜘蛛、食人花等怪物升级。</p>
        <p class="color-red text-sm">提示：挂机升级请选择比人物装备低一阶的怪物，多逛逛坊市。</p>
    </div>
    <?php $this->insert('includes/gonowmid', ['show_prev' => false]);?>
</div>