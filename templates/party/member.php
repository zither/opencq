<?php $this->layout('layout', ['pvp' => $pvp ?? [], 'tupo' => $tupo ?? null]) ?>

<?php $this->insert('includes/message'); ?>

<div class="flex flex-col">
    <div class="">
        [我的队友] <a href="<?=$this->_l('cmd=party-member')?>">刷新</a>
    </div>
    <div class="">
        -<br/>
        <?php if (empty($members)):?>
            你暂时还没有加入小队。<br/>
        <?php else:?>
            <ul>
                <?php foreach ($members as $k => $v): ?>
                    <li>
                        [<?=$k + 1?>] <a href='<?=$this->_l("cmd=getplayerinfo&uid=%d", $v->uid)?>'><?=$v->name?></a>
                        <span class="color-gray">(<?=$v->uid != $party->uid ? $v->statusText : '队长'?>)</span>
                        <?php if ($is_leader ?? false):?>
                            <?php if($v->status && $v->uid != $party->uid):?>
                                <a href='<?=$this->_l("cmd=remove-party-member&member_id=%d", $v->id)?>'>移除</a>
                            <?php elseif ($v->status == 0):?>
                                <a href='<?=$this->_l("cmd=allow-party-member&member_id=%d", $v->id)?>'>通过</a>
                                <a href='<?=$this->_l("cmd=reject-party-member&member_id=%d", $v->id)?>'>拒绝</a>
                            <?php endif;?>
                        <?php elseif ($v->uid == $player->id):?>
                            <?php if ($v->status == 2):?>
                                <a href='<?=$this->_l("cmd=unfollow-leader&member_id=%d", $v->id)?>'>取消跟随</a>
                            <?php endif; ?>
                            <?php if ($v->status == 1):?>
                                <a href='<?=$this->_l("cmd=follow-leader&member_id=%d", $v->id)?>'>跟随</a>
                            <?php endif; ?>
                            <a href='<?=$this->_l("cmd=leave-party&member_id=%d", $v->id)?>'>退出</a>
                        <?php endif;?>

                    </li>
                <?php endforeach;?>
            </ul>
        <?php endif;?>
        -<br/>
        <?php if ($is_leader ?? false):?>
            <?php if ($party->isClosed):?>
                <a href="<?=$this->_l('cmd=toggle-party-request&party_id=%d', $party->id)?>">开启申请</a>
            <?php else: ?>
                <a href="<?=$this->_l('cmd=toggle-party-request&party_id=%d', $party->id)?>">关闭申请</a>
            <?php endif;?>
            <a href="<?=$this->_l('cmd=delete-party&party_id=%d', $party->id)?>">解散队伍</a>
        <?php endif;?>
        <a href="<?=$this->_l('cmd=party')?>">组队大厅</a>
    </div>
    <?php $this->insert('includes/gonowmid', ['show_prev' => false]);?>
</div>