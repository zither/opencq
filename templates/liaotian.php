<?php $this->layout('layout', ['pvp' => $pvp ?? [], 'tupo' => $tupo ?? null]) ?>

<?php $this->insert('includes/message'); ?>

<div class="flex flex-col">
    <div class="">
        <span class="color-gray">[</span><span>消息</span><span class="color-gray">] <a class='ml-2' href='<?=$this->_l('cmd=liaotian&type=%d', $type)?>'>刷新</a></span>
    </div>
    <div class="">
        <?php foreach ($messages as $v):?>
            <?php if ($v['type'] == 1):?>
                [<span class="color-red">系统</span>] <?=$v['content']?><br/>
            <?php elseif ($v['type'] == 2):?>
                [<span class="color-blue">消息</span>] <?=$v['content']?><br/>
            <?php elseif ($v['type'] == 3):?>
                [<span class="color-green">队伍</span>] <?=$v['content']?><br/>
            <?php endif;?>
        <?php endforeach;?>
    </div>
<?php $this->insert('includes/gonowmid', ['show_prev' => false]);?>
</div>