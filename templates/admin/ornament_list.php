<?php $this->layout('layout', ['pvp' => $pvp ?? [], 'tupo' => $tupo ?? null]) ?>

<?php $this->insert('includes/message'); ?>

<div class="flex flex-col">
    <div class="">
        <span class="text-gray-600">[</span> <span class="font-bold">摆件列表</span> <span class="text-gray-600">]</span>
    </div>
    <div class="mt-4">
        <table class="table-auto">
            <tbody>
            <tr>
                <td class="w-64">
                    <a class="font-bold" href="<?=$this->_l('cmd=admin-show-edit-ornament')?>">+创建新摆件</a>
                </td>
            </tr>
            <?php foreach ($ornaments as $k => $v):?>
                <tr>
                    <td class="w-64">
                        <a href="<?=$this->_l('cmd=admin-show-ornament&id=%d', $v['id'])?>"><?=$v['name']?></a>(<?=$v['id']?>)</span>
                    </td>
                    <?php if (!$v['exists']) :?>
                    <td class="w-12">
                        <a href="<?=$this->_l('cmd=admin-set-ornament&id=%d', $v['id'])?>">放置</a>
                    </td>
                    <?php else :?>
                    <td class="w-12">
                        <a class="text-red-700" href="<?=$this->_l('cmd=admin-unset-ornament&id=%d', $v['id'])?>">移除</a>
                    </td>
                    <?php endif; ?>
                    <td class="w-12">
                        <a class="text-red-700" onclick="return confirm('确定删除摆件吗?')" href="<?=$this->_l('cmd=admin-delete-ornament&id=%d', $v['id'])?>">删除</a>
                    </td>
                </tr>
            <?php endforeach;?>
            </tbody>
        </table>
    </div>
    <div class="inline-block my-4">
        <a class="link mr-4" href="<?=$this->_l('cmd=show-loc')?>">返回地图</a>
    </div>
</div>