<?php $this->layout('layout', ['pvp' => $pvp ?? [], 'tupo' => $tupo ?? null]) ?>

<div class="flex flex-col">
    战斗结果<br/>
    <?=$attacker->name?> 攻击了 <?=$defender->name?><br/>
    <?php if ($combatStatus->resultType == 1): ?>
    成功击杀
    <?php elseif ($combatStatus->resultType == 2) :?>
    被反杀了
    <?php elseif ($combatStatus->resultType == 3) :?>
    自己却逃跑了
    <?php elseif ($combatStatus->resultType == 4) :?>
    对方却逃跑了
    <?php endif;?>
    <?php $this->insert('includes/message'); ?>
    <div class="inline-block my-4">
        <a href="?cmd=<?=$gonowmid?>">返回游戏</a>
    </div>
</div>

