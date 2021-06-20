<?php $this->layout('layout', ['pvp' => $pvp ?? [], 'tupo' => $tupo ?? null]) ?>

<div class="flex flex-col">
    <div>
        <span class="text-gray-600">[</span>
        <span class="font-bold"><?=$ornament['name']?></span>
        <span class="text-gray-600">]</span>
    </div>
    <div>
        <?=$ornament['info']?>
    </div>
    <ul>
        <?php foreach ($operations as $v): ?>
            <li><a href="<?=$this->_l('cmd=operate&id=%d', $v['id'])?>"><?=$v['name']?></a></li>
        <?php endforeach;?>
    </ul>

    <?php $this->insert('includes/message'); ?>
    <?php $this->insert('includes/gonowmid', ['gonowmid' => $gonowmid]);?>
</div>

