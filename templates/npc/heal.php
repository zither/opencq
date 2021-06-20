<?php $this->layout('layout', ['pvp' => $pvp ?? [], 'tupo' => $tupo ?? null]) ?>

<?php $this->insert('includes/message'); ?>

<div class="flex flex-col">
    <p class="font-bold"><?=$npc->name?></p>
    <p>性别: <?=$npc->sex?></p>
    <p><?=$npc->info?></p>
    <div class="my-2">
        <a href="?cmd=<?=$cmd?>">生命恢复需要<?=$xiaohao?>金币</a>(没有金币不收费)<br/>
    </div>
    <?php $this->insert('includes/gonowmid');?>
</div>

