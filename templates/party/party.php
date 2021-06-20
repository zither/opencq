<?php $this->layout('layout', ['pvp' => $pvp ?? [], 'tupo' => $tupo ?? null]) ?>

<?php $this->insert('includes/message'); ?>

<div class="flex flex-col">
    <div class="">
        [组队大厅] <a href="<?=$this->_l('cmd=party')?>">刷新</a>
    </div>
    <div class="">
        <?php if (empty($parties)):?>
            -<br/>
            暂时没有队伍。<br/>
            -<br/>
        <?php else:?>
            -<br/>
            <ul>
                <?php foreach ($parties as $k => $v): ?>
                    <li>
                        [<?=$k + 1?>] <span class="color-blue"><?=$v->name?></span>
                        <span class="color-gray">(队长：<?=$v->leaderName?>)</span>
                        <?php if (!$v->isClosed && !$player->partyId):?>
                            <a href="<?=$this->_l('cmd=join-party&party_id=%d', $v->id)?>">申请入队</a>
                        <?php endif;?>
                    </li>
                <?php endforeach; ?>
            </ul>
            -<br/>
        <?php endif;?>
        <?php if (!$player->partyId):?>
        <p><a href="<?=$this->_l('cmd=party-creation-form')?>">创建队伍</a></p>
        <?php endif;?>
    </div>
    <?php $this->insert('includes/gonowmid', ['show_prev' => false]);?>
</div>