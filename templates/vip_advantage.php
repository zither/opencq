<?php $this->layout('layout', ['pvp' => $pvp ?? [], 'tupo' => $tupo ?? null]) ?>

<?php $this->insert('includes/message'); ?>

<div class="flex flex-col">
    <div class="">
        <span class="color-gray">[</span><span class="color-red">会员权益</span><span class="color-gray">]</span>
    </div>
    <div class="">
        <ul>
            <li>1.独特的昵称颜色</li>
            <li>2.超大背包容量(300格)，拾取更多道具</li>
            <li>3.商店购买物品享受7折优惠价</li>
            <li>4.战斗前回血持续到20级</li>
            <li>5.免费享受NPC治疗，即使重伤状态可以完全恢复</li>
            <li>6.不受服务器在线人数限制影响，随时畅玩</li>
            <li>7.选取道士职业时可以放生宝宝</li>
            <li>8.更多权益更新中</li>
        </ul>
        <a href="<?=$this->_l('cmd=about')?>">申请会员</a>
    </div>
    <?php $this->insert('includes/gonowmid', ['show_prev' => false]);?>
</div>