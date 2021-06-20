<?php $this->layout('layout', ['pvp' => $pvp ?? [], 'tupo' => $tupo ?? null]) ?>

<?php $this->insert('includes/message'); ?>

<div class="flex flex-col">
    <div class="">
        <span class="color-gray">[</span><span class="">设置</span><span class="color-gray">]</span>
    </div>
    <div class="">
        <ul>
            <?php for ($i = 1; $i <= 3; $i++):?>
                <li class="">
                    玩家物品键<?=$i?>:
                    <?php if (empty($items[$i])): ?>
                        <a href="<?=$this->_l('cmd=select-shortcut-item&n=%d', $i)?>">未定义</a>
                    <?php else :?>
                        <?=$items[$i]['name']?> <a href="<?=$this->_l('cmd=select-shortcut-item&n=%d', $i)?>">设置</a>
                        <a href="<?=$this->_l('cmd=set-shortcut&type=item&value=0&n=%d', $i)?>">清除</a>
                    <?php endif;?>
                </li>
            <?php endfor;?>
        </ul>
        <ul>
            <?php for ($i = 1; $i <= 3; $i++):?>
                <li>
                    玩家技能键<?=$i?>:
                    <?php if (empty($skills[$i])): ?>
                        <a href="<?=$this->_l('cmd=select-shortcut-skill&n=%d', $i)?>">未定义</a>
                    <?php else :?>
                        <?=$skills[$i]['name']?> <a href="<?=$this->_l('cmd=select-shortcut-skill&n=%d', $i)?>">设置</a>
                        <a href="<?=$this->_l('cmd=set-shortcut&type=skill&value=0&n=%d', $i)?>">清除</a>
                    <?php endif;?>
                </li>
            <?php endfor;?>
        </ul>
    </div>

    <div>
        <p><a href="<?=$this->_l('cmd=logout')?>">退出登录</a> </p>
    </div>
    <?php $this->insert('includes/gonowmid', ['show_prev' => false]);?>
</div>