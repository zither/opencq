<?php $this->layout('layout', ['pvp' => $pvp ?? [], 'tupo' => $tupo ?? null]) ?>

<?php $this->insert('includes/message'); ?>

<div class="flex flex-col">
    <div>
        <span class="text-gray-600">[</span><span class="font-bold"><?=$skill['name']?></span><span class="text-gray-600">]</span>
    </div>
    等级：<?=$skill['require_level']?><br/>
    熟练度：<?=$skill['ui_level']?> (<?=$skill['score']?>/<?=$skill['max_score']?>)<br/>
    <div class="color-green"><?=$skill['info']?></div>
    <?php if (!empty($effects)): ?>
        -<br>
        <span class="color-blue">战斗效果:</span> <br>
        <ul>
            <?php foreach ($effects as $effect): ?>
                <li><?=$effect?></li>
            <?php endforeach;?>
        </ul>
        -<br>
    <?php endif; ?>

    <?php if ($skill['outside_combat']):?>
        <a href="<?=$this->_l('cmd=use-skill-outside&id=%d', $skill['id'])?>">使用技能</a>
    <?php endif;?>

    <?php $this->insert('includes/gonowmid');?>
</div>