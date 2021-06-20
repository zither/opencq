<?php $this->layout('layout', ['pvp' => $pvp ?? [], 'tupo' => $tupo ?? null]) ?>

==========兑换页面==========
<form>
    <input type="hidden" name="cmd" value="<?=$cmd?>">
    兑换码:<br/>
    <input class="border border-gray-400" name="dhm"> <input class="border border-gray-400 py-0 px-4" type="submit" value="兑换"><br/><br/>
</form>
<?php if(isset($error)):?>
    <?=$error?>
<?php else: ?>
    <?php if (!empty($items)):?>
        <div>兑换<?=$dhm?>兑换码成功，获得：</div>
        <?php foreach ($items as $item):?>
            <div class="ml-2"><span class="<?=$item['class'] ?? ''?>"><?=$item['name']?></span> x<?=$item['count']?></div>
        <?php endforeach;?>
    <?php endif;?>
<?php endif;?>

<?php $this->insert('includes/gonowmid', ['gonowmid' => $gonowmid]);?>
