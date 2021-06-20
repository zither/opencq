<?php $this->layout('layout', ['pvp' => $pvp ?? [], 'tupo' => $tupo ?? null]) ?>

<div class="flex flex-col">
    <p>昵称:<?=$npc->name?></p>
    <p>性别:<?=$npc->sex?></p>
    <p>信息:<?=$npc->info?></p>
    <div class="my-2">
        <div class="mb-1">========配方列表========</div>
        <?php foreach ($peifang as $k => $v): ?>
            <div class="mb-0">
                [<?=$k+1?>] <a href='?cmd=<?=$v['info_link']?>'><?=$v['name']?></a>
                <?php if ($v['done']): ?>
                    <span class="text-gray-400">(已学习)</span>
                <?php else: ?>
                    <a class='ml-2 border border-gray-400 px-2 rounded' href='?cmd=<?=$v['study_link']?>'>学习</a>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
    <?php $this->insert('includes/message'); ?>
    <?php $this->insert('includes/gonowmid', ['gonowmid' => $gonowmid]);?>
</div>