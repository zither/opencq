<?php $this->layout('layout', ['pvp' => $pvp ?? [], 'tupo' => $tupo ?? null]) ?>

<?php $this->insert('includes/message'); ?>

<div class="flex flex-col">
    <?php if ($log['status'] == -3):?>
        怪物已经被其他人攻击了！<br/>
        请少侠练习一下手速哦
    <?php elseif ($log['status'] == -1): ?>
        对方无法跟上你逃跑的脚步，愤怒的咆哮<br/>
    <?php elseif ($log['status'] == 1): ?>
        <span class="color-green">你获得了胜利。</span><br/>
        <?php foreach ($defenders as $k => $v): ?>
            <div class="">
                <?=$v?>战败了。<br/>
                <?php if (isset($notes[$k])) :?>
                    <?php foreach ($notes[$k] as $loots) :?>
                        <?=implode('', $loots)?>
                    <?php endforeach;?>
                <?php endif; ?>
            </div>
            <hr class="hr" align="left"/>
        <?php endforeach;?>
    <?php elseif ($log['status'] == 0): ?>
        <span class="color-red">你战败了。</span><br/>
        请少侠重来。
    <?php elseif($log['status'] == -2) :?>
        你已经趟地上了，再来过吧。
    <?php endif;?>
    <div class="inline-block my-4">
        <a href="<?=$this->_l('cmd=delete-pve-log&id=%d', $log['id'])?>">返回游戏</a>
    </div>
</div>

