<?php $this->layout('layout', ['pvp' => $pvp ?? [], 'tupo' => $tupo ?? null]) ?>

<div class="flex flex-col">
    <p>昵称:<?=$npc->name?></p>
    <p>性别:<?=$npc->sex?></p>
    <p>信息:<?=$npc->info?></p>
    <div class="my-2">
        <form action="?cmd=<?=$action?>" method="POST">
            <div>门派大名:<input  class="border border-gray-400 ml-1 rounded" name="clubname"></div>
            <div class="my-2 w-full"><textarea class="rounded p-2 border border-gray-400 w-full" placeholder="门派说明" name="clubinfo" style="height: 80px"></textarea></div>
            <input class="btn px-4 rounded" type="submit" value="创建">
        </form>
    </div>
    <?php $this->insert('includes/message'); ?>
    <?php $this->insert('includes/gonowmid', ['gonowmid' => $gonowmid]);?>
</div>