<?php $this->layout('layout', ['pvp' => $pvp ?? [], 'tupo' => $tupo ?? null]) ?>

<?php $this->insert('includes/message'); ?>

<div class="flex flex-col">
    <div class="">
        <span class="text-gray-600">[</span> <span class="font-bold">操作列表</span> <span class="text-gray-600">]</span>
    </div>
    <div class="mt-4">
        <table class="table-auto">
            <tbody>
            <tr>
                <td class="w-64">
                    <a class="font-bold" href="<?=$this->_l('cmd=admin-show-create-operation')?>">+创建新操作</a>
                </td>
            </tr>
            <?php foreach ($operations as $k => $v):?>
                <tr>
                    <td class="w-64">
                        <a href="<?=$this->_l('cmd=admin-show-create-operation&id=%d', $v['id'])?>"><?=$v['name']?></a>
                        <?php if (!empty($v['notes'])):?>
                            (<?=$v['notes']?>)
                        <?php endif;?>
                        (<?=$v['id']?>)
                    </td>
                    <?php if (!$v['exists']) :?>
                    <td class="w-12">
                        <a href="<?=$this->_l('cmd=admin-set-operation&id=%d', $v['id'])?>">添加</a>
                    </td>
                    <?php else :?>
                    <td class="w-12">
                        <a class="text-red-700" href="<?=$this->_l('cmd=admin-unset-operation&id=%d', $v['id'])?>">移除</a>
                    </td>
                    <?php endif; ?>
                    <td class="w-12">
                        <a class="text-red-700" onclick="return confirm('确定删除操作吗?')" href="<?=$this->_l('cmd=admin-delete-operation&id=%d', $v['id'])?>">删除</a>
                    </td>
                </tr>
            <?php endforeach;?>
            </tbody>
        </table>
    </div>
    <div class="inline-block my-4">
        <a class="link mr-4" href="<?=$this->_l('cmd=admin-operation-redirect')?>">返回上页</a>
    </div>
</div>