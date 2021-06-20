<?php $this->layout('layout', ['pvp' => $pvp ?? [], 'tupo' => $tupo ?? null]) ?>

<?php $this->insert('includes/message'); ?>

<div class="flex flex-col">
    <div><span class="color-gray">[</span> <span class="">每日签到</span> <span class="color-gray">]</span></div>
    <div>
        <?php if (!$is_today):?>
            <?php if (empty($gifts)):?>
                <p>你已连续签到<?=empty($item) ? 0 : $item['v']?>日。</p>
            <?php else: ?>
                <p>你已连续签到<?=empty($item) ? 0 : $item['v']?>日，今日签到可获得：</p>
                <ul>
                    <?php foreach ($gifts as $v):?>
                        <li><?=$v['name']?> x<?=$v['amount']?></li>
                    <?php endforeach;?>
                </ul>
            <?php endif;?>
            <p><a href="<?=$this->_l('cmd=activity-do-qiandao')?>">签到</a></p>
        <?php else:?>
            <p>你已连续签到<?=empty($item) ? 0 : $item['v']?>日。</p>
            <p class="color-green">已签到</p>
        <?php endif;?>
    </div>
    <a class="link mr-1" href="<?=$this->_l('cmd=activity-list')?>">返回上级</a> <br/>
    <?php $this->insert('includes/gonowmid', ['show_prev' => false]);?>
</div>