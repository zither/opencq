<?php $this->layout('layout', ['pvp' => $pvp ?? [], 'tupo' => $tupo ?? null]) ?>

<?php $this->insert('includes/message'); ?>

<div class="flex flex-col">
    <div class="">
        <span class="color-gray">[</span><span class="">确认强化</span><span class="color-gray">]</span>
    </div>
    <div class="mt-4">
        <div class="<?=$equip->qualityColor?>"><?=$this->getPlayerEquipName($equip)?></div>
        <?php if ($continue):?>
        <div>
            需要材料:<br/>
            <?php foreach ($items as $item):?>
                <?=$item['ui_name'] ?: $item['name']?> <span class="color-gray">x<?=$item['amount']?></span><br/>
            <?php endforeach;?>
            金币 <span class="color-gray">x<?=$cost?></span><br/>
            成功率: <?=$rate?>%<br/>
        </div>
        <div>
            <a href="<?=$this->_l('cmd=qianghua&id=%d', $equip->id)?>">确认选择</a>
        </div>
        <?php endif;?>
    </div>
</div>

<div class="inline-block my-4">
    <a href="<?=$this->_l('cmd=qianghua-list')?>">返回上级</a>
</div>