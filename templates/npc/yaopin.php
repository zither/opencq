<?php $this->layout('layout', ['pvp' => $pvp ?? [], 'tupo' => $tupo ?? null]) ?>

<?php $this->insert('includes/message'); ?>

<div class="flex flex-col">
    <p class="font-bold"><?=$npc->name?></p>
    <p>性别:<?=$npc->sex?></p>
    <p><?=$npc->info?></p>
    <div class="my-2">
        <?php foreach ($yaopin as $v): ?>
            <div>
                <a href="<?=$this->_l("cmd=show-shop-item-info&id=%d", $v['id'])?>"><?=$v['name']?>(<?=$v['price']?>金币)</a>
                <a class="ml-2" href="<?=$this->_l("cmd=do-shop&count=1&id=%d", $v['id'])?>">购买</a>
            </div>
        <?php endforeach; ?>
    </div>
    <?php $this->insert('includes/gonowmid');?>
</div>