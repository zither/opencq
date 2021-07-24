<?php $this->layout('layout', ['pvp' => $pvp ?? [], 'tupo' => $tupo ?? null]) ?>

<?php $this->insert('includes/message'); ?>

<div class="flex flex-col">
    <div>
        <span class="color-gray"></span><span class="<?=$quality_color?>"><?=$this->getPlayerEquipName($zhuangbei)?></span><span class="color-gray"></span>
    </div>
    等级: <?=$zhuangbei->level?><br/>
    价格: <?=$zhuangbei->price?><br/>
    <?php if (!empty($zhuangbei->sex)):?>
        性别: <?=$zhuangbei->sex == 1 ? '男': '女'?><br/>
    <?php endif;?>
    类型: <?=$tool?><br/>
    <?php if (!empty($zhuangbei->manualId)):?>
        职业: <?=$manuals[$zhuangbei->manualId]?><br/>
    <?php endif;?>
    <span class="<?=$is_bound ? 'color-red' : 'color-green'?>">绑定: <?=$is_bound ? '是' : '否'?></span><br/>
    <?php if (!empty($zhuangbei->info)):?>
    <?=$zhuangbei->info?><br/>
    <?php endif;?>
    基础属性: <br/>
    <?php foreach ($attributes as $k => $v): ?>
        <?php if (isset($zhuangbei->$k) && ($zhuangbei->$k > 0 || $zhuangbei->{"quality" . ucfirst($k)} > 0)) :?>
            <div class="color-green">
                <?=$v?>: <?=floor(($zhuangbei->$k + $zhuangbei->{"quality" . ucfirst($k)}) * (1 + 0.1 * $zhuangbei->qianghua))?>
            </div>
        <?php endif;?>
    <?php endforeach; ?>
    <?php if (!empty($zhuangbei->quality)):?>
        品质加成: <br/>
        <?php foreach ($attributes as $k => $v): ?>
            <?php if (!empty($zhuangbei->{"quality" . ucfirst($k)})) :?>
                <div class="<?=$quality_color?>"><?=$v?>: <?=$zhuangbei->{"quality" . ucfirst($k)}?></div>
            <?php endif;?>
        <?php endforeach; ?>
    <?php endif;?>
    <?php if (!empty($zhuangbei->keywords)):?>
    特殊属性: <br/>
        <?php foreach ($zhuangbei->keywords as $v):?>
            <div class="color-red"><?=$v['desc']?></div>
        <?php endforeach;?>
    <?php endif;?>
    装备来源: <br/>
    <div class="color-gray">
        地图: <?=$zhuangbei->sourceLocation ?: '未知'?><br/>
        对象: <?=$zhuangbei->sourceMonster ?: '未知'?><br/>
        玩家: <?=$zhuangbei->sourcePlayer ?: '未知'?><br/>
        时间: <?=$zhuangbei->sourceTimestamp?>
    </div>
    <?php if ($is_wearable): ?>
        <?php if (!$is_bound && $is_wearable):?>
            -<br>
            <form class="mb-0" action="<?=$this->_l("cmd=sell-djinfo&id=%d", $zhuangbei->id)?>" method="POST">
                <input type="hidden" name="count" value="1">
                寄售价格：<br/>
                <input class="border px-1 border-blue-900" type="number" name="price" value="1">
                <div class="mt">
                    <input class="mr-2 border border-gray-400 px-2 rounded cursor-pointer" type="submit" value="确定">
                </div>
            </form>
            -<br>
        <?php endif;?>
    <?php endif;?>
    <?php $this->insert('includes/gonowmid');?>
</div>