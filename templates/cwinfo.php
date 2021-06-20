<?php $this->layout('layout', ['pvp' => $pvp ?? [], 'tupo' => $tupo ?? null]) ?>

<?php $this->insert('includes/message'); ?>

<div class="flex flex-col">
    <div>
        <span class="color-gray">[</span>
        <span class=""><?=$chongwu->isBorn ? $chongwu->name : '灵兽蛋'?></span>
        <span class="color-gray">]</span>
    </div>
    <?php if ($chongwu->isBorn) : ?>
        等级: <?=$chongwu->level?><br/>
        生命值: <?=$chongwu->hp?>/<?=$chongwu->maxhp?><br/>
        魔法值: <?=$chongwu->mp?>/<?=$chongwu->maxmp?><br/>
        神力: <?=$chongwu->baqi?><br/>
        物攻: <?=$chongwu->wugong?><br/>
        物防: <?=$chongwu->wufang?><br/>
        法攻: <?=$chongwu->fagong?><br/>
        法防: <?=$chongwu->fafang?><br/>
        命中: <?=$chongwu->mingzhong?><br/>
        闪避: <?=$chongwu->shanbi?><br/>
        暴击: <?=$chongwu->baoji?><br/>
        抗暴: <?=$chongwu->shenming?><br/>
    <?php else : ?>
        <div>一个奇怪的蛋，从内部发出一股淡淡的<?=$chongwu->color?>色柔光。</div>
        <div class='mt-4'>
            <a class="" href="<?=$this->_l('cmd=show-ronghe&id=' . $chongwu->id)?>">融合元魂</a>
            <a class="ml-2" href="<?=$this->_l('cmd=fh-chongwu&id=' . $chongwu->id)?>">孵化灵兽</a>
        </div>
    <?php endif;?>

    <?php $this->insert('includes/gonowmid');?>
</div>