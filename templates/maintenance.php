<?php $this->layout('layout') ?>

<div class="flex flex-col">
    <div class="">
        <span class="color-gray">[</span><span class="">通知</span><span class="color-gray">]</span>
    </div>
    <div class="">
        <ul>
            <li>
                <span class="color-red"><?=$message??'未知错误'?></span>
            </li>
        </ul>
    </div>
</div>