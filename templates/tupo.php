<?php $this->layout('layout', ['pvp' => $pvp ?? [], 'tupo' => $tupo ?? null]) ?>

<div class="flex flex-col">
    <div>
        <span class="text-gray-600">[</span>
        <span class="font-bold">======突破======</span>
        <span class="text-gray-600">]</span>
    </div>
    <div class="mt-4">
        当前境界：<?=$player->jingjie?><br/>
        当前功法：<?=$player->playerManualName?> - <?=$player->cengci?><br/>
        <div>当前瓶颈：<?=$rates['pingjing']['sub_name']?></div>
        <div>所在地点：<?=$mid['name']?>（<?=\Xian\Helper::lingQiLevel($mid['lingqi'])?>）</div>
    </div>
    <?php if ($can_tupo): ?>
        <div>环境：+<?=$rates['env']?></div>
        <div>悟性：+<?=$rates['wuxing']?></div>
        <div>心境：+<?=$rates['xinjing']?></div>
        <div>境界：+<?=$rates['jieduan']?></div>
        <?php if (isset($rates['effects'])) :?>
            <div class="text-red-500 font-bold">神魂不稳：<?=$rates['effects']?></div>
        <?php endif;?>
        <?php if (isset($rates['yaopin'])) :?>
            <div class="text-green-500 font-bold"><?=$rates['yaopin']['notes']?>：+<?=$rates['yaopin']['v']?></div>
        <?php endif;?>
        <div>突破成功率：<?=$rates['success']?>%</div>
        <div class='my-4'>
            <a class="px-2 py-1 rounded border border-gray-400" href="?cmd=<?=$tupocmd?>">突破</a>
        </div>
    <?php endif; ?>
    <?php $this->insert('includes/message'); ?>
    <?php $this->insert('includes/gonowmid', ['gonowmid' => $gonowmid]);?>
</div>