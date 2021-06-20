<?php $this->layout('layout', ['pvp' => $pvp ?? [], 'tupo' => $tupo ?? null]) ?>

<?php $this->insert('includes/message'); ?>

<div class="flex flex-col">
    <div class="">
        <span class="text-gray-600">[</span> <span class="">等级排行</span> <span class="text-gray-600">]</span>
    </div>
    <div class="">
        <ul>
            <?php foreach ($top as $k => $v):?>
                <li>
                    <?=$k+1?>.<a href="?cmd=<?=$v['info_link']?>"><?php echo empty($v['club']) ? '' : "[{$v['club']}]"?><?=$this->getVipName($v)?></a> (Lv.<?=$v['lv']?>)
                </li>
            <?php endforeach;?>
        </ul>
    </div>
    <?php $this->insert('includes/gonowmid');?>
</div>