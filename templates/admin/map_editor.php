<?php $this->layout('layout') ?>

<?php $this->insert('includes/message'); ?>

<div>
    <span><?=$location->name?></span>
     <span class=""><?=$pvphtml?></span>
     <a href="<?=$this->_l('cmd=show-loc&mid=%d', $location->id)?>">刷新</a>
    <?php if (!empty($location->info)):?>
    <div class="mt-2">
        <?=$location->info?><br/>
    </div>
    <?php endif;?>
</div>

<?php if (!empty($npcs)) :?>
    <!--NPC-->
    <div class="">
        <div class="">
            这里的NPC：
        <?php foreach ($npcs as $v): ?>
            <a class="mr-1 " href="">
                <?=$v['name']?>
            </a>
        <?php endforeach;?>
        </div>
    </div>
<?php endif;?>

<?php if (!empty($monsters)) :?>
    <!--Monsters-->
    <div class="">
        <div class="">
            这里的怪物：
            <?php foreach ($monsters as $v): ?>
                <a class="mr-1 " href="<?=$this->_l('cmd=admin-monster-list')?>">
                    <?=$v['name']?>
                </a>
            <?php endforeach;?>
        </div>
    </div>
<?php endif;?>

<?php if (!empty($ornaments)) :?>
    <!--Ornaments-->
    <div class="">
        <div class="">
            这里的摆件：
            <?php foreach ($ornaments as $v): ?>
                <a class="mr-1 " href="<?=$this->_l('cmd=admin-ornament-list')?>">
                    <?=$v['name']?>
                </a>
            <?php endforeach;?>
        </div>
    </div>
<?php endif;?>

<table class="text-center mt">
    <?php foreach ($directions as $m => $dir):?>
        <tr>
        <?php foreach ($dir as $n => $loc): ?>
            <td>
            <?php if (is_object($loc)): ?>
                <div class="bg-gray-200 rounded" title="<?=$loc->id?>">
                    <?php if ($loc->id == $location->id):?>
                        <span class="color-green font-bold"><?=$loc->name?></span>
                    <?php else:?>
                        <a class="" href="<?=$this->_l('cmd=show-loc&mid=%d',$loc->id)?>"><?=$loc->name?></a>
                    <?php endif;?>
                </div>
            <?php elseif (is_string($loc) && in_array($loc, ['—', '|'])): ?>
                <span class="color-gray"><?=$loc?></span>
            <?php elseif (is_string($loc) && in_array($loc, ['up', 'down', 'left', 'right'])): ?>
                <a class="color-red rounded" href="<?=$this->_l('cmd=show-create-loc&origin_mid=%d&direction=%s',$location->id, $loc)?>">创建</a>
            <?php else:?>
                <span class="inline-block"></span>
            <?php endif;?>
            </td>
        <?php endforeach;?>
        </tr>
    <?php endforeach;?>
</table>
<div class="mt">
    <a href="<?=$this->_l('cmd=allmap')?>">传送</a> .
    <a href="<?=$this->_l('cmd=show-area')?>">地图</a> .
    <a href="<?=$this->_l('cmd=admin-show-update-loc&id=%d', $location->id)?>">编辑</a> .
    <a href="<?=$this->_l('cmd=gomid')?>">退出</a>
</div>
<div class="mt">
    <?php foreach ($doors as $k => $v):?>
        <div>
        <?php if ($k == 'up'):?>
            北：
        <?php elseif ($k == 'left'): ?>
            西：
        <?php elseif ($k == 'right'): ?>
            东：
        <?php elseif ($k == 'down'): ?>
            南：
        <?php endif;?>
        <?php if (!empty($v)):?>
                <?=$v->name?>(<?=$v->id?>) <a href="<?=$this->_l('cmd=admin-loc-disconnect&dir=%s', $k)?>">断开</a>
        <?php else:?>
            <a href="<?=$this->_l('cmd=admin-loc-show-connections&dir=%s', $k)?>">连接</a>
        <?php endif;?>
        </div>
    <?php endforeach; ?>
</div>

<div class="mt">
    <div>
        <a href="<?=$this->_l('cmd=admin-npc-list')?>">NPC</a> .
        <a href="<?=$this->_l('cmd=admin-monster-list')?>">怪物</a> .
        <a href="<?=$this->_l('cmd=admin-ornament-list')?>">摆件</a> .
        <a href="<?=$this->_l('cmd=admin-condition-list')?>">条件</a>
    </div>
    <div>
        <a href="<?=$this->_l('cmd=admin-area-list')?>">区域</a> .
        <a href="<?=$this->_l('cmd=admin-area-list')?>">物品</a> .
        <a href="<?=$this->_l('cmd=admin-area-list')?>">装备</a> .
        <a href="<?=$this->_l('cmd=admin-area-list')?>">药品</a>
    </div>
</div>
