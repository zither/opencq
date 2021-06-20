<?php $this->layout('layout', ['pvp' => $pvp ?? [], 'tupo' => $tupo ?? null]) ?>

<?php $this->insert('includes/message'); ?>

<div class="flex flex-col">
    <div class="">
        <span class="color-gray">[</span><span class="">战斗策略</span><span class="color-gray">]</span>
    </div>
    <div class="">
        <form class="" action="<?=$this->_l('cmd=add-combat-condition')?>" method="post">
            <div class="">
                <label for="target">比较对象:</label>
                <select name="target" id="target">
                    <?php foreach ($targets as $k => $v): ?>
                        <option value="<?=$k?>" <?=($k == $target) ? 'selected' : ''?>><?=$v?></option>
                    <?php endforeach;?>
                </select>
            </div>
            <?php if ($target > 1):?>
            <div class="">
                <label for="target-num-op">对象数量:</label>
                <select name="target_num_op" id="target-num-op">
                    <?php foreach ($operations as $k => $v): ?>
                        <option value="<?=$v?>"><?=$v?></option>
                    <?php endforeach;?>
                </select>
                <input name="target_num" value="0" class="inline-block" style="width: 5em"/>
            </div>
            <?php endif;?>
            <div>
                <label for="target-property">比较属性:</label>
                <select name="target_property" id="target-property">
                    <?php if ($target == 0):?>
                    <option value="">无条件</option>
                    <?php endif;?>
                    <?php foreach ($properties as $k => $v): ?>
                        <option value="<?=$k?>"><?=$v?></option>
                    <?php endforeach;?>
                </select>
            </div>
            <div>
                <label for="operation">比较类型:</label>
                <select name="operation" id="operation">
                    <?php if ($target == 0):?>
                    <option value="">无</option>
                    <?php endif;?>
                    <?php foreach ($operations as $k => $v): ?>
                        <option value="<?=$v?>"><?=$v?></option>
                    <?php endforeach;?>
                </select>
                <input name="num" value="0" class="inline-block" style="width: 5em"/>
            </div>
            <div>
                <label for="selection-type">操作类型:</label>
                <select name="selection_type" id="selection-type">
                    <?php foreach ($types as $k => $v): ?>
                        <option value="<?=$k?>"><?=$v?></option>
                    <?php endforeach;?>
                </select>
            </div>
            <div>
                <label for="selection">操作对象:</label>
                <select name="selection_id" id="selection-id">
                    <?php foreach ($skills as $k => $v): ?>
                        <option value="<?=$v['id']?>"><?=$v['name']?></option>
                    <?php endforeach;?>
                </select>
            </div>
            <div class="mt">
                <button class="" type="submit" name="submit">
                    保存
                </button>
            </div>
        </form>
    </div>
    <?php $this->insert('includes/gonowmid');?>
</div>

<?php $this->push('scripts');?>
<script>
    let targets = [
      '<?=$this->_l('cmd=show-add-combat-condition')?>',
      '<?=$this->_l('cmd=show-add-combat-condition&target=1')?>',
      '<?=$this->_l('cmd=show-add-combat-condition&target=2')?>',
      '<?=$this->_l('cmd=show-add-combat-condition&target=3')?>'
    ];
    let items = <?=json_encode($items, JSON_UNESCAPED_UNICODE)?>;
    let skills = <?=json_encode($skills, JSON_UNESCAPED_UNICODE)?>;
    document.getElementById('target').addEventListener('change', function (){
      let target = targets[this.value];
      if (target) {
        window.location.replace(target);
      }
    });
    document.getElementById('selection-type').addEventListener('change', function (){
      console.log(this.value);
      let options;
      if (this.value === "1") {
        options = skills;
      } else {
        options = items;
      }
      let str = ""
      for (let option of options) {
        str += `<option value="${option.id}">${option.name}</option>`
      }
      document.getElementById("selection-id").innerHTML = str;
    });
</script>
<?php $this->stop(); ?>
