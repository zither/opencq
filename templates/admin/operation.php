<?php $this->layout('layout', ['pvp' => $pvp ?? [], 'tupo' => $tupo ?? null]) ?>

<?php $this->insert('includes/message'); ?>

<div class="flex flex-col">
    <div>
        <span class="text-gray-600">[</span>
        <span class="font-bold">编辑操作</span>
        <span class="text-gray-600">]</span>
    </div>

    <div class="mt-4">
        <form class="bg-white rounded px-0 pt-2 mb-4" action="<?=$this->_l('cmd=admin-create-operation')?>" method="post">
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" type="hidden" name="id" value="<?=$operation['id'] ?? 0?>">
            <div class="mb-2">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="username">
                    操作名称
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="username" type="text" name="name" value="<?=$operation['name'] ?? ''?>">
            </div>
            <div class="mb-2">
                <label class="block text-gray-700 text-sm font-bold mb-2">
                    备注
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" name="notes" value="<?=$operation['notes'] ?? ''?>">
            </div>
            <div class="mb-2">
                <label class="block text-gray-700 text-sm font-bold mb-2">
                    回调操作
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" name="cmd" value="<?=$operation['cmd'] ?? ''?>" >
            </div>
            <div class="mb-2">
                <label class="block text-gray-700 text-sm font-bold mb-2">
                    操作类型
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" name="type" value="<?=$operation['type'] ?? 0?>" >
            </div>
            <div class="mb-2">
                <label class="block text-gray-700 text-sm font-bold mb-2">
                    显示条件
                </label>
                <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" name="condition">
                    <option value="0" selected>无</option>
                    <?php foreach ($conditions as $v): ?>
                        <?php if (isset($operation['condition']) && $v['id'] == $operation['condition']) :?>
                            <option value="<?=$v['id']?>" selected><?=$v['notes']?></option>
                        <?php else: ?>
                            <option value="<?=$v['id']?>"><?=$v['notes']?></option>
                        <?php endif; ?>
                    <?php endforeach;?>
                </select>
            </div>
            <div class="mb-2">
                <label class="block text-gray-700 text-sm font-bold mb-2">
                    提示话语
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" name="message" value="<?=$operation['message'] ?? ''?>" >
            </div>
            <div class="mb-2">
                <label class="block text-gray-700 text-sm font-bold mb-2">
                    区域编号(副本专用)
                </label>
                <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" name="area_id">
                    <option value="0" selected>无</option>
                    <?php foreach ($dungeons as $v): ?>
                        <?php if (isset($operation['area_id']) && $v['id'] == $operation['area_id']) :?>
                            <option value="<?=$v['id']?>" selected><?=$v['name']?></option>
                        <?php else: ?>
                            <option value="<?=$v['id']?>"><?=$v['name']?></option>
                        <?php endif; ?>
                    <?php endforeach;?>
                </select>
            </div>
            <div class="mb-2">
                <label class="block text-gray-700 text-sm font-bold mb-2">
                    接受任务
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" name="new_tasks" value="<?=$operation['new_tasks'] ?? ''?>" >
            </div>
            <div class="mb-2">
                <label class="block text-gray-700 text-sm font-bold mb-2">
                    增加标识
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" name="inc_identity" value="<?=$operation['inc_identity'] ?? ''?>" >
            </div>
            <div class="mb-2">
                <label class="block text-gray-700 text-sm font-bold mb-2">
                    获得物品
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" name="get_items" value="<?=$operation['get_items'] ?? ''?>" >
            </div>
            <div class="mb-2">
                <label class="block text-gray-700 text-sm font-bold mb-2">
                    失去物品
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" name="lose_items" value="<?=$operation['lose_items'] ?? ''?>" >
            </div>
            <div class="flex items-center justify-between">
                <button class="bg-blue-500 hover:bg-blue-700 text-white py-1 px-4 rounded focus:outline-none focus:shadow-outline" type="submit" name="submit" value="登录">
                    保存操作
                </button>
            </div>
        </form>
    </div>
    <div class="inline-block mt-4">
        <a class="link mr-4" href="<?=$this->_l("cmd=show-loc")?>">返回地图</a>
        <a class="link mr-4" href="<?=$this->_l("cmd=admin-operation-redirect")?>">返回上页</a>
    </div>
</div>

