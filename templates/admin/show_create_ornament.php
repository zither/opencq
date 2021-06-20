<?php $this->layout('layout') ?>

<?php $this->insert('includes/message'); ?>

<div class="w-full">
    <form class="bg-white rounded px-0 pt-2 mb-4" action="<?=$this->_l('cmd=admin-create-ornament')?>" method="post">
        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" type="hidden" name="id" value="<?=$ornament['id'] ?? 0?>">
        <div class="mb-2">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="username">
                摆件名称
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="username" type="text" name="name" value="<?=$ornament['name'] ?? ''?>">
        </div>
        <div class="mb-2">
            <label class="block text-gray-700 text-sm font-bold mb-2">
                操作列表
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" name="operations" value="<?=$ornament['operations'] ?? ''?>">
        </div>
        <div class="mb-2">
            <label class="block text-gray-700 text-sm font-bold mb-2">
                显示条件
            </label>
            <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" name="show_condition">
                <option value="0" selected>无</option>
                <?php foreach ($conditions as $v): ?>
                    <?php if (isset($ornament['show_condition']) && $v['id'] == $ornament['show_condition']) :?>
                        <option value="<?=$v['id']?>" selected><?=$v['notes']?></option>
                    <?php else: ?>
                        <option value="<?=$v['id']?>"><?=$v['notes']?></option>
                    <?php endif; ?>
                <?php endforeach;?>
            </select>
        </div>
        <div class="mb-2">
            <label class="block text-gray-700 text-sm font-bold mb-2">
                信息
            </label>
            <textarea  rows="5" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" name="info" ><?=$ornament['info'] ?? ''?></textarea>
        </div>
        <div class="flex items-center justify-between">
            <button class="bg-blue-500 hover:bg-blue-700 text-white py-1 px-4 rounded focus:outline-none focus:shadow-outline" type="submit" name="submit">
                保存
            </button>
        </div>
    </form>
</div>
<div class="inline-block my-4">
    <?php if (empty($ornament['id'])): ?>
        <a class="link mr-4" href="<?=$this->_l('cmd=admin-ornament-list')?>">返回列表</a>
    <?php else:?>
        <a class="link mr-4" href="<?=$this->_l('cmd=admin-show-ornament&id=%d', $ornament['id'])?>">返回摆件</a>
    <?php endif; ?>
</div>
