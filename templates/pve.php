<?php $this->layout('layout', ['notifications' => $notifications ?? null]) ?>

<?php $this->insert('includes/message'); ?>

<div class="flex flex-col" id="main-container">
    <div class="grid grid-cols-2 grid-flow-row gap-1">
        <?php foreach ($defenders as $guaiwu): ?>
        <div class="attacker <?=$guaiwu->hp <= 0 ? 'color-gray' : ''?>" id="<?=$guaiwu->getUniqueId()?>">
            <div><span class="color-blue">Lv.<?=$guaiwu->level?></span> · <span id="<?=$guaiwu->getUniqueId()?>_name" class="<?=$guaiwu->monsterType == 3 ? 'color-red font-bold' : ''?>"><?=$guaiwu->name?> </span> <?=$guaiwu->hp <= 0 ? '(重伤)' : ''?></div>
            <div class="">生命:(<div class="hpys" style="display: inline" id="<?=$guaiwu->getUniqueId()?>_hp"><?=$guaiwu->column('hp')?></div>/<div class="hpys" style="display: inline" id="<?=$guaiwu->getUniqueId()?>_maxhp"><?=$guaiwu->column('max_hp')?></div>)<?=$this->getMessage('gphurt')?> <?=$this->hasMessage('pvebj') ? '(暴击)' : ''?><br/></div>
        </div>
        <?php endforeach; ?>
    </div>
    <div class="my-4">
        <hr class="hr" align="left">
    </div>
    <div class="grid grid-cols-2 grid-flow-row gap-1">
        <?php foreach ($attackers as $attacker): ?>
        <div class="attacker <?=$attacker->hp <= 0 ? 'color-gray' : ''?>" id="<?=$attacker->getUniqueId()?>">
            <div><span class="color-blue">Lv.<?=$attacker->level?></span> · <span id="<?=$attacker->getUniqueId()?>_name"><?=$attacker->name?></span> <?=$attacker->hp <= 0 ? '(重伤)' : ''?></div>
            <div class="">生命:(<div class="hpys" style="display: inline" id="<?=$attacker->getUniqueId()?>_hp" ><?=$attacker->column('hp')?></div>/<div class="hpys" style="display: inline" id="<?=$attacker->getUniqueId()?>_maxhp"><?=$attacker->column('max_hp')?></div>)<?=$this->getMessage('phurt')?> <?=$this->getMessage('pvexx')?><br/></div>
        </div>
        <?php endforeach; ?>
    </div>
    <div class="mt">
        <ul class="inline-flex">
            <li>
                <a class="mr-1"  href="<?=$this->_l('cmd=runaway&cid=%d', $combatStatus->id)?>">逃跑</a>
                <?php if (!$is_member ?? false):?>
                    <a class="mr-1" href="<?=$this->_l('cmd=do-pve&id=%d', $combatStatus->id)?>">攻击</a>
                    <a class="mr-1" href="<?=$this->_l("cmd=do-pve&action=auto&id=%d", $combatStatus->id)?>">自动</a>
                <?php endif;?>
            </li>
        </ul>
    </div>
    <div>
        <div class="">
            <a class=" " href="<?=$this->_l('cmd=select-combat-skill&from=1&cid=%d', $combatStatus->id)?>">选择技能</a>
            <?php foreach($skills as $v) : ?>
                <?php if ($v['type'] == 0): ?>
                    <a class="disabled-link color-gray" title="该技能无法使用"><?=$v['name']?></a>
                <?php elseif ($v['type'] == 1): ?>
                    <a class="" href="<?=$this->_l('cmd=select-combat-skill&from=1&cid=%d', $combatStatus->id)?>"><?=$v['name']?></a>
                <?php else: ?>
                    <?php if ($is_member):?>
                        <a class="" href="<?=$this->_l('cmd=do-member-pve&action=skill&aid=%d&id=%d&nowmid=%d', $v['id'], $combatStatus->id, $player->nowmid)?>"><?=$v['name']?></a>
                    <?php else:?>
                        <a class="" href="<?=$this->_l('cmd=do-pve&action=skill&aid=%d&id=%d&nowmid=%d', $v['id'], $combatStatus->id, $player->nowmid)?>"><?=$v['name']?></a>
                    <?php endif;?>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
        <div class="">
            <a class="" href="<?=$this->_l('cmd=select-combat-item&from=1&cid=%d', $combatStatus->id)?>">选择物品</a>
            <?php foreach($medicines as $v) : ?>
                <?php if ($v['type'] == 0): ?>
                    <a class="disabled-link color-gray" title="数量不足，无法使用"><?=$v['name']?></a>
                <?php elseif ($v['type'] == 1): ?>
                    <a class="" href="<?=$this->_l('cmd=select-combat-item&from=1&cid=%d', $combatStatus->id)?>"><?=$v['name']?></a>
                <?php else: ?>
                    <?php if ($is_member): ?>
                        <a class="" href="<?=$this->_l('cmd=do-member-pve&action=item&aid=%d&id=%d&nowmid=%d', $v['id'], $combatStatus->id, $player->nowmid)?>"><?=$v['name']?></a>
                    <?php else:?>
                        <a class="" href="<?=$this->_l('cmd=do-pve&action=item&aid=%d&id=%d&nowmid=%d', $v['id'], $combatStatus->id, $player->nowmid)?>"><?=$v['name']?></a>
                    <?php endif;?>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
    <div>
        <?php if (!empty($logs)):?>
            <div class="mt-1"><a class="color-blue">战斗描述：</a></div>
            <?php foreach ($logs as $v): ?>
                <?php foreach ($v as $lines):?>
                    <div class="">
                        <?php foreach ($lines as $l):?>
                            <p class=""><?=$l?></p>
                        <?php endforeach;?>
                    </div>
                <?php endforeach;?>
            <?php endforeach;?>
        <?php endif;?>
    </div>
</div>