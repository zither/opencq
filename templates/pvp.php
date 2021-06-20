<?php $this->layout('layout', ['pvp' => $pvp ?? [], 'tupo' => $tupo ?? null]) ?>

<?php $this->insert('includes/message'); ?>

<div class="flex flex-col" id="main-container">
    <span class="color-gray">=====战斗=====</span><br/>
    <div class="grid grid-cols-2 grid-flow-row gap-1">
        <?php foreach ($defenders as $defender): ?>
            <div class="attacker <?=$defender->hp <= 0 ? 'color-gray' : ''?>" id="<?=$defender->getUniqueId()?>">
                <div><span class="color-blue">Lv.<?=$defender->level?></span> · <span id="<?=$defender->getUniqueId()?>_name"><?=$defender->name?> </span> <?=$defender->hp <= 0 ? '(重伤)' : ''?></div>
                <div class="">生命:(<div class="hpys" style="display: inline" id="<?=$defender->getUniqueId()?>_hp"><?=$defender->column('hp')?></div>/<div class="hpys" style="display: inline" id="<?=$defender->getUniqueId()?>_maxhp"><?=$defender->column('max_hp')?></div>)<?=$this->getMessage('gphurt')?> <?=$this->hasMessage('pvebj') ? '(暴击)' : ''?><br/></div>
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
                <a class="mr-1" href="<?=$this->_l('cmd=do-pvp&id=%d', $combatStatus->id)?>">攻击</a>
                <a class="mr-1" href="<?=$this->_l("cmd=do-pvp&action=auto&id=%d", $combatStatus->id)?>">自动</a>
            </li>
        </ul>
    </div>
    <div>
        <div class="my-4">
            <a class="px-2 py-1 rounded border border-gray-400 text-blue-500" href="<?=$this->_l('cmd=select-combat-skill&from=2&cid=%d', $combatStatus->id)?>">选择技能</a>
            <?php foreach($skills as $v) : ?>
                <?php if ($v['type'] == 0): ?>
                    <a class="disabled-link px-2 py-1 text-gray-600 hover:text-gray-600 rounded border border-gray-400" title="功法不匹配，无法使用"><?=$v['name']?></a>
                <?php elseif ($v['type'] == 1): ?>
                    <a class="px-2 py-1 rounded border border-gray-400 text-blue-500" href="<?=$this->_l('cmd=select-combat-skill&from=2&cid=%d', $combatStatus->id)?>"><?=$v['name']?></a>
                <?php else: ?>
                    <a class="px-2 py-1 rounded border border-gray-400 text-blue-500" href="<?=$this->_l('cmd=do-pvp&id=%d&action=skill&aid=%d&nowmid=%d', $combatStatus->id, $v['id'], $player->nowmid)?>"><?=$v['name']?></a>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
        <div class="my-4">
            <a class="px-2 py-1 rounded border border-gray-400 text-blue-500" href="<?=$this->_l('cmd=select-combat-item&from=2&cid=%d', $combatStatus->id)?>">选择物品</a>
            <?php foreach($medicines as $v) : ?>
                <?php if ($v['type'] == 0): ?>
                    <a class="disabled-link px-2 py-1 text-gray-600 hover:text-gray-600 rounded border border-gray-400" title="数量不足，无法使用"><?=$v['name']?></a>
                <?php elseif ($v['type'] == 1): ?>
                    <a class="px-2 py-1 rounded border border-gray-400 text-blue-500" href="<?=$this->_l('cmd=select-combat-item&from=2&cid=%d', $combatStatus->id)?>"><?=$v['name']?></a>
                <?php else: ?>
                    <a class="px-2 py-1 rounded border border-gray-400 text-blue-500" href="<?=$this->_l('cmd=do-pvp&id=%d&action=item&aid=%d&nowmid=%d', $combatStatus->id, $v['id'], $player->nowmid)?>"><?=$v['name']?></a>
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