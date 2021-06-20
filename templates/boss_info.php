<?php $this->layout('layout', ['pvp' => $pvp ?? [], 'tupo' => $tupo ?? null]) ?>

<div class="flex flex-col">
    <div><span class="text-gray-600">[</span> <span class="font-bold"><?=$boss->bossname?></span> <span class="text-gray-600">]</span></div>
    <?=$boss->bossinfo?><br/>
    <div class="mt-4">
        <a class="mr-2 border border-gray-400 px-2 rounded" href="?cmd=<?=$pvb?>">战斗</a>
    </div>
    <?php $this->insert('includes/gonowmid', ['gonowmid' => $gonowmid]);?>
</div>

