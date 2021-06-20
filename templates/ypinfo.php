<?php $this->layout('layout', ['pvp' => $pvp ?? [], 'tupo' => $tupo ?? null]) ?>

<?php $this->insert('includes/message'); ?>

<div class="flex flex-col">
    <div>
        <span class="text-gray-600">[</span>
        <span class="font-bold"><?=$yaopin->name?></span>
        <span class="text-gray-600">]</span>
    </div>
    <?php if ($playerYaopin->amount > 0):?>
        数量：<?=$playerYaopin->amount?: 0?><br/>
    <?php endif;?>
    <?php if (!$yaopin->isBound):?>
        价格：<?=$yaopin->price?>金币<br/>
    <?php endif; ?>
    <span class="<?=$yaopin->isBound ? 'color-red' : 'color-green'?>">绑定：<?=$yaopin->isBound ? '是' : '否'?></span><br/>
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
    <?php if ($has_it):?>
        -<br>
        <a href="?cmd=<?=$useyp?>">使用<?=$yaopin->name?></a><br>
        -<br>
    <?php endif;?>

    <?php $this->insert('includes/gonowmid');?>
</div>