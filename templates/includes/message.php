<?php if($this->hasMessage('message')): ?>
    <?php foreach ($this->getMessage('message') as $message): ?>
        <div id="flash-message" class="">[<span class="color-blue">消息</span>]<?=$message;?></div>
    <?php endforeach;?>
<?php endif; ?>
<?php if($this->hasMessage('success')): ?>
    <?php foreach ($this->getMessage('success') as $message): ?>
        <div id="flash-message" class="">[<span class="color-green">消息</span>]<?=$message;?></div>
    <?php endforeach;?>
<?php endif; ?>
<?php if($this->hasMessage('error')): ?>
    <?php foreach ($this->getMessage('error') as $message): ?>
        <div id="flash-message" class="">[<span class="color-red">消息</span>]<?=$message;?></div>
    <?php endforeach;?>
<?php endif; ?>
<?php if($this->hasMessage('tips')): ?>
    <?php foreach ($this->getMessage('tips') as $message): ?>
        <div id="flash-message" class=""><?=$message;?></div>
    <?php endforeach;?>
<?php endif; ?>
