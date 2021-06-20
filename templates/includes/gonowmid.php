<div class="inline-block">
    <?php if (!isset($show_prev) || $show_prev): ?>
    <a class="link mr-1" href="<?=$this->_l($this->event()->lastAction())?>">返回上级</a> <br/>
    <?php endif;?>
    <?php if (!isset($show_return) || $show_return): ?>
    <a href="<?=$this->_l('cmd=gomid')?>">返回游戏</a>
    <?php endif;?>
</div>