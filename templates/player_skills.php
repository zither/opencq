<?php $this->layout('layout', ['pvp' => $pvp ?? [], 'tupo' => $tupo ?? null]) ?>

<?php $this->insert('includes/message'); ?>

<div class="flex flex-col">
    <div class="my-2">
        <?php if (!isset($type) || $type == 0):?>
            全部
        <?php else: ?>
            <a href="<?=$this->_l('cmd=player-skills')?>">全部</a>
        <?php endif?>
         -
        <?php if (!isset($type) || $type != 1):?>
            <a href="<?=$this->_l('cmd=player-skills&type=1')?>">物攻</a>
        <?php else: ?>
            物攻
        <?php endif?>
        -
        <?php if (!isset($type) || $type != 2):?>
            <a href="<?=$this->_l('cmd=player-skills&type=2')?>">法攻</a>
        <?php else: ?>
            法攻
        <?php endif?>
        -
        <?php if (!isset($type) || $type != 3):?>
            <a href="<?=$this->_l('cmd=player-skills&type=3')?>">辅助</a>
        <?php else: ?>
            辅助
        <?php endif?>
    </div>
    <div>
        -<br>
        <ul>
            <?php foreach ($skills as $k => $v):?>
                <li>
                    [<?=$k+1?>] <a href="<?=$this->_l('cmd=player-skill-info&id=%d', $v['id'])?>">(<?=$v['ui_level']?>)<?=$v['name']?></a>
                </li>
            <?php endforeach;?>
        </ul>
        -<br>
    </div>

    <?php $this->insert('includes/gonowmid', ['show_prev' =>  false]);?>
</div>