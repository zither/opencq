<?php $this->layout('layout', ['pvp' => $pvp ?? [], 'tupo' => $tupo ?? null]) ?>

<?php $this->insert('includes/message'); ?>

<div class="flex flex-col">
    <div>
        <span class="color-gray">[ </span>
        <span class=""><?=$item->uiName ?: $item->name?></span>
        <span class="color-gray"> ]</span>
    </div>
    <div>
        <?php if ($item->type == 2):?>
            等级: <?=$zhuangbei->level?><br/>
            类型: <?=$tool?><br/>
            <span class="<?=$item->isBound ? 'color-red' : 'color-green'?>">绑定：<?=$item->isBound ? '是' : '否'?></span><br/>
            <?=$zhuangbei->info?><br/>
            基础属性：<br/>
            <?php foreach ($attributes as $k => $v): ?>
                <?php if ($zhuangbei->$k > 0) :?>
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
            价格：<?=$item->price?>金币<br/>
            <span class="<?=$item->isBound ? 'color-red' : 'color-green'?>">绑定：<?=$item->isBound ? '是' : '否'?></span><br/>
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
            价格：<?=$item->price?>金币<br/>
            <span class="<?=$item->isBound ? 'color-red' : 'color-green'?>">绑定：<?=$item->isBound ? '是' : '否'?></span><br/>
            <?=$item->info?>
        <?php endif;?>
    </div>
    <div>
        <form class="mt-2" action="<?=$this->_l('cmd=do-shop&id=%d', $item->id)?>" method="POST">
            购买数量：<br/>
            <input class="border border-blue-900" type="number" name="count" value="1"><br/>
            <div class="mt">
                <button class="mr-2 border border-gray-400 px-2 rounded cursor-pointer">购买</button>
            </div>
        </form>
    </div>

    <?php $this->insert('includes/gonowmid');?>
</div>