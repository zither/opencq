<?php $this->layout('layout', ['pvp' => $pvp ?? [], 'tupo' => $tupo ?? null]) ?>

<?php $this->insert('includes/message'); ?>

<div class="flex flex-col">
    <div class="">
        <?=$this->getVipName($player)?><br/>
        等级: <?=$player->level?><br />
        职业: <?=$player->sex == 1? '男' : '女'?> . <?=$player->playerManualName?><br/>
        经验: <?=$player->exp?>/<?=$player->maxExp?><br/>
        生命值: <?=$player->hp?>/<?=$player->maxhp?><br/>
        魔法值: <?=$player->mp?>/<?=$player->maxmp?><br/>
        神力: <?=$player->baqi?><br/>
        物攻: <?=$player->wugong?><br/>
        法攻: <?=$player->fagong?><br/>
        物防: <?=$player->wufang?><br/>
        法防: <?=$player->fafang?><br/>
        命中: <?=$player->mingzhong?><br/>
        闪避: <?=$player->shanbi?><br/>
        暴击: <?=$player->baoji?><br/>
        抗暴: <?=$player->shenming?><br/>
    </div>
    <?php $this->insert('includes/gonowmid');?>
</div>