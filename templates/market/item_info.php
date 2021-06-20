<?php $this->layout('layout', ['pvp' => $pvp ?? [], 'tupo' => $tupo ?? null]) ?>

<?php $this->insert('includes/message'); ?>

<div class="flex flex-col">
    <div>
        <span class="color-gray">[ </span>
        <span class="<?=$quality_color ?? ''?>"><?=$marketItem['name']?></span>
        <span class="color-gray"> ]</span>
    </div>
    <div>
        <?php if ($item->type == 2):?>
            等级: <?=$zhuangbei->level?><br/>
            <?php if (!empty($zhuangbei->sex)):?>
                性别: <?=$zhuangbei->sex == 1 ? '男': '女'?><br/>
            <?php endif;?>
            类型: <?=$tool?><br/>
            <?php if (!empty($zhuangbei->manualId)):?>
                职业: <?=$manuals[$zhuangbei->manualId]?><br/>
            <?php endif;?>
            <?php if (!empty($zhuangbei->info)):?>
            <?=$zhuangbei->info?><br/>
            <?php endif;?>
            基础属性：<br/>
            <?php foreach ($attributes as $k => $v): ?>
                <?php if (isset($zhuangbei->$k) && ($zhuangbei->$k > 0 || $zhuangbei->{"quality" . ucfirst($k)} > 0)) :?>
                    <div class="color-green">
                        <?=$v?>: <?=floor(($zhuangbei->$k + $zhuangbei->{"quality" . ucfirst($k)}) * (1 + 0.1 * $zhuangbei->qianghua))?>
                    </div>
                <?php endif;?>
            <?php endforeach; ?>
            <?php if (!empty($zhuangbei->quality)):?>
                品质属性: <br/>
                <?php foreach ($attributes as $k => $v): ?>
                    <?php if (!empty($zhuangbei->{"quality" . ucfirst($k)})) :?>
                        <div class="<?=$quality_color?>"><?=$v?>: <?=$zhuangbei->{"quality" . ucfirst($k)}?></div>
                    <?php endif;?>
                <?php endforeach; ?>
            <?php endif;?>
            <?php if (!empty($zhuangbei->keywords)):?>
                特殊属性：<br/>
                <?php foreach ($zhuangbei->keywords as $v):?>
                    <div class="color-red"><?=$v['desc']?></div>
                <?php endforeach;?>
            <?php endif;?>
            装备来源: <br/>
            <div class="color-gray">
                地图: <?=$zhuangbei->sourceLocation ?: '未知'?><br/>
                对象: <?=$zhuangbei->sourceMonster ?: '未知'?><br/>
                玩家: <?=$zhuangbei->sourcePlayer ?: '未知'?><br/>
                时间: <?=$zhuangbei->sourceTimestamp?>
            </div>
        <?php elseif ($item->type == 3):?>
            <ul>
                <?php foreach ($yaopin->effects as $effect): ?>
                    <?php if ($effect->isColumn): ?>
                        <li>
                            <?=sprintf($effect->desc, $effect->amount)?>
                            <?php if ($effect->turns > 1):?>
                                ，持续<?=$effect->turns?>回合
                            <?php endif;?>
                            <?php if (!$effect->isRaw || $effect->turns > 1): ?>
                                (仅在战斗中生效)
                            <?php endif;?>
                        </li>
                    <?php elseif ($effect->isCustom): ?>
                        <li>
                            使用后获得「<span class="text-gray-800 font-bold"><?=$effect->info?></span>」效果
                            <?php if ($effect->isTemporary):?>
                                ，持续时间<?=$effect->duration?>秒
                            <?php endif;?>
                        </li>
                    <?php endif;?>
                <?php endforeach;?>
            </ul>
        <?php else :?>
            <span class="color-green">绑定：<?=$item->isBound ? '是' : '否'?></span><br/>
            <?=$item->info?>
        <?php endif;?>
    </div>
    <?php if (!$is_bound):?>
        <div>
            <form class="mt-2" action="<?=$this->_l('cmd=fangshi-buy-daoju&id=%d', $marketItem['id'])?>" method="POST">
                现有数量: <?=$marketItem['amount']?><br/>
                寄售单价: <?=$marketItem['price']?><br/>
                购买数量：<br/>
                <input class="border border-blue-900" type="number" name="count" value="1"><br/>
                <div class="mt">
                    <button class="mr-2 border border-gray-400 px-2 rounded cursor-pointer">购买</button>
                </div>
            </form>
        </div>
    <?php else:?>
        <div class="color-red">帐号未认证，入群认证后即可正常交易。</div>
    <?php endif;?>

    <?php $this->insert('includes/gonowmid');?>
</div>