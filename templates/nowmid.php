<?php $this->layout('layout', ['notifications' => $notifications ?? null]) ?>

<?php if(isset($died)): ?>
    你已经重伤请治疗<br/>
    <a href="?cmd=<?=$gonowmid?>">返回游戏</a>
<?php else:?>

<?php $this->insert('includes/message'); ?>

<div>
    <div class="">
        [<span><?=$clmid->mname?></span>]
        <a href="<?=$this->_l('cmd=gomid')?>">刷新</a>
        <span class="<?=$clmid->ispvp ? 'color-red' : 'color-green'?>">[<?=$pvphtml?>]</span>
    </div>
    <div class="">
        <a href="<?=$this->_l('cmd=mytask')?>">任务(<?=$taskcount?>)</a> |
        <a href="<?=$this->_l('cmd=party-member')?>">组队</a> |
        <a href="<?=$this->_l('cmd=vip')?>">会员</a> |
        <a class="color-red" href="<?=$this->_l('cmd=activity-list')?>">礼包</a>
    </div>
</div>
<?php if (!empty($npc)) :?>
<!--NPC-->
<div class="flex flex-col">
    <?php foreach ($npc as $v): ?>
    <div class="flex flex-row">
        <div class="tooltip">
            <a class="" href="?cmd=<?=$v['cmd']?>">
                <?php foreach ($v['tasks'] as $image): ?>
                    <img class="inline-block pb-1" src="images/<?=$image?>.gif" />
                <?php endforeach;?>
                <?=$v['name']?>
            </a>
            <?php if (!empty($v['talk'])):?>
            <div class="right border border-gray-400 bg-gray-200 hidden">
                <p><?=$v['talk']?></p>
                <i></i>
            </div>
            <?php endif;?>
        </div>
    </div>
    <?php endforeach;?>
</div>
<?php endif;?>
<?php if (!empty($ornaments)): ?>
    <div class="">
        发现:
        <?php foreach ($ornaments as $v): ?>
            <a class="" href='<?=$this->_l('cmd=show-ornament&id=%d', $v['id'])?>'><?=$v['name']?></a>
        <?php endforeach;?>
    </div>
<?php endif;?>
<?php if (!empty($gw_array)):?>
    <div class="">
        怪物:
        <?php foreach ($gw_array as $v): ?>
            <a class="<?=($v['type'] == 3 ? 'color-red font-bold' : '')?>" href='?cmd=<?=$v['cmd']?>'>*<?=$v['name']?></a>
        <?php endforeach;?>
    </div>
<?php endif;?>
<?php if ($player->manualId != 3): ?>
    <div class="">
        请选择: <a href="<?=$this->_l('cmd=show-area')?>">地图</a> | <a href="<?=$this->_l('cmd=allmap')?>">传送</a> <br/>
    </div>
    <div class="flex flex-col">
        <?php foreach ($directions as $k => $v):?>
            <div><a href="<?=$this->_l('cmd=move-to-mid&mid=%d', $v->id)?>"><?=$k?>: <?=$v->name?></a></div>
        <?php endforeach;?>
    </div>
    <?php if (!empty($clmid->playerinfo)) :?>
        <div>
            [场景]<?=$clmid->playerinfo?>
        </div>
    <?php endif;?>
 <?php endif;?>
    <?php if (!empty($players)):?>
        <div class="">
            你遇到了:
            <?php foreach($players as $v):?>
                <a class='ml-1' href='<?=$this->_l('cmd=getplayerinfo&uid=%d', $v['uid'])?>'><?=$v['club']?><?=$this->getVipName($v)?></a>
            <?php endforeach;?>
        </div>
    <?php endif;?>
    <?php if (!empty($clmid->midinfo)):?>
        <div class="">
            <?=$clmid->midinfo?><br/>
        </div>
    <?php endif;?>
    <div class="">
        <div class="">
            <a class="" href="<?=$this->_l('cmd=zhuangtai')?>">状态</a> .
            <a class="" href="<?=$this->_l('cmd=getbagdj')?>" >背包</a> .
            <a class="" href="<?=$this->_l('cmd=show-equips')?>" >装备</a> .
            <a class="" href="<?=$this->_l('cmd=player-skills')?>" >技能</a>
        </div>
        <div class="">
            <a class="" href="<?=$this->_l('cmd=chongwu')?>" >宝宝</a> .
            <a class="" href="<?=$this->_l('cmd=relationship')?>" >关系</a> .
            <a class="" href="<?=$this->_l('cmd=ranks')?>" >排行</a> .
            <a class="" href="<?=$this->_l('cmd=fangshi-daoju')?>">坊市</a>
        </div>
        <div class="">
<!--            <a class="color-gray" href="" >魔塔</a> .-->
            <a class="" href="<?=$this->_l('cmd=club')?>" >门派</a> .
            <a class="" href="<?=$this->_l('cmd=liaotian')?>">消息</a> .
            <a class="" href="<?=$this->_l('cmd=show-combat-condition')?>">策略</a> .
            <a class="" href="<?=$this->_l('cmd=show-shortcuts')?>" >设置</a>
        </div>
        <?php if ($isAdmin):?>
            <div class="">
                <a class="" href="<?=$this->_l('cmd=show-loc&mid=%d', $clmid->mid)?>">编辑</a>
            </div>
        <?php endif;?>
    </div>
    <div>
        -
    </div>
    <div>
        <a href="/">首页</a> . <a href="<?=$this->_l('cmd=about')?>" class="color-blue">联系</a> . <a class="color-red" href="<?=$this->_l('cmd=tutorial')?>">攻略</a>
    </div>

    <?php $this->insert('includes/footer'); ?>
<?php endif;?>