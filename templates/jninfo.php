<?php $this->layout('layout', ['pvp' => $pvp ?? [], 'tupo' => $tupo ?? null]) ?>

<div class="flex flex-col">
    <div>
        <span class="text-gray-600">[</span>
        <span class="font-bold"><?=$jineng->jnname?></span>
        <span class="text-gray-600">]</span>
    </div>
    攻击加成：<?php echo $jineng->jngj; ?>%<br/>
    防御加成：<?php echo $jineng->jnfy; ?>%<br/>
    暴击加成：<?php echo $jineng->jnbj; ?>%<br/>
    吸血加成：<?php echo $jineng->jnxx; ?>%<br/>
    兑换需要：<?=$dhdaoju->djname?>(<?=$sum?>/<?=$jineng->djcount?>)
    <div class='my-4'>
        <a class='px-2 py-1 rounded border border-gray-400 text-blue-500' href='?cmd=<?=$duihuan?>'>兑换<a/>
    </div>

    <?php if ($has_it):?>
        <a href="?cmd=<?=$setjn1?>">装备符篆1.</a>
        <a href="?cmd=<?=$setjn2?>">装备符篆2.</a>
        <a href="?cmd=<?=$setjn3?>">装备符篆3.</a><br/>
    <?php endif;?>

    <?php $this->insert('includes/message'); ?>
    <?php $this->insert('includes/gonowmid', ['gonowmid' => $gonowmid]);?>
</div>