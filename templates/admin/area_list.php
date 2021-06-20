<?php $this->layout('layout', ['pvp' => $pvp ?? [], 'tupo' => $tupo ?? null]) ?>

<?php $this->insert('includes/message'); ?>

<div class="flex flex-col">
    <div class="">
        <span class="text-gray-600">[</span> <span class="font-bold">区域列表</span> <span class="text-gray-600">]</span>
    </div>
    <div class="mt-4">
        <table class="table-auto">
            <tbody>
            <tr>
                <td class="w-64">
                    <a class="font-bold" href="<?=$this->_l('cmd=admin-show-create-area')?>">+创建区域</a>
                </td>
            </tr>
            <?php foreach ($areas as $k => $v):?>
                <tr>
                    <td class="w-64">
                        <a href="<?=$this->_l('cmd=admin-show-create-area&id=%d', $v['id'])?>"><?=$v['name']?></a>(<?=$v['id']?>)
                    </td>
                    <td class="w-12">
                        <a class="text-red-500" onclick="return confirm('确定删除操作吗?')"  href="<?=$this->_l('cmd=admin-delete-area&id=%d', $v['id'])?>">删除</a>
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