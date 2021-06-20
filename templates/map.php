<?php $this->layout('layout', ['pvp' => $pvp ?? []]) ?>
<div class="flex flex-col">
    <div>
        <span class="text-gray-600">[</span><span class="font-bold">区域地图</span><span class="text-gray-600">]</span> </div>
    <div class="">
        <table class="text-center text-sm">
            <?php foreach ($map as $v): ?>
                <tr>
                    <?php foreach ($v as $n) :?>
                        <td>
                            <?php if (!empty($n)) :?>
                                <?=$n?>
                            <?php endif;?>
                        </td>
                    <?php endforeach;?>
                </tr>
            <?php endforeach;?>
        </table>
    </div>
    <?php $this->insert('includes/gonowmid');?>
</div>