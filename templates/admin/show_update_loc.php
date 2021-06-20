<?php $this->layout('layout') ?>
<div class="w-full">
    <?php $this->insert('includes/message'); ?>
    <form class="bg-white rounded px-0 pt-2 mb-4" action="<?=$this->_l('cmd=admin-update-loc')?>" method="post">
        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" type="hidden" name="id" value="<?=$location->id ?? 0?>">
        <div class="mb-2">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="username">
                名称
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" type="text" name="name" value="<?=$location->name ?? ''?>">
        </div>
        <div class="mb-2">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
                说明
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" name="info" value="<?=$location->info ?? ''?>" >
        </div>
        <div class="flex items-center justify-between">
            <button class="bg-blue-500 hover:bg-blue-700 text-white py-1 px-4 rounded focus:outline-none focus:shadow-outline" type="submit" name="submit">
                保存
            </button>
        </div>
    </form>
</div>

<div class="inline-block">
    <a class="link mr-4" href="<?=$this->_l('cmd=show-loc')?>">返回地图</a>
</div>
