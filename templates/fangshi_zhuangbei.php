<?php $this->layout('layout', ['pvp' => $pvp ?? [], 'tupo' => $tupo ?? null]) ?>

<div class="flex flex-col">
    <div class="">
        <span class="text-gray-600">[ </span> <span class="font-bold">坊市</span> <span class="text-gray-600"> ]</span>
    </div>
    <div class="mt-4">
        <div class="my-2">【<a href="?cmd=<?=$daoju?>">道具</a>|装备】</div>
        <?php foreach ($zhuangbei as $v): ?>
            <div>
                <a href="?cmd=<?=$v['info_link']?>"><?=$v['zbname']?><?=$v['qianghua'] > 0 ? "+{$v['qianghua']}" : ''?></a>价格:<?=$v['pay']?><a href="?cmd=<?=$v['goumaizb']?>">购买</a>
            </div>
        <?php endforeach;?>
    </div>
    <?php $this->insert('includes/message'); ?>
    <?php $this->insert('includes/gonowmid', ['gonowmid' => $gonowmid]);?>
</div>