<?php $this->layout('layout', ['pvp' => $pvp ?? [], 'tupo' => $tupo ?? null]) ?>

<div class="flex flex-col">
    <p class="font-bold"><?=$npc->name?></p>
    <p>性别:<?=$npc->sex?></p>
    <p><?=$npc->info?></p>
    <?php if (!empty($tasks)):?>
    <div class="mt-2">
        <?php foreach ($tasks as $task): ?>
            <div >
                <img class="inline-block pb-1" src="images/<?=$task['image']?>.gif" /> <a href="?cmd=<?=$task['cmd']?>"><?=$task['name']?></a>  <span class="color-gray">(<?=$task['type']?>)</span>
            </div>
        <?php endforeach; ?>
    </div>
    <?php endif;?>
    <?php if (!empty($functions)): ?>
    <div class="" >
        -<br>
        <?php foreach ($functions as $f): ?>
            <?php if (isset($f['insert'])): ?>
                <div><?php $this->insert($f['insert']); ?></div>
            <?php endif; ?>
            <div class=""><a class="" href="?cmd=<?=$f['cmd']?>"><?=$f['text']?></a></div>
        <?php endforeach; ?>
        -<br>
    </div>
    <?php endif; ?>
    <?php $this->insert('includes/gonowmid');?>
</div>

