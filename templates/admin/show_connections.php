<?php $this->layout('layout', ['pvp' => $pvp ?? [], 'tupo' => $tupo ?? null]) ?>

<?php $this->insert('includes/message'); ?>

<div class="flex flex-col">
    <div class="">
        <span class="text-gray-600">[</span> <span class="font-bold">连接地图</span> <span class="text-gray-600">]</span>
    </div>
    <div class="mt-4">
        <table class="table-auto">
            <tbody>
            <?php foreach ($locations as $k => $v):?>
                <tr>
                    <td class="w-64">
                        <span><?=$v['name']?>(<?=$v['id']?>)</span>
                    </td>
                    <td class="w-12">
                        <a class="text-red-700" onclick="return confirm('确定连接吗?')" href="<?=$this->_l('cmd=admin-loc-connect&id=%d&dir=%s', $v['id'], $dir)?>">连接</a>
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
