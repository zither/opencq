<?php $this->layout('layout', ['pvp' => $pvp ?? [], 'tupo' => $tupo ?? null]) ?>

<?php $this->insert('includes/message'); ?>

<div class="flex flex-col">
    <div class="">
        <span class="text-gray-600">[</span> <span class="font-bold">条件列表</span> <span class="text-gray-600">]</span>
    </div>
    <div class="mt-4">
        <table class="table-auto">
            <tbody>
            <tr>
                <td class="w-64">
                    <a class="font-bold" href="<?=$this->_l('cmd=admin-show-create-condition')?>">+创建新条件</a>
                </td>
            </tr>
            <?php foreach ($conditions as $k => $v):?>
                <tr>
                    <td class="w-64">
                        <a href="<?=$this->_l('cmd=admin-show-create-condition&id=%d', $v['id'])?>">
                        <?php if (!empty($v['notes'])):?>
                            <?=$v['notes']?>
                        <?php else:;?>
                            条件
                        <?php endif;?>
                            (<?=$v['id']?>)
                        </a>
                    </td>
                    <td class="w-12">
                        <a class="text-red-700" onclick="return confirm('确定删除条件吗?')" href="<?=$this->_l('cmd=admin-delete-condition&id=%d', $v['id'])?>">删除</a>
                    </td>
                </tr>
            <?php endforeach;?>
            </tbody>
        </table>
    </div>
    <div class="inline-block my-4">
        <a class="link mr-4" href="<?=$this->_l('cmd=show-loc')?>">返回上页</a>
    </div>
</div>