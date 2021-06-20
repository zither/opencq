<?php $this->layout('layout', ['pvp' => $pvp ?? [], 'tupo' => $tupo ?? null]) ?>

<?php $this->insert('includes/message'); ?>

<div class="flex flex-col">
    <div class="">
        <span class="color-gray">[</span><span class="">战斗策略</span><span class="color-gray">]</span>
    </div>
    <?php if ($is_bound): ?>
        <div class="color-red">帐号未认证，入群认证后即可使用策略功能。</div>
    <?php else: ?>
        <div class="">
            <ul>
                <?php foreach ($conditions as $k => $v):?>
                    <li class="">
                        <?=$k + 1?>: <?=$v['desc']?>
                        <?php if ($k > 0) :?>
                        <a href="<?=$this->_l('cmd=up-combat-condition&id=%d', $v['id'])?>">上移</a>
                        <?php endif;?>
                        <a href="<?=$this->_l('cmd=delete-combat-condition&id=%d', $v['id'])?>">删除</a>
                    </li>
                <?php endforeach;?>
            </ul>
        </div>
        <div>
            <a href="<?=$this->_l('cmd=show-add-combat-condition')?>">新增策略</a>
        </div>
    <?php endif;?>
    <?php $this->insert('includes/gonowmid', ['show_prev' => false]);?>
</div>