<?php $this->layout('layout', ['pvp' => $pvp ?? [], 'tupo' => $tupo ?? null]) ?>

<?php $this->insert('includes/message'); ?>

<div class="flex flex-col">
    <div><span class="color-gray">[</span> <span class=""><?=$item->uiName?></span> <span class="color-gray">]</span></div>
    <?php if (!$item->isBound):?>
    <?php endif;?>
    <?php if ($item->type == 2):?>
        等级: <?=$zhuangbei->level?><br/>
        价格: <?=$item->price?><br/>
        <?php if (!empty($zhuangbei->sex)):?>
            性别: <?=$zhuangbei->sex == 1 ? '男': '女'?><br/>
        <?php endif;?>
        类型: <?=$tool?><br/>
        <?php if (!empty($zhuangbei->manualId)):?>
            职业: <span class="color-blue"><?=$manuals[$zhuangbei->manualId]?></span><br/>
        <?php endif;?>
        <span class="<?=$item->isBound ? 'color-red' : 'color-green'?>">绑定: <?=$item->isBound ? '是' : '否'?></span><br/>
        <?php if (!empty($zhuangbei->info)):?>
        <?=$zhuangbei->info?><br/>
        <?php endif;?>
        基础属性：<br/>
        <?php foreach ($attributes as $k => $v): ?>
            <?php if (isset($zhuangbei->$k) && $zhuangbei->$k > 0) :?>
                <div class="color-green"><?=$v?>: <?=$zhuangbei->$k?></div>
            <?php endif;?>
        <?php endforeach; ?>
        <?php if (!empty($zhuangbei->keywords)):?>
            特殊属性：<br/>
            <?php foreach ($zhuangbei->keywords as $v):?>
                <div class="color-red"><?=$v['desc']?></div>
            <?php endforeach;?>
        <?php endif;?>
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
        <?=$item->info?>
    <?php endif;?>

    <?php if (!empty($package)):?>
        <p class="mt-2">使用后可获得：</p>
        <?php foreach ($package as $v):?>
            <div class="ml-4">- <span class="<?=$v['class']?>"><?=$v['name']?></span></div>
        <?php endforeach;?>
    <?php endif;?>

    <?php $this->insert('includes/gonowmid');?>
</div>