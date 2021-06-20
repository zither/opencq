<?php $this->layout('layout', ['pvp' => $pvp ?? [], 'tupo' => $tupo ?? null]) ?>

<?php $this->insert('includes/message'); ?>

<div class="flex flex-col">
    <div class="">
        <span class="text-gray-600">[</span><span class="">我的装备</span><span class="text-gray-600">]</span>
    </div>
    <div class="">
        <span ><a href="<?=$this->_l('cmd=shengxing-list')?>">升星</a></span> .
        <span ><a href="<?=$this->_l('cmd=qianghua-list')?>">强化</a></span>
    </div>
    <div class="">
        -<br>
        <?php foreach ($tools as $k => $v) : ?>
            <div>
                <span><?=$k?>:</span>
                <?php if (!empty($v)): ?>
                    <a class="<?=$v->qualityColor?>" href="<?=$this->_l('cmd=chakanzb&zbnowid=%d&uid=%d', $v->id, $player->id)?>"><?=$this->getPlayerEquipName($v)?></a>
                    <a class="ml-2" href="<?=$this->_l('cmd=select-equip&tool=%s', $k)?>">更换</a>
                    <a class="ml-2" href="<?=$this->_l('cmd=xxzb&equip_id=%d', $v->id)?>">卸下</a>
                <?php else: ?>
                    <a class="ml-2" href="<?=$this->_l('cmd=select-equip&tool=%s', $k)?>">选择</a>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
        -<br>
    </div>
</div>

<div class="inline-block my-4">
    <a href="<?=$this->_l('cmd=gomid&newmid=%d', $gonowmid ?? 0)?>">返回游戏</a>
</div>