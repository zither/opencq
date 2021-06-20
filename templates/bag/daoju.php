<?php $this->layout('layout', ['pvp' => $pvp ?? [], 'tupo' => $tupo ?? null]) ?>

<?php $this->insert('includes/message'); ?>

<div class="flex flex-col">
    <div>我的背包</div>
    <div>元宝: <?=$player->uczb?></div>
    <div>金币: <?=$player->uyxb?></div>
    <div>负重: <?=$count?>/<?=$player->liftingCapacity?></div>
    <div class="mt-2">
        <a href="<?=$this->_l('cmd=getbagzb')?>">装备</a> | 道具 | <a href="<?=$this->_l('cmd=getbagyp')?>">药品</a>
    </div>
    <div>
        <?php if (!empty($items)):?>
            -<br>
            <ul>
                <?php foreach ($items as $k => $v):?>
                    <li>
                        [<?=$k+1?>] <a class="overflow-hidden" href="?cmd=<?=$v['info_link']?>"><?=$v['ui_name']?></a> <span class="color-gray">x<?=$v['amount']?></span>
                        <?php if ($v['is_sellable']): ?>
                            <a href="javascript:void(0);"  class="cursor-pointer" onclick="show_menu(this)" data-name="<?=$v['name']?>" data-price="<?=$v['price']?>" data-quality="<?=$v['quality']?>" data-one="<?=$v['sell_one']?>" data-five="<?=$v['sell_five']?>" data-ten="<?=$v['sell_ten']?>">回收</a>
                        <?php endif;?>
                    </li>
                <?php endforeach;?>
            </ul>
            -<br>
        <?php endif;?>
    </div>
    <div id="menu" class="hidden">
        <p class=""><span id="menu-name">待出售物品</span> <span class="">(单价:<span id="menu-price">单价</span>)<span></span></p>
        <a class="" id="menu-one">卖一</a>
        <a class="" id="menu-five">卖五</a>
        <a class="" id="menu-ten">卖十</a>
        <a class="cursor-pointer" onclick="hide_menu()">取消</a>
        <br>-<br>
    </div>
    <?php if (!empty($previous_page) || !empty($next_page)):?>
        <div class="mt-4 flex flex-row">
            <?php if (isset($previous_page)): ?>
                <a class="px-4 py-1 rounded bg-gray-200 " href="<?=$this->_l('cmd=getbagdj&page=%d', $previous_page)?>">上一页</a>
            <?php endif;?>
            <?php if (isset($next_page)): ?>
                <a class="px-4 py-1 rounded bg-gray-200 " href="<?=$this->_l('cmd=getbagdj&page=%d', $next_page)?>">下一页</a>
            <?php endif;?>
        </div>
    <?php endif;?>
    <?php $this->push('scripts');?>
    <script>
        function show_menu(element) {
          let flash = document.getElementById('flash-message');
          if (flash) {
            flash.classList.add('hidden');
          }
          document.getElementById('menu-name').innerHTML = element.dataset.name;
          document.getElementById('menu-price').innerHTML = element.dataset.price;
          document.getElementById('menu-name').className = '';
          // document.getElementById('menu-name').classList.add('quality-' + element.dataset.quality);
          document.getElementById('menu-one').href = '?cmd=' + element.dataset.one;
          document.getElementById('menu-five').href = '?cmd=' + element.dataset.five;
          document.getElementById('menu-ten').href = '?cmd=' + element.dataset.ten;
          document.getElementById('menu').classList.remove('hidden');
        }
        function hide_menu() {
          document.getElementById('menu').classList.add('hidden');
        }
    </script>
    <?php $this->end();?>
    <?php $this->insert('includes/gonowmid');?>
</div>