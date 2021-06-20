<?php $this->layout('layout', ['pvp' => $pvp ?? [], 'tupo' => $tupo ?? null]) ?>

<div class="flex flex-col">
    <div>
        <span class="font-bold"><?=$clmid->mname?><?=$pvphtml?></span>
        <div class="mt-2">
            <?=$clmid->midinfo?><br/>
        </div>
        <?php if (empty($show)): ?>
        <div class="my-2 text-gray-600">
            你正在探索中，还需要等待 <span class="font-bold text-blue-500" id="counter"><?=$seconds?></span> 秒。
        </div>
        <?php else: ?>
            <?php $this->insert('includes/resources', ['resources' => $resources]);?>
            <div class="inline-block my-4">
                <a class="mr-4" href="<?=$this->_l('cmd=do-search&mid=%d', $clmid->mid)?>">继续搜寻</a>
                <a href="<?=$this->_l('cmd=gomid&newmid=%d', $player->nowmid)?>">返回游戏</a>
            </div>
        <?php endif;?>
    </div>
    <?php if (empty($show)): ?>
        <?php $this->push('scripts'); ?>
        <script>
          let counter = document.getElementById('counter');
          let current = parseInt(counter.innerHTML);
          let countdown = setInterval(function(){
            counter.innerHTML = parseInt(counter.innerHTML) - 1;
          }, 1000);
          setTimeout(function() {
            clearInterval(countdown);
            window.location.reload();
          }, (current + 1) * 1000);
        </script>
        <?php $this->end();?>
    <?php endif; ?>
</div>