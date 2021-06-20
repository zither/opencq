<?php $this->layout('layout', ['pvp' => $pvp ?? [], 'tupo' => $tupo ?? null]) ?>

<?php $this->insert('includes/message'); ?>

<div class="flex flex-col">
    <div class="">
        <span class="text-gray-600">[</span> <span class="">财富排行</span> <span class="text-gray-600">]</span>
    </div>
    <div class="">
        <ul>
            <?php foreach ($top as $k => $v):?>
                <li>
                    <?=$k+1?>.<a href="?cmd=<?=$v['info_link']?>"><?php echo empty($v['club']) ? '' : "[{$v['club']}]"?><?=$this->getVipName($v)?></a>
                    <span class="color-gray">(<?=$v['money']?>万)</span>
                </li>
            <?php endforeach;?>
        </ul>
    </div>
    <?php $this->insert('includes/gonowmid');?>
</div>