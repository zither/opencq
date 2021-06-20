<?php $this->layout('layout', ['pvp' => $pvp ?? [], 'tupo' => $tupo ?? null]) ?>

<?php $this->insert('includes/message'); ?>

<div class="flex flex-col">
    <div>我的背包</div>
    <div>元宝: <?=$player->uczb?></div>
    <div>金币: <?=$player->uyxb?></div>
    <div>负重: <?=$count?>/<?=$player->liftingCapacity?></div>
    <div class="mt-2">
        <a href="<?=$this->_l('cmd=getbagzb')?>">装备</a> | <a href="<?=$this->_l('cmd=getbagdj')?>">道具</a> | 药品
    </div>
    <div>
        <?php if (!empty($yaopin)): ?>
        -<br>
        <ul>
            <?php foreach ($yaopin as $k => $v):?>
                <li>
                    [<?=$k+1?>] <a href="?cmd=<?=$v['info_link']?>"><?=$v['name']?></a> <span class="color-gray">x<?=$v['amount']?></span>
                </li>

            <?php endforeach;?>
        </ul>
        -<br>
        <?php endif;?>
    </div>
    <?php $this->insert('includes/gonowmid');?>
</div>