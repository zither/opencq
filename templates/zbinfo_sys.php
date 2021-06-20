<?php $this->layout('layout', ['pvp' => $pvp ?? [], 'tupo' => $tupo ?? null]) ?>

<div class="flex flex-col">
    <div>
        <span class="text-gray-600">[ </span>
        <span class="font-bold"><?=$zhuangbei->zbname?></span>
        <span class="text-gray-600"> ]</span>
    </div>
    装备攻击:<?=$zhuangbei->zbgj?><br/>
    装备防御:<?=$zhuangbei->zbfy?><br/>
    增加气血:<?=$zhuangbei->zbhp?><br/>
    装备暴击:<?=$zhuangbei->zbbj?>%<br/>
    装备吸血:<?=$zhuangbei->zbxx?>%<br/>
    装备信息:<?=$zhuangbei->zbinfo?><br/><br/>
    提示：装备不限制种类！<br/><br/>
    <?php $this->insert('includes/message'); ?>
    <?php $this->insert('includes/gonowmid', ['gonowmid' => $gonowmid]);?>
</div>