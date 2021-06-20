<?php $this->layout('layout', ['pvp' => $pvp ?? [], 'tupo' => $tupo ?? null]) ?>

<div class="flex flex-col">
    <span class="font-bold">=====战斗=====</span><br/>
    <?=$pvper->uname?> [lv:<?=$pvper->ulv?>]<br/>
    <div>气血:(<div class="hpys" style="display: inline"><?=$pvper->uhp?></div>/<div class="hpys" style="display: inline"><?=$pvper->umaxhp?></div>)<?=$this->getMessage('pvperhurt')?> <?=$this->hasMessage('pvpbj') ? '(暴击)' : ''?><br/></div>
    攻击:(<?=$pvper->ugj?>)<br/>
    防御:(<?=$pvper->ufy?>)<br/>
    ===================<br/>
    <?=$player->uname?> [lv:<?=$player->ulv?>]<br/>
    <div>气血:(<div class="hpys" style="display: inline"><?=$player->uhp?></div>/<div class="hpys" style="display: inline"><?=$player->umaxhp?></div>)<?=$this->getMessage('phurt')?> <?=$this->getMessage('pvpxx')?><br/></div>
    攻击:(<?=$player->ugj?>)<br/>
    防御:(<?=$player->ufy?>)<br/>
    <div class="mt-4">
        <ul class="inline-flex">
<!--            <li><a class="px-2 py-1 rounded border border-gray-400 bg-red-200 text-blue-500 mr-2" href="?cmd=--><?//=$fastcmd?><!--" >快速攻击</a></li>-->
            <li><a class="px-2 py-1 rounded border border-gray-400 text-blue-500 mr-2"  href="?cmd=<?=$gonowmid?>">逃跑</a></li>
            <li><a class="px-2 py-1 rounded border border-gray-400 text-blue-500 mr-2" href="?cmd=<?=$pgjcmd?>">攻击</a></li>
        </ul>
    </div>
    <div>
        <div class="my-4">
            <a class="px-2 py-1 rounded border border-gray-400 text-blue-500" href="?cmd=<?=$usejn1?>"><?=$jnname1?></a>
            <a class="px-2 py-1 rounded border border-gray-400 text-blue-500" href="?cmd=<?=$usejn2?>"><?=$jnname2?></a>
            <a class="px-2 py-1 rounded border border-gray-400 text-blue-500" href="?cmd=<?=$usejn3?>"><?=$jnname3?></a>
        </div>
<!--        <div class="my-4">-->
<!--            <a class="px-2 py-1 rounded border border-gray-400 text-blue-500" href="?cmd=--><?//=$useyp1?><!--">--><?//=$ypname1?><!--</a>-->
<!--            <a class="px-2 py-1 rounded border border-gray-400 text-blue-500" href="?cmd=--><?//=$useyp2?><!--">--><?//=$ypname2?><!--</a>-->
<!--            <a class="px-2 py-1 rounded border border-gray-400 text-blue-500" href="?cmd=--><?//=$useyp3?><!--">--><?//=$ypname3?><!--</a>-->
<!--        </div>-->
    </div>
    <?php $this->insert('includes/message'); ?>
</div>