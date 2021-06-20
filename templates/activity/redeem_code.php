<?php $this->layout('layout', ['pvp' => $pvp ?? [], 'tupo' => $tupo ?? null]) ?>

<?php $this->insert('includes/message'); ?>

<div class="flex flex-col">
    <div><span class="color-gray">[</span> <span class=""><?=!empty($label) ? $label : '兑换码'?></span> <span class="color-gray">]</span></div>
    <div>
        <form class="mt mb" action="<?=$this->_l('cmd=activity-do-redeem-code')?>" method="post">
            <div class="">
                <p><label class="" for="redeem-code"><?=$message?></label></p>
                <input class="color-green" id="redeem-code" type="text" name="code" value="<?=$code?>">
            </div>
            <div class="mt">
                <button class="" type="submit" name="submit">领取</button>
            </div>
        </form>
        <?php if (!empty($items)): ?>
        <div class="mb">
            <p><span class="color-blue">可获得</span>：</p>
            <ul class="disc-list">
                <?php foreach ($items as $v): ?>
                    <li class="list-ml"><?=$v['name']?> x<?=$v['amount']?></li>
                <?php endforeach;?>
            </ul>
        </div>
        <?php endif;?>
    </div>
    <a class="link mr-1" href="<?=$this->_l('cmd=activity-list')?>">返回上级</a> <br/>
    <?php $this->insert('includes/gonowmid', ['show_prev' => false]);?>
</div>