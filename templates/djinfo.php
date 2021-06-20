<?php $this->layout('layout', ['pvp' => $pvp ?? [], 'tupo' => $tupo ?? null]) ?>

<?php $this->insert('includes/message'); ?>

<div class="flex flex-col">
    <div><span class="color-gray">[</span> <span class=""><?=$item->uiName?></span> <span class="color-gray">]</span></div>
    <?php if ($playerItem->amount > 0):?>
        数量：<?=$playerItem->amount?: 0?><br/>
    <?php endif;?>
    价格：<?=$item->price?>金币<br/>
    <span class="<?=$is_bound ? 'color-red' : 'color-green'?>">绑定：<?=$is_bound ? '是' : '否'?></span><br/>
    <?=$item->info?>
    <br/>
    <?php if (!empty($operations) && $playerItem->amount > 0):?>
        <?php foreach ($operations as $v):?>
            <p class="mt-2"><a href="<?=$this->_l('cmd=operate&id=%d', $v['id'])?>"><?=$v['name']?></a></p>
        <?php endforeach;?>
    <?php endif;?>

    <?php if (!empty($package)):?>
        <p class="mt-2">使用后可获得：</p>
        <?php foreach ($package as $v):?>
            <div class="ml-4">- <span class="<?=$v['class']?>"><?=$v['name']?></span></div>
        <?php endforeach;?>
        <div class="my-2"><a class="px-2 py-1 rounded bg-gray-300" href="?cmd=<?=$useCmd?>">使用</a></div>
    <?php endif;?>

    <?php if ($item->type ==1 && $item->subType == 1 && $playerItem->amount > 0):?>
        -<br>
        <p class="">使用后加入职业: <a class=""><?=$manual['name']?></a></p>
        <div class=""><a class="" href="<?=$this->_l('cmd=learn-manual&id=%d&item_id=%d', $manual['id'], $item->id)?>">确定</a></div>
        -<br>
    <?php endif;?>

    <?php if ($item->type ==1 && $item->subType == 2 && $playerItem->amount > 0):?>
        -<br>
        <p class="">使用后学习技能: <a class=""><?=$skill['name']?></a>(<?=$skill['level']?>级)</p>
        <div class=""><a class="" href="<?=$this->_l('cmd=learn-skill&id=%d&item_id=%d', $skill['id'], $item->id)?>">确定</a></div>
        -<br>
    <?php endif;?>

    <?php if ($playerItem->amount > 0 && !$is_bound && $item->isSellable): ?>
        -<br>
        <form class="mt-2" action="<?=$this->_l("cmd=sell-djinfo&id=%d", $playerItem->id)?>" method="POST">
            寄售数量：<br/>
            <input class="border border-blue-900 px-1" type="number" name="count" value="1"><br/>
            寄售单价：<br/>
            <input class="border border-blue-900 px-1" type="number" name="price" value="1">
            <div class="mt-1">
                <button class="" type="submit">寄售</button>
            </div>
        </form>
        -<br>
    <?php endif;?>
    <?php $this->insert('includes/gonowmid');?>
</div>