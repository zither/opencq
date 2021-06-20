<?php $this->layout('layout') ?>

<?php $this->insert('includes/message'); ?>

<div class="w-full">
    <form class="bg-white rounded px-0 pt-2 pb-8 mb-4" action="<?=$this->_l('cmd=admin-create-npc')?>" method="post">
        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" type="hidden" name="id" value="<?=$npc['id'] ?? 0?>">
        <div class="mb-2">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="username">
                NPC名称
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" type="text" name="name" value="<?=$npc['name'] ?? ''?>">
        </div>
        <div class="mb-2">
            <label class="block text-gray-700 text-sm font-bold mb-2">
                性别
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" name="sex"  value="<?=$npc['sex'] ?? ''?>">
        </div>
        <div class="mb-2">
            <label class="block text-gray-700 text-sm font-bold mb-2">
                模板列表
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" name="muban"  value="<?=$npc['muban'] ?? ''?>">
        </div>
        <div class="mb-2">
            <label class="block text-gray-700 text-sm font-bold mb-2">
                信息
            </label>
            <textarea  rows="5" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" name="info" > <?=$npc['info'] ?? ''?></textarea>
        </div>
        <div class="flex items-center justify-between">
            <button class="bg-blue-500 hover:bg-blue-700 text-white py-1 px-4 rounded focus:outline-none focus:shadow-outline" type="submit" name="submit" value="登录">
                保存
            </button>
        </div>
    </form>
</div>

<div class="inline-block my-4">
    <a class="link mr-4" href="<?=$this->_l('cmd=admin-npc-list')?>">返回列表</a>
</div>
