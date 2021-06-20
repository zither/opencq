<?php $this->layout('layout', ['pvp' => $pvp ?? [], 'tupo' => $tupo ?? null]) ?>

<?php $this->insert('includes/message'); ?>

<div class="flex flex-col">
    <div><span class="color-gray">[</span> <span class="color-red">礼包</span> <span class="color-gray">]</span></div>
    <div class="mt mb">
        <ul>
            <li><a href="<?=$this->_l('cmd=activity-qiandao')?>">每日签到</a></li>
            <li><a href="<?=$this->_l('cmd=activity-redeem-code&label=%s&code=%s', '<span class="color-red">新手礼包</span>', 'RC01_NEWBIE_GIFTS')?>">新手礼包</a></li>
            <li><a href="<?=$this->_l('cmd=activity-redeem-code')?>">使用兑换码</a></li>
        </ul>
    </div>
    <?php $this->insert('includes/gonowmid', ['show_prev' => false]);?>
</div>