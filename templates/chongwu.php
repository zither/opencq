<?php $this->layout('layout', ['pvp' => $pvp ?? [], 'tupo' => $tupo ?? null]) ?>

<?php $this->insert('includes/message'); ?>

<div class="flex flex-col">
    <div>
        <span class="text-gray-600">[</span><span class="">宝宝</span><span class="text-gray-600">]</span>
    </div>
    <div class="">
        <?php if (empty($pets)): ?>
            当前没有宝宝
        <?php else:?>
            -<br>
            <ul>
                <?php foreach ($pets as $pet):?>
                    <li>
                        <a href="<?=$this->_l('cmd=cwinfo&cwid=%d', $pet->id)?>"><?=$pet->name?></a>
                        <?php if ($pet->isOut): ?>
                            (已出战)
                        <?php endif;?>
                        <?php if (!$pet->isOut): ?>
                            <a href="<?=$this->_l('cmd=cz-chongwu&cwid=%d', $pet->id)?>" class="" >出战</a>
                            <?php if ($player->vip): ?>
                                <a href="<?=$this->_l('cmd=fs-chongwu&cwid=%d', $pet->id)?>" class="" >放生</a>
                            <?php endif;?>
                        <?php else: ?>
                            <a href="<?=$this->_l('cmd=sh-chongwu&cwid=%d', $pet->id)?>" class=" " >召回</a>
                        <?php endif;?>
                    </li>
                <?php endforeach;?>
            </ul>
            -<br>
        <?php endif;?>
    </div>
    <?php $this->insert('includes/gonowmid', ['show_prev' => false]);?>
</div>