<?php $this->layout('layout', ['pvp' => $pvp ?? [], 'tupo' => $tupo ?? null]) ?>

<?php $this->insert('includes/message'); ?>

<div class="flex flex-col">
    <div class="">
        <span class="color-gray">[</span><span class="">确认升星</span><span class="color-gray">]</span>
    </div>
    <div class="mt-4">
        <div><?=$equip->shengxing > 0 ? "{$equip->shengxing}星" : ''?><?=$equip->uiName ?: $equip->name?></div>
        <div>
            材料：<?=$item->name?> x<?=$amount?>
        </div>
        <div>
            金币：<?=$cost?>
        </div>
        <div>
            <a href="<?=$this->_l('cmd=shengxing&id=%d', $equip->id)?>">确认选择</a>
        </div>
    </div>
</div>

<div class="inline-block my-4">
    <a href="<?=$this->_l('cmd=shengxing-list')?>">返回上级</a>
</div>