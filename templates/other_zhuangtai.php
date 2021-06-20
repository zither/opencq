<?php $this->layout('layout', ['pvp' => $pvp ?? [], 'tupo' => $tupo ?? null]) ?>

<?php $this->insert('includes/message'); ?>

<div class="flex flex-col">
    <div class="mt-4">
        <?=$this->getVipName($player)?><br/>
        职业: <span class="color-blue"><?=$player->sex == 1? '男' : '女'?><?=$player->playerManualName?></span><br/>
        等级: <?=$player->level?>级<br/>
        <?php foreach ($tools as $k => $v) : ?>
            <div>
                <?php if (!empty($v)): ?>
                    <span><?=$k?>:</span>
                    <a href="<?=$this->_l("cmd=chakanzb&zbnowid=%d&uid=%d", $v->id, $player->id)?>" class="<?=$v->qualityColor?>"><?=$this->getPlayerEquipName($v)?></a>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
        <?php if (!empty($pet)): ?>
            <div>
                宝宝: <span class="<?=$pet_color?>"><?=$pet_level_text?><?=$pet->name?></span>
            </div>
        <?php endif;?>
        <?php if (!empty($skills)):?>
        <div>
            技能:
            <?php foreach ($skills as $i => $v): ?>
                <?=$v['name']?>(<?=$v['skill_level']?>)
                <?php if ($i + 1 < count($skills)):?>
                   .
                <?php endif;?>
            <?php endforeach; ?>
        </div>
        <?php endif;?>
        <?php if ($player->partyId):?>
            <div>
                队伍:
                <span class="color-blue"><?=$party->name?></span>
                <?php if ($is_leader):?>
                    <span class="color-gray">(队长)</span>
                <?php endif;?>
            </div>
        <?php endif;?>
        <?php if (!$is_self): ?>
            -<br>
            <div class="">
                <a href='?cmd=<?=$pk_link?>'>攻击</a>
                <?php if (!$is_im): ?>
                    <a href='<?=$this->_l('cmd=add-relationship&type=1&tid=%d', $player->id)?>'>加为好友</a>
                <?php else: ?>
                    <a href='<?=$this->_l('cmd=delete-relationship&type=1&tid=%d', $player->id)?>'>删除好友</a>
                <?php endif;?>
            </div>
            -<br>
        <?php endif;?>

    </div>
    <?php $this->insert('includes/gonowmid');?>
</div>