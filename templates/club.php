<?php $this->layout('layout', ['pvp' => $pvp ?? [], 'tupo' => $tupo ?? null]) ?>

<div class="flex flex-col">
    <div>
        <span class="text-gray-600">[ </span>
        <span class="font-bold">门派 <?=$club->clubname ?? ''?></span>
        <span class="text-gray-600"> ]</span>
    </div>
    <div class="">
        <?php if (isset($renzhi_players)) : ?>
            =========选择任职人员=========<br/>
            <?php foreach ($renzhi_players as $v) :?>
                <a href='?cmd=<?=$v['info_link']?>'><?=$v['name']?></a><br/>
            <?php endforeach;?>
        <?php elseif (!empty($club)) : ?>
            创建者:<a href="?cmd=<?=$cbosscmd?>" ><?=$cboss->name?></a><br/>
            门派资金:金币[<?=$club->clubyxb?>] 元宝[<?=$club->clubczb?>]<br/>
            建设度:<?=$club->clubexp?><br/>
            门派介绍:<br/><?=$club->clubinfo?><br/>
            <?php if ($clubplayer && $clubplayer->uid && $is_member) :?>
                <div class="">
                <?php if($clubplayer->uclv == 1): ?>
                    <a class="mr-2" href='?cmd=<?=$renzhicmd?>'>任职</a>
                    <a href='?cmd=<?=$outclubcmd?>'>解散</a><br/>
                <?php else :?>
                    <a href='?cmd=<?=$outclubcmd?>'>判出</a><br/>
                <?php endif;?>
                </div>
            <?php else: ?>
                <div class="">
                    <?php if (!isset($has_club) || !$has_club): ?>
                        <a href='?cmd=<?=$joincmd?>'>申请加入</a><br/>
                    <?php endif;?>
                </div>
            <?php endif;?>
            <?php if (!empty($caozuo)): ?>
                <div>
                    <?php foreach ($caozuo as $v): ?>
                        <a href='?cmd=<?=$v['cmd']?>'>任职<?=$v['title']?></a><br/>
                    <?php endforeach;?>
                </div>
            <?php endif;?>
            <div class="" ><a href="?cmd=<?=$clublist?>">门派列表</a></div>
            门派成员：<br/>
            <?php foreach($members as $v):?>
                <a href='?cmd=<?=$v['info_link']?>'>[<?=$v['chenghao']?>]<?=$v['name']?></a><br/>
            <?php endforeach;?>
        <?php else: ?>
            你现在还没有门派呢！<br/>
            <a href="?cmd=<?=$clublist?>">加入门派</a>
        <?php endif; ?>
    </div>

    <?php $this->insert('includes/message'); ?>
    <?php $this->insert('includes/gonowmid', ['gonowmid' => $gonowmid]);?>
</div>