<?php $this->layout('layout', ['pvp' => $pvp ?? [], 'tupo' => $tupo ?? null]) ?>

<div class="flex flex-col">
    <div>
        <span class="text-gray-600">[ </span>
        <span class="font-bold">门派天榜</span>
        <span class="text-gray-600"> ]</span>
    </div>
    <ul class="">
        <?php foreach ($clubs as $k => $v) :?>
            <li>[<?=$k+1?>] <a href='?cmd=<?=$v['info_link']?>' ><?=$v['clubname']?></a></li>
        <?php endforeach;?>
    </ul>
    <?php $this->insert('includes/message'); ?>
    <?php $this->insert('includes/gonowmid', ['gonowmid' => $gonowmid]);?>
</div>