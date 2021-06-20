<?php $this->layout('layout', ['pvp' => $pvp ?? [], 'tupo' => $tupo ?? null]) ?>

<?php $this->insert('includes/message'); ?>

<div class="flex flex-col">
    <div class="">
        <span class="text-gray-600">[</span> <span class="font-bold">NPC 列表</span> <span class="text-gray-600">]</span>
    </div>
    <div class="mt-4">
        <table class="table-auto">
            <tbody>
            <tr>
                <td class="w-64">
                    <a class="font-bold" href="<?=$this->_l('cmd=admin-show-create-npc')?>">+创建NPC</a>
                </td>
            </tr>
            <?php foreach ($npcs as $k => $v):?>
                <tr>
                    <td class="w-64">
                        <a href="<?=$this->_l('cmd=admin-show-create-npc&id=%d', $v['id'])?>"><?=$v['name']?></a>(<?=$v['id']?>)
                    </td>
                    <td class="w-12">
                        <?php if ($v['exists']) :?>
                            <a class="text-red-500" href="<?=$this->_l('cmd=admin-unset-npc&id=%d', $v['id'])?>">移除</a>
                        <?php else: ?>
                            <a href="<?=$this->_l('cmd=admin-set-npc&id=%d', $v['id'])?>">放置</a>
                        <?php endif; ?>
                    </td>
                    <td class="w-12">
                        <a class="text-red-500" onclick="return confirm('确定删除操作吗?')"  href="<?=$this->_l('cmd=admin-delete-npc&id=%d', $v['id'])?>">删除</a>
                    </td>
                </tr>
            <?php endforeach;?>
            </tbody>
        </table>
    </div>
    <div class="inline-block my-4">
        <a class="link mr-4" href="<?=$this->_l('cmd=show-loc')?>">返回编辑</a>
    </div>
</div>