<?php $this->layout('layout', ['pvp' => $pvp ?? [], 'tupo' => $tupo ?? null]) ?>

<?php $this->insert('includes/message'); ?>

<div class="flex flex-col">
    <div>我的背包</div>
    <div>元宝: <?=$player->uczb?></div>
    <div>金币: <?=$player->uyxb?></div>
    <div>负重: <?=$count?>/<?=$player->liftingCapacity?></div>
    <div class="mt-2">
        装备 | <a href="<?=$this->_l('cmd=getbagdj')?>">道具</a> | <a href="<?=$this->_l('cmd=getbagyp')?>">药品</a>
    </div>
    <div>
        <?php if (!empty($zhuangbei)):?>
            -<br>
            <ul>
                <?php foreach ($zhuangbei as $k => $v):?>
                    <li>
                        [<?=$k+1?>] <a href="<?=$this->_l("cmd=chakanzb&zbnowid=%d&uid=%d", $v['id'], $player->id)?>" class="<?=$v['quality_color']?>"><?=$this->getPlayerEquipName($v)?></a>
                        <?php if ($v['is_wearing']): ?>
                            (已装备)
                        <?php else: ?>
                            <?php if($v['is_sellable']):?>
                                <a href="<?=$this->_l("cmd=sell-getbagzb&zbnowid=%d", $v['id'])?>" class="" >卖出</a>
                            <?php endif;?>
                            <a href="<?=$this->_l("cmd=delete-getbagzb&zbnowid=%d", $v['id'])?>" class="" >分解</a>
                        <?php endif;?>
                    </li>
                <?php endforeach;?>
            </ul>
            -<br>
        <?php endif; ?>
    </div>
    <?php if (!empty($pagenavi)):?>
        <div class="mt-4 flex flex-row">
            <?php if (isset($pagenavi['prev'])): ?>
                <a class="px-4 py-1 rounded bg-gray-200 " href="?cmd=<?=$pagenavi['prev']?>">上一页</a>
            <?php endif;?>
            <?php if (isset($pagenavi['next'])): ?>
                <a class="px-4 py-1 rounded bg-gray-200 " href="?cmd=<?=$pagenavi['next']?>">下一页</a>
            <?php endif;?>
        </div>
    <?php endif;?>
    <?php $this->insert('includes/gonowmid');?>
</div>