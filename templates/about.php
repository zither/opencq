<?php $this->layout('layout', ['pvp' => $pvp ?? [], 'tupo' => $tupo ?? null]) ?>

<?php $this->insert('includes/message'); ?>

<div class="flex flex-col">
    <div class="">
        <span class="text-gray-600">[</span><span class="">联系</span><span class="text-gray-600">]</span>
    </div>
    <div class="">
        <ul>
            <li>
                <span class="color-green">官方讨论群：39387037</span>
            </li>
        </ul>
    </div>
    <?php $this->insert('includes/gonowmid', ['show_prev' => false]);?>
</div>