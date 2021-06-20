<?php $this->layout('layout', ['pvp' => $pvp ?? [], 'tupo' => $tupo ?? null]) ?>

<?php $this->insert('includes/message'); ?>

<div class="flex flex-col">
    <div class="">
        <span class="color-gray">[</span><span class="">选择升星装备</span><span class="color-gray">]</span>
    </div>
    <div class="mt-4">
        <?php if (!empty($equips)):?>
            -<br>
            <ul>
            <?php foreach ($equips as $k => $v):?>
                <li>
                    <a class="<?=$v['quality_color']?>" href="<?=$this->_l('cmd=chakanzb&zbnowid=%d&uid=%d', $v['id'], $uid)?>"><?=$this->getPlayerEquipName($v)?></a>
                    <a href="<?=$this->_l('cmd=show-shengxing-material&id=%d', $v['id'])?>">选择</a>
                </li>
            <?php endforeach;?>
            </ul>
            -<br>
        <?php endif;?>
    </div>
    <div class="inline-block my-4">
        <a class="link mr-4" href="<?=$this->_l('cmd=show-equips')?>">返回上一页</a>
    </div>
</div>