<?php $this->layout('layout', ['pvp' => $pvp ?? [], 'tupo' => $tupo ?? null]) ?>

<?php $this->insert('includes/message'); ?>

<div class="flex flex-col">
    <div class="">
        <span class="text-gray-600">[</span><span class="">地图传送</span><span class="text-gray-600">]</span>
    </div>
    <div class="">
        安全区：<br/>
        <?php foreach ($cities as $k => $map):?>
            <span class="w-1/4 text-gray-600"><a href="<?=$this->_l('cmd=move-to-mid&mid=%d', $map['teleport'])?>"><?=$map['qyname']?></a></span>
            <?php if ($k + 1 < count($cities)):?>
                .
            <?php endif;?>
        <?php endforeach; ?>
    </div>
    -
    <div class="">
        危险区：<br/>
        <?php foreach ($wildAreas as $map):?>
            <div class="flex">
                <a href="<?=$this->_l('cmd=move-to-mid&mid=%d', $map['teleport'])?>"><?=$map['qyname']?></a> <?=$map['description']?>
            </div>
        <?php endforeach; ?>
    </div>
    -
    <?php $this->insert('includes/gonowmid', ['show_prev' => false]);?>
</div>