<?php $this->layout('layout', ['pvp' => $pvp ?? [], 'tupo' => $tupo ?? null]) ?>

<?php $this->insert('includes/message'); ?>

<div class="flex flex-col">
    <div class="">
        [创建队伍]
    </div>
    <div class="">
        <form action="<?=$this->_l("cmd=create-party")?>" method="POST">
            <label for="name">名称：</label><br/>
            <input id="name" class="border border-blue-900 px-1" type="text" name="name" value=""><br/>
            <div class="mt">
                <button class="" type="submit">确定</button>
            </div>
        </form>
    </div>
    <?php $this->insert('includes/gonowmid', ['show_prev' => false]);?>
</div>