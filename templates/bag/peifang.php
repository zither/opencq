<?php $this->layout('layout', ['pvp' => $pvp ?? [], 'tupo' => $tupo ?? null]) ?>

<?php $this->insert('includes/message'); ?>

<div class="flex flex-col">
    <div class="my-2">
        <a href="?cmd=<?=$getbagzbcmd?>">装备</a> | <a href="?cmd=<?=$getbagdjcmd?>">道具</a> | <a href="?cmd=<?=$getbagypcmd?>">药品</a> | 配方
    </div>
    <div>
        <?php if (!empty($peifang)):?>
            -<br>
            <ul>
            <?php foreach ($peifang as $k => $v):?>
                <li>
                    [<?=$k+1?>] <a href="?cmd=<?=$v['info_link']?>"><?=$v['name']?></a>
                </li>
            <?php endforeach;?>
            </ul>
            -<br>
        <?php endif;?>
    </div>
    <?php $this->insert('includes/gonowmid');?>
</div>