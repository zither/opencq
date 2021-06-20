<?php $this->layout('layout', ['pvp' => $pvp ?? [], 'tupo' => $tupo ?? null]) ?>

<?php $this->insert('includes/message'); ?>

<div class="flex flex-col">
    <div><span class="color-gray">[</span> <span class="<?=$yguaiwu->type == 3 ? 'color-red font-bold': ''?>"><?=$yguaiwu->name?></span> <span class="color-gray">]</span></div>
    等级: <?=$yguaiwu->level?><br/>
    生命: <?=$guaiwu->hp?>/<?=$guaiwu->maxhp?><br/>
    <?=$yguaiwu->info?><br/>
    <?php if ($canBattle):?>
        <div class="">
            <a class="" href="<?=$this->_l("cmd=begin-pve&gid=%d", $guaiwu->id)?>">攻击<?=$yguaiwu->name?></a>
        </div>
    <?php endif;?>
    <div class="">
        <span class="font-bold">攻击: <?=$guaiwu->wugong?> 防御: <?=$guaiwu->wufang?></span><br/>
        <span class="font-bold">法攻: <?=$guaiwu->fagong?> 法御: <?=$guaiwu->fafang?></span><br/>
    </div>
    <?php if (!empty($daoju)): ?>
    <div class="">
        <div>-</div>
        掉落:
        <?php foreach ($daoju as $k => $v):?>
            <a class="" href="<?=$this->_l('cmd=djinfo&djid=%d', $v['item_id'])?>"><?=$v['item_name']?></a>
            <?php if ($k + 1 < count($daoju)):?>
            ,
            <?php endif;?>
        <?php endforeach;?>
        <div>-</div>
    </div>
    <?php endif; ?>
    <?php $this->insert('includes/gonowmid');?>
</div>

