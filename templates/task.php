<?php $this->layout('layout', ['pvp' => $pvp ?? [], 'tupo' => $tupo ?? null]) ?>

<?php $this->insert('includes/message');?>

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
                <div class="mt-4">
                    要求：<br/>
                    <?php foreach ($targets as $target): ?>
                        <?php if ($task->type ==  1):?>
                            收集<?=$target['name']?> x<?=$target['count']?> <br/>
                        <?php elseif($task->type == 2): ?>
                            击杀<?=$target['name']?> x<?=$target['count']?><br/>
                        <?php elseif($task->type == 3): ?>
                            去找<?=$target['name']?> <br/>
                        <?php endif;?>
                    <?php endforeach; ?>
                </div>
                <?php if (!empty($items)):?>
                    <p>奖励：</p>
                    <?php foreach ($items as $item): ?>
                        <div>
                            <?php if (isset($item['info_link'])): ?>
                                <span class="<?=$item['class']?>"><a href="?cmd=<?=$item['info_link']?>" class=""><?=$item['ui_name'] ?: $item['name']?></a></span> <span class="color-gray">x<?=$item['count']?></span>
                            <?php else: ?>
                                <?=$item['ui_name'] ?? $item['name']?> <span class="color-gray">x<?=$item['count']?></span>
                            <?php endif; ?>
                        </div>
                    <?php endforeach;?>
                <?php endif;?>
                <div class="mt-4">
                    <?php if (!isset($no_op) /* 模板共用，该参数非常重要 */) :?>
                        <?php if ($ptask->id): ?>
                            <?php if ($ptask->status == 3): ?>
                                <p class="mb-2 text-green-600 font-bold">任务已完成</p>
                            <?php elseif ($ptask->status == 2): ?>
                                当前进度: <br/>
                                <?php foreach ($conditions as $v):?>
                                    <p class="<?=$v['amount'] >= $v['required_amount'] ? 'color-green' : 'color-red'?>"><?=$v['target_name']?>(<?=$v['amount']?>/<?=$v['required_amount']?>)</p>
                                <?php endforeach; ?>
                                <a class="" href="?cmd=<?=$tijiaorw?>">提交</a>
                            <?php elseif($task->toId == $nid): ?>
                                <a class="" href="?cmd=<?=$tijiaorw?>">提交</a>
                            <?php endif;?>
                        <?php else: ?>
                            <a class=""  href="?cmd=<?=$jieshourw?>">接受</a>
                        <?php endif;?>
                    <?php elseif ($ptask->id && $ptask->taskId) : ?>
                        <?php if ($ptask->status== 3): ?>
                            <p class="mb-2 text-green-600 font-bold">任务已完成</p>
                        <?php elseif ($task->mode != 3): ?>
                            当前进度: <br/>
                            <?php foreach ($conditions as $v):?>
                                <p class="<?=$v['amount'] >= $v['required_amount'] ? 'color-green' : 'color-red'?>"><?=$v['target_name']?>(<?=$v['amount']?>/<?=$v['required_amount']?>)</p>
                            <?php endforeach; ?>
                        <?php endif;?>
                    <?php endif; ?>
                </div>
                <?php $this->insert('includes/gonowmid'); ?>
            <?php endif;?>
        </div>
    </div>
