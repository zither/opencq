<?php $this->layout('layout', ['pvp' => $pvp ?? [], 'tupo' => $tupo ?? null]) ?>

<?php $this->insert('includes/message'); ?>

<div class="flex flex-col">
    <div>
        <span class="text-gray-600">[</span>
        <span class="font-bold"><?=$ornament['name']?></span>
        <span class="text-gray-600">]</span>
    </div>
    <div>
        <?=$ornament['info']?>
    </div>

    <div class="mt-4">
        <span>操作列表：</span>
        <table class="table-auto">
            <tbody>
            <?php foreach ($operations as $k => $v):?>
                <tr>
                    <td class="w-64">
                        <a href="<?=$this->_l('cmd=admin-show-operation&id=%d', $v['id'])?>"><?=$v['name']?></a>(<?=$v['id']?>)
                    </td>
                    <td class="w-12">
                        <a class="text-red-700" href="<?=$this->_l('cmd=admin-unset-operation&id=%d', $v['id'])?>">移除</a>
                    </td>
                </tr>
            <?php endforeach;?>
            </tbody>
        </table>
    </div>
    <?php if (!empty($showCondition)): ?>
        <div class="mt-4">
            <hr/>
        </div>
        <div class="mt-4">
            显示条件：
        </div>
        <table class="table-auto">
            <tbody>
            <tr>
                <td class="w-64">
                    <a href="<?=$this->_l('cmd=admin-show-create-condition&id=%d', $showCondition['id'])?>">
                        <?php if (!empty($showCondition['notes'])): ?>
                            <?=$showCondition['notes']?>
                        <?php else:?>
                            条件(<?=$showCondition['id']?>)
                        <?php endif;?>
                    </a>
                </td>
                <td class="w-12">
                    <a class="text-red-700" href="<?=$this->_l('cmd=admin-ornament-unset-condition&id=%d', $ornament['id'])?>">移除</a>
                </td>
            </tr>
            </tbody>
        </table>
    <?php endif;?>
    <div class="mt-4">
        <hr/>
    </div>
    <div class="mt-4">
        [<a class="" href="<?=$this->_l('cmd=admin-show-edit-ornament&id=%d', $ornament['id'])?>">编辑摆件</a>] -
        [<a class="" href="<?=$this->_l('cmd=admin-operation-list&from_id=%d&from_type=1&exist=%s', $ornament['id'], $ornament['operations'])?>">添加操作</a>]
    </div>

    <div class="inline-block my-4">
        <a class="link mr-4" href="<?=$this->_l("cmd=show-loc")?>">返回地图</a>
        <a class="link mr-4" href="<?=$this->_l("cmd=admin-ornament-list")?>">返回列表</a>
    </div>
</div>

