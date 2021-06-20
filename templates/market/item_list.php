<?php $this->layout('layout', ['pvp' => $pvp ?? [], 'tupo' => $tupo ?? null]) ?>

<?php $this->insert('includes/message'); ?>

<div class="flex flex-col">
    <div class="mt-2">
        <?php if (!isset($type) || $type == 0):?>
            全部
        <?php else: ?>
            <a href="<?=$this->_l('cmd=fangshi-daoju')?>">全部</a>
        <?php endif?>
         -
        <?php if (!isset($type) || $type != 2):?>
            <a href="<?=$this->_l('cmd=fangshi-daoju&type=2')?>">装备</a>
        <?php else: ?>
            装备
        <?php endif?>
        -
        <?php if (!isset($type) || $type != 3):?>
            <a href="<?=$this->_l('cmd=fangshi-daoju&type=3')?>">药品</a>
        <?php else: ?>
            药品
        <?php endif?>
        -
        <?php if (!isset($type) || $type != 1):?>
            <a href="<?=$this->_l('cmd=fangshi-daoju&type=1')?>">道具</a>
        <?php else: ?>
            道具
        <?php endif?>
    </div>
    <div>
        <ul>
            <?php foreach ($items as $k => $v):?>
                <li>
                    [<?=$k+1?>] <a class="<?=$this->getQualityColor($v['quality'])?>" href="<?=$this->_l('cmd=fangshi-item-info&id=%d', $v['id'])?>"><?=$v['name']?></a> <span class="color-gray">x<?=$v['amount']?></span>（单价：<?=$v['price']?>）
                </li>
            <?php endforeach;?>
        </ul>
        <div>
            <?php if (!empty($prev_page)): ?>
                <a href="<?=$this->_l('cmd=fangshi-daoju&type=%d&page=%d', $type, $prev_page)?>">上一页</a>
            <?php endif;?>
            <?php if (!empty($next_page)): ?>
                <a href="<?=$this->_l('cmd=fangshi-daoju&type=%d&page=%d', $type, $next_page)?>">下一页</a>
            <?php endif;?>
        </div>
    </div>
    <?php $this->insert('includes/gonowmid', ['show_prev' => false]);?>
</div>