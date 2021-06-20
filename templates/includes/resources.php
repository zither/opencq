<div class="my-2">
<?php if(!empty($resources)): ?>
    <p class="text-gray-600 mb-2">你认真的搜寻了下周围，找到了：</p>
    <?php foreach ($resources as $resource): ?>
        <a class="mr-2 bg-gray-200 rounded quality-<?=$resource['quality']?> px-2 border" href="?cmd=<?=$resource['cmd']?>"><?=$resource['name']?></a>
        <?=$this->getMessage('message');?>
    <?php endforeach;?>
<?php else: ?>
    <p class="text-gray-600">什么也没有找到，空手而归。</p>
<?php endif;?>
</div>
