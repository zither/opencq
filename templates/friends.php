<?php $this->layout('layout', ['pvp' => $pvp ?? [], 'tupo' => $tupo ?? null]) ?>

<?php $this->insert('includes/message'); ?>

<div class="flex flex-col">
    <div class="">
        <?php if ($type != 1):?>
            <a href="<?=$this->_l('cmd=relationship&type=1')?>">好友</a>
        <?php else: ?>
            好友
        <?php endif?>
        -
        <?php if ($type != 2):?>
            <a href="<?=$this->_l('cmd=relationship&type=2')?>">仇人</a>
        <?php else: ?>
            仇人
        <?php endif?>
        -
        <?php if ($type != 3):?>
            <a href="<?=$this->_l('cmd=relationship&type=3')?>">黑名单</a>
        <?php else: ?>
            黑名单
        <?php endif?>
    </div>
    <div class="">
        -
        <ul>
        <?php foreach ($players as $k => $v): ?>
            <li>[<?=$k + 1?>] <a href='<?=$this->_l("cmd=getplayerinfo&uid=%d", $v['id'])?>'><?=$v['name']?></a><br/></li>
        <?php endforeach; ?>
        </ul>
        -
    </div>
    <?php $this->insert('includes/gonowmid', ['show_prev' => false]);?>
</div>