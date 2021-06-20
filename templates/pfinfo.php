<?php $this->layout('layout', ['pvp' => $pvp ?? [], 'tupo' => $tupo ?? null]) ?>

<?php $this->insert('includes/message');?>

<p><span class="text-gray-600">[</span> <span class="font-bold"><?=$peifang['name']?></span> <span class="text-gray-600">]</span></p>
<?=$peifang['description']?><br/>
<div class="mt-2">
配方材料：<br/>
<?php foreach ($ingredients as $v): ?>
    <div class="ml-4">
        - <span class="quality-<?=$v['quality']?>"><?=$v['name']?></span> <span class="text-gray-600">x<?=$ingredientIds[$v['id']]?></span>
    </div>
<?php endforeach;?>
</div>
<?php if (!empty($target)): ?>
<div class="mt-2">
炼制物品：
    <div class="ml-4">
        - <span class="<?=$target['class']?>"><?=$target['name']?></span> <span class="text-gray-600">x1</span>
    </div>
</div>
<?php endif;?>

<?php if (!empty($playerPfInfo)) :?>
    <div class="mt-4 text-gray-600"><?=$player->name?>的配方熟练度：<?=$playerPfInfo['proficiency']?>%</div>
    <div class="mt-4"><a class="mr-2 border border-gray-400 px-2 rounded"  href="?cmd=<?=$makeCmd?>">制作物品</a></div>
<?php endif;?>

<?php $this->insert('includes/gonowmid');?>
