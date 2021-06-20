<?php $this->layout('layout') ?>

<div class="flex flex-col">
    <div>
        <span class="font-bold">我的任务</span>
        <div class="">
            <?php if ($type == 3): ?>
                <a href="<?=$this->_l('cmd=mytask&type=1')?>">未完成</a> | 已完成
            <?php else: ?>
                未完成 | <a href="<?=$this->_l('cmd=mytask&type=3')?>">已完成</a>
            <?php endif;?>
        </div>
        <div class="">
            -<br>
            <?php foreach ($tasks as $task): ?>
                <div>[<?=$task['type']?>]
                    <?php if ($type != 3 && !empty($task['image'])) :?>
                    <img class="inline-block pb-1" src="images/<?=$task['image']?>.gif"/>
                    <?php endif;?>
                    <a href="<?=$this->_l('cmd=mytaskinfo&rwid=%d&no_op=1', $task['task_id'])?>"><?=$task['name']?></a>
                </div>
            <?php endforeach; ?>
            -<br>
        </div>
        <?php $this->insert('includes/gonowmid', ['gonowmid' => $gonowmid]); ?>
</div>
