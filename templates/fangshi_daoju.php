<?php $this->layout('layout', ['pvp' => $pvp ?? [], 'tupo' => $tupo ?? null]) ?>

<div class="flex flex-col">
    <div class="">
        <span class="text-gray-600">[ </span> <span class="font-bold">坊市</span> <span class="text-gray-600"> ]</span>
    </div>
    <div class="mt-4">
        <div class="my-2">【道具|<a href="?cmd=<?=$zhuangbei?>">装备</a>】</div>
        <?php foreach ($daoju as $v): ?>
            <div>
                <a href="?cmd=<?=$v['info_link']?>"><?=$v['djname']?>x<?=$v['djcount']?></a>单价:<?=$v['pay']?><a href="?cmd=<?=$v['goumaidj1']?>">购买1</a><a href="?cmd=<?=$v['goumaidj5']?>">购买5</a> <a href="?cmd=<?=$v['goumaidj10']?>">购买10</a>
            </div>
        <?php endforeach;?>
    </div>
    <?php $this->insert('includes/message'); ?>
    <?php $this->insert('includes/gonowmid', ['gonowmid' => $gonowmid]);?>
</div>