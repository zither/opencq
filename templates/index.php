<?php $this->layout('layout', ['pvp' => $pvp ?? [], 'tupo' => $tupo ?? null]) ?>

<div class="logo">
    王者归来
</div>
<div class="">
    <?php $this->insert('includes/message'); ?>
    <form class="" action="<?=$this->_l('cmd=do-login')?>" method="post">
        <div class="">
            <label class="" for="username">
                帐号
            </label>
            <input class="" id="username" type="text" placeholder="" name="username">
        </div>
        <div class="mt">
            <label class="" for="password">
                密码
            </label>
            <input class="" id="password" type="password" name="userpass" placeholder="">
        </div>
        <div class="mt">
            <button class="" type="submit" name="submit" value="登录">
                登录
            </button>
        </div>
    </form>
    <div class="mt">
        <a class="" type="button" href="<?=$this->_l('cmd=register')?>">
            注册帐号
        </a> . <a href="https://github.com/zither/opencq">开源代码</a> . <span class="color-green">讨论群:39387037</span>
    </div>
</div>
