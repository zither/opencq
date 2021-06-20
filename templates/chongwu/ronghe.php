<?php $this->layout('layout', ['pvp' => $pvp ?? [], 'tupo' => $tupo ?? null]) ?>

<div class="flex flex-col">
    <div>
        <span class="text-gray-600">[</span>
        <span class="font-bold">灵兽蛋</span>
        <span class="text-gray-600">]</span>
    </div>
    <div>一个奇怪的蛋，从内部发出一股淡淡的<?=$chongwu->color?>色柔光。</div>
    <div class='mt-4'>
        <form class="bg-white rounded px-0 pt-2 pb-2" action="<?=$this->_l('cmd=do-ronghe&id=' . $chongwu->cwid)?>" method="POST">
            <select name="did" class="py-1 px-2 bg-white border-gray-400 border rounded ">
                <option value="0">选择元魂</option>
                <?php foreach ($yuanhun as $v) :?>
                    <option value="<?=$v['id']?>"><?=$v['name']?>(<?=$v['pz']?>)</option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="flex mt-4 px-2 py-1 rounded border border-gray-400 btn">融合</button>
        </form>
    </div>

    <?php $this->insert('includes/message'); ?>
    <?php $this->insert('includes/gonowmid', ['gonowmid' => $gonowmid]);?>
</div>