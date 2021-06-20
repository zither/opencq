<?php $this->layout('layout') ?>

<?php $this->insert('includes/message'); ?>

<div class="w-full">
    <form class="bg-white rounded px-0 pt-2 pb-8 mb-4" action="<?=$this->_l('cmd=admin-create-area')?>" method="post">
        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" type="hidden" name="id" value="<?=$area['id'] ?? 0?>">
        <div class="mb-2">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="username">
                区域名称
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" type="text" name="name" value="<?=$area['name'] ?? ''?>">
        </div>
        <div class="mb-2">
            <label class="block text-gray-700 text-sm font-bold mb-2">
                类型
            </label>
            <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" name="type">
                <?php foreach ([1 => '城市', 2 => '野外', 3 => '副本'] as $k => $v): ?>
                    <?php if (isset($area['type']) && $k == $area['type']) :?>
                        <option value="<?=$k?>" selected ><?=$v?></option>
                    <?php else: ?>
                        <option value="<?=$k?>" ><?=$v?></option>
                    <?php endif; ?>
                <?php endforeach;?>
            </select>
        </div>
        <div class="mb-2">
            <label class="block text-gray-700 text-sm font-bold mb-2">
                传送点编号
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" name="teleport"  value="<?=$area['teleport'] ?? 0?>">
        </div>
        <div class="mb-2">
            <label class="block text-gray-700 text-sm font-bold mb-2">
                复活点编号
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" name="mid"  value="<?=$area['mid'] ?? 0?>">
        </div>
        <div class="flex items-center justify-between">
            <button class="bg-blue-500 hover:bg-blue-700 text-white py-1 px-4 rounded focus:outline-none focus:shadow-outline" type="submit" name="submit" value="登录">
                保存
            </button>
        </div>
    </form>
</div>

<div class="inline-block my-4">
    <a class="link mr-4" href="<?=$this->_l('cmd=admin-area-list')?>">返回列表</a>
</div>
