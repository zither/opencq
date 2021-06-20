<?php $this->layout('layout', ['pvp' => $pvp ?? [], 'tupo' => $tupo ?? null]) ?>

<?php $this->insert('includes/message'); ?>

<div class="flex flex-col">
    <div class="">
        <span class="text-gray-600">[</span> <span class="font-bold">修炼</span> <span class="text-gray-600">]</span>
    </div>
    <div class="mt-4">
        当前境界: <?=$player->jingjie?><br/>
        当前功法: <?=$player->playerManualName?> - <?=$player->cengci?><br/>
        <?php if (!empty($pingjing)): ?>
             修为瓶颈: 当前
            <?php foreach ($pingjing as $v): ?>
                -><?=$v['sub_name']?>
            <?php endforeach; ?>
            <br />
            瓶颈进度: <?=round(($playerExp / $pingjingExp) * 100, 2)?>%<br/>
            <div>所在地点：<?=$mid['name']?>（<?=\Xian\Helper::lingQiLevel($mid['lingqi'])?>）</div>
            修炼速度: <?=$expRate?> 修为/分钟<br/>
        <?php endif;?>
        <?php if ($player->isMaxManualLevel): ?>
            修炼状态: <span class="">已达到功法最高等级，无法继续修炼</span><br/>
        <?php else: ?>
            ===============<br/>
            修炼时间: <?=$xlsjc?> 分钟<br/>
            修炼收益: <?=$xlexp?> 修为<br/>
            ===============<br/>
            注：单次最多修炼8小时，480分钟
            <?php if ($player->sfxl == 1): ?>
                <br/>修炼中<br/>
                <a href="<?=$this->_l('cmd=endxiulian')?>">结束修炼</a>
            <?php else: ?>
                <br/>
                <a class="mr-2 mt-2" href="<?=$this->_l('cmd=startxiulian')?>">开始修炼</a>
            <?php endif;?>
        <?php endif;?>
    </div>
    <?php if ($player->sfxl != 1): ?>
        <?php $this->insert('includes/gonowmid');?>
    <?php endif;?>
</div>