<?php $this->layout('layout', ['pvp' => $pvp ?? [], 'tupo' => $tupo ?? null]) ?>

<div class="flex flex-col">
    <div class="">
        <span class="text-gray-600">[</span><span class="">选择装备</span><span class="text-gray-600">]</span>
    </div>
    <div class="mt-4">
        <?php if (!empty($equips)):?>
            -<br>
            <ul>
            <?php foreach ($equips as $k => $v):?>
                <li>
                    <a class="<?=$v['quality_color']?>" href="<?=$this->_l('cmd=chakanzb&zbnowid=%d&uid=%d', $v['id'], $uid)?>"><?=$this->getPlayerEquipName($v)?></a>
                    <a href="<?=$this->_l('cmd=setzbwz&zbnowid=%d&tool=%s', $v['id'], $tool)?>">装备</a>
                </li>
            <?php endforeach;?>
            </ul>
            -<br>
        <?php endif;?>
    </div>
    <?php $this->insert('includes/message'); ?>
    <div class="inline-block my-4">
        <a class="link mr-4" href="<?=$this->_l('cmd=show-equips')?>">返回上一页</a>
    </div>
</div>