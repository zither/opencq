<?php $this->layout('layout', ['pvp' => $pvp ?? [], 'tupo' => $tupo ?? null]) ?>

<?php $this->insert('includes/message'); ?>

<div class="flex flex-col">
    <div class="">
        <span class="text-gray-600">[</span><span class="color-red">错误</span><span class="text-gray-600">]</span>
    </div>
    <div class="">
        <p>刷新过快，请设置至少1秒延时。</p>
        <a href="<?=$this->_l($cmd)?>">继续操作</a>
    </div>

</div>