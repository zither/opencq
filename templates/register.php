<?php $this->layout('layout', ['pvp' => $pvp ?? [], 'tupo' => $tupo ?? null]) ?>

<div class="logo">
    王者归来
</div>
<div class="">
    <?php $this->insert('includes/message'); ?>
    <form class="" action="<?=$this->_l('cmd=do-register')?>" method="post">
        <div class="">
            <label class="" for="username">
                帐号
            </label>
            <input class="" id="username" type="text" name="username">
        </div>
        <div class="mt">
            <label class="" for="password">
                密码
            </label>
            <input id="password" class="" type="password" name="userpass" placeholder="">
        </div>
        <div class="mt">
            <label class="" for="confirm-password">
                确认
            </label>
            <input id="confirm-password" class="" type="password" name="userpass2" placeholder="">
        </div>
        <div class="mt">
            <button class="" type="submit" name="submit" value="注册">
                注册
            </button>
        </div>
    </form>
    <div class="mt">
        <a class="" type="button" href="/">
            登录游戏
        </a> . <span class="color-green">讨论群:39387037</span>
    </div>
</div>
