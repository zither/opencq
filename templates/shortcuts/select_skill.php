<?php $this->layout('layout', ['pvp' => $pvp ?? [], 'tupo' => $tupo ?? null]) ?>

<div class="flex flex-col">
    <div class="">
        <span class="text-gray-600">[</span> <span class="font-bold">技能选择</span> <span class="text-gray-600">]</span>
    </div>
    <div class="">
        -<br>
        <ul>
            <?php foreach ($skills as $k => $v):?>
                <li>
                    <a href="<?=$this->_l('cmd=set-shortcut&type=skill&n=%d&value=%d', $n , $v['id'])?>"><?=$v['name']?></a>
                </li>
            <?php endforeach;?>
        </ul>
        -<br>
    </div>
    <?php $this->insert('includes/message'); ?>
    <?php $this->insert('includes/gonowmid', ['gonowmid' => $gonowmid]);?>
</div>