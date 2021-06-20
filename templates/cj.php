<?php $this->layout('layout', ['pvp' => $pvp ?? [], 'tupo' => $tupo ?? null]) ?>

<?php $this->insert('includes/message'); ?>

<form action="<?=$this->_l('cmd=cjplayer&token=%d', $token)?>" method="POST">
    角色名称：
    <p><input class="border border-blue-900" type="text" name="username" maxlength="7"></p>
    <p class="my-2"><label>男：<input type="radio" name="sex" value="1" checked></label>
        <label>女：<input type="radio" name="sex" value="2"></label>
    </p>
    <button class="px-2 rounded-sm border border-gray-700 color-blue" type="submit">创建</button>
</form>
