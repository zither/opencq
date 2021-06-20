<?php $this->layout('layout') ?>

<?php $this->insert('includes/message'); ?>

<div class="w-full">
    <form class="bg-white rounded px-0 pt-2 mb-4" action="<?=$this->_l('cmd=admin-create-condition')?>" method="post">
        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" type="hidden" name="id" value="<?=$condition['id'] ?? 0?>">
        <div class="mb-2">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="username">
                条件备注
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="username" type="text" name="notes" value="<?=$condition['notes'] ?? ''?>">
        </div>
        <div class="mb-2">
            <label class="block text-gray-700 text-sm font-bold mb-2">
                成功提示
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" name="success_info" value="<?=$condition['success_info'] ?? ''?>">
        </div>
        <div class="mb-2">
            <label class="block text-gray-700 text-sm font-bold mb-2">
                失败提示
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" name="failure_info" value="<?=$condition['failure_info'] ?? ''?>">
        </div>
        <div class="mb-2">
            <label class="block text-gray-700 text-sm font-bold mb-2">
                配套条件
            </label>
            <textarea  rows="5" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" name="matchers" ><?=$condition['matchers'] ?? ''?></textarea>
        </div>
        <div class="flex items-center justify-between">
            <button class="bg-blue-500 hover:bg-blue-700 text-white py-1 px-4 rounded focus:outline-none focus:shadow-outline" type="submit" name="submit">
                保存
            </button>
        </div>
    </form>
</div>
<div class="inline-block my-4">
    <a class="link mr-4" href="<?=$this->_l('cmd=admin-condition-list')?>">返回列表</a>
</div>
