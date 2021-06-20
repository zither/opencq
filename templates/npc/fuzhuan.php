<?php $this->layout('layout', ['pvp' => $pvp ?? [], 'tupo' => $tupo ?? null]) ?>

<div class="flex flex-col">
    <p>昵称:<?=$npc->name?></p>
    <p>性别:<?=$npc->sex?></p>
    <p>信息:<?=$npc->info?></p>
    <div class="my-2">
        <div>========兑换列表========</div>
        <?php foreach ($fuzhuan as $k => $v): ?>
            <div>
                [<?=$k + 1?>]<a class="ml-1" href="?cmd=<?=$v['info_link']?>"><?=$v['name']?></a>
            </div>
        <?php endforeach; ?>
    </div>
    <?php $this->insert('includes/message'); ?>
    <?php $this->insert('includes/gonowmid', ['gonowmid' => $gonowmid]);?>
</div>