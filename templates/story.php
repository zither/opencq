<?php $this->layout('layout', ['pvp' => $pvp ?? [], 'tupo' => $tupo ?? null]) ?>

<div class="flex flex-col">
    <div>
        <span class="font-bold"><?=$task->name?></span>
        <div class="">
            <?php if ($ptask->status != 3): ?>
                <div><?=$task_description?></div>
            <?php else: ?>
                <div><?=$task->summary?></div>
            <?php endif;?>
            <?php if (isset($p) && $p != -1):?>
                <div><a href="<?=$this->_l('cmd=task&nid=%d&rwid=%d&p=%d', $nid, $task->id, $p)?>">继续</a></div>
            <?php else: ?>
                <?php if (!empty($items)):?>
                    <p>获得物品：</p>
                    <?php foreach ($items as $item): ?>
                        <div>
                            <?php if (isset($item['info_link'])): ?>
                                <span class="<?=$item['class']?>"><a href="?cmd=<?=$item['info_link']?>" class="<?=$item['link_class'] ?? ''?>"><?=$item['name']?></a></span> <span class="text-gray-600">x<?=$item['count']?></span>
                            <?php else: ?>
                                <?=$item['name']?> <span class="text-gray-600">x<?=$item['count']?></span>
                            <?php endif; ?>
                        </div>
                    <?php endforeach;?>
                <?php endif;?>
                <?php $this->insert('includes/message');?>
                <div class="mt-4">
                    <?php if (!isset($no_op) /* 模板共用，该参数非常重要 */) :?>
                        <?php if ($ptask->id): ?>
                            <?php if ($ptask->status == 3): ?>
                                <p class="mb-2 text-green-600 font-bold">任务已完成</p>
                            <?php elseif ($ptask->status == 2): ?>
                                当前进度: <br/>
                                <?php foreach ($conditions as $v):?>
                                    <p class="<?=$v['amount'] >= $v['required_amount'] ? 'text-green-600' : 'text-red-600'?>"><?=$v['target_name']?>(<?=$v['amount']?>/<?=$v['required_amount']?>)</p>
                                <?php endforeach; ?>
                                <a class="mt-4 mr-2 border border-gray-400 px-2 rounded" href="?cmd=<?=$tijiaorw?>">提交</a>
                            <?php elseif($task->toId == $nid || $task->type == 4): ?>
                                <a class="mr-2 border border-gray-400 px-2 rounded" href="?cmd=<?=$tijiaorw?>">确定</a>
                            <?php endif;?>
                        <?php else: ?>
                            <a class="mr-2 border border-gray-400 px-2 rounded"  href="?cmd=<?=$jieshourw?>">接受</a>
                        <?php endif;?>
                    <?php elseif ($ptask->id && $ptask->taskId) : ?>
                        <?php if ($ptask->status== 3): ?>
                            <p class="mb-2 text-green-600 font-bold">任务已完成</p>
                        <?php elseif ($task->mode != 3): ?>
                            当前进度: <br/>
                            <?php foreach ($conditions as $v):?>
                                <p class="<?=$v['amount'] >= $v['required_amount'] ? 'text-green-600' : 'text-red-600'?>"><?=$v['target_name']?>(<?=$v['amount']?>/<?=$v['required_amount']?>)</p>
                            <?php endforeach; ?>
                        <?php endif;?>
                    <?php endif; ?>
                </div>
                <?php $this->insert('includes/gonowmid', ['gonowmid' => $gonowmid]); ?>
            <?php endif;?>
        </div>
    </div>
