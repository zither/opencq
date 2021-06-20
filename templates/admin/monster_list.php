<?php $this->layout('layout', ['pvp' => $pvp ?? [], 'tupo' => $tupo ?? null]) ?>

<?php $this->insert('includes/message'); ?>

<div class="flex flex-col">
    <div class="">
        <span class="text-gray-600">[</span> <span class="font-bold">怪物 列表</span> <span class="text-gray-600">]</span>
    </div>
    <div class="mt-4">
        <table class="table-auto">
            <tbody>
            <tr>
                <td class="w-64">
                    <a class="font-bold" href="<?=$this->_l('cmd=admin-show-create-monster')?>">+创建新怪物</a>
                </td>
            </tr>
            <?php foreach ($monsters as $k => $v):?>
                <tr>
                    <td class="w-64">
                        <a href="<?=$this->_l('cmd=admin-show-create-monster&id=%d', $v['id'])?>"><?=$v['name']?></a>(<?=$v['id']?>) - <span class="text-gray-600">lvl.<?=$v['level']?></span>
                        <?php if ($v['amount']) :?>
                            - <span class="text-gray-600"><?=$v['amount']?>个</span>
                        <?php endif; ?>
                    </td>
                    <td class="w-12">
                        <a href="<?=$this->_l('cmd=admin-set-monster&id=%d', $v['id'])?>">放置</a>
                    </td>
                    <?php if ($v['exists']) :?>
                    <td class="w-12">
                        <a class="text-red-700" href="<?=$this->_l('cmd=admin-unset-monster&id=%d', $v['id'])?>">减少</a>
                    </td>
                    <?php endif; ?>
                    <td class="w-12">
                        <a class="text-red-500" onclick="return confirm('确定删除操作吗?')"  href="<?=$this->_l('cmd=admin-delete-monster&id=%d', $v['id'])?>">删除</a>
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