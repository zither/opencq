<?php $this->layout('layout', ['pvp' => $pvp ?? [], 'tupo' => $tupo ?? null]) ?>

<?php $this->insert('includes/message'); ?>

<div class="flex flex-col">
    <p class="font-bold"><?=$npc->name?></p>
    <p>性别:<?=$npc->sex?></p>
    <p><?=$npc->info?></p>
    <div class="mt-2">
        <div class="mb-1">
            <ul class="">
                <li><a class="" href="?cmd=<?=$free_cmd?>">打听</a></li>
                <?php foreach ($questions as $q): ?>
                    <li ><a class="" href="?cmd=<?=$q['cmd']?>"><?=$q['content']?></a></li>
                <?php endforeach;?>
            </ul>
        </div>
        <?php if ($this->hasMessage('conversation')): ?>
            <?php foreach ($this->getMessage('conversation') as $message): ?>
                <div class="mt-2">
                    <span class="font-bold"><?=$npc->name?></span>: <?=$message?>
                </div>
            <?php endforeach;?>
        <?php endif;?>
    </div>
    <?php $this->insert('includes/gonowmid');?>
</div>