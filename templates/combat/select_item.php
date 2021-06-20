<?php $this->layout('layout', ['pvp' => $pvp ?? [], 'tupo' => $tupo ?? null]) ?>

<div class="flex flex-col">
    <div class="">
        <span class="text-gray-600">[</span> <span class="font-bold">物品选择</span> <span class="text-gray-600">]</span>
    </div>
    <div class="">
        <ul>
            <?php foreach ($items as $k => $v):?>
                <li>
                    <?php if ($from == 1) :?>
                        <?php if ($is_member): ?>
                            <a href="<?=$this->_l('cmd=do-member-pve&action=item&aid=%d&id=%d&nowmid=%d', $v['id'], $cid, $player->nowmid)?>"><?=$v['name']?></a>
                        <?php else: ?>
                            <a href="<?=$this->_l('cmd=do-pve&action=item&aid=%d&id=%d&nowmid=%d', $v['id'], $cid, $player->nowmid)?>"><?=$v['name']?></a>
                        <?php endif;?>
                    <?php elseif ($from == 2) : ?>
                        <a href="<?=$this->_l('cmd=do-pvp&action=item&aid=%d&id=%d&nowmid=%d', $v['id'], $cid, $player->nowmid)?>"><?=$v['name']?></a>
                    <?php elseif ($from == 3) : ?>
                        <a href="<?=$this->_l('cmd=do-defense&action=item&aid=%d&id=%d&nowmid=%d', $v['id'], $cid, $player->nowmid)?>"><?=$v['name']?></a>
                    <?php endif; ?>
                </li>
            <?php endforeach;?>
        </ul>
    </div>
    <?php $this->insert('includes/message'); ?>

    <?php $this->insert('includes/gonowmid', ['show_return' => false]); ?>
</div>