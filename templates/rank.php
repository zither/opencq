<?php $this->layout('layout', ['pvp' => $pvp ?? [], 'tupo' => $tupo ?? null]) ?>

<?php $this->insert('includes/message'); ?>

<div class="flex flex-col">
    <div><span class="color-gray">[</span> <span class="">排行榜</span> <span class="color-gray">]</span></div>
    -<br>
<!--    <div><a href="">赞助榜</a></div>-->
    <div>
        <a href="<?=$this->_l('cmd=paihang')?>">等级榜</a> . <a href="<?=$this->_l('cmd=fortune-rank')?>">财富榜</a>
    </div>
    -<br>
    <?php $this->insert('includes/gonowmid', ['show_prev' => false]);?>
</div>