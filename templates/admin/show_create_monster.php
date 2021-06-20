<?php $this->layout('layout') ?>

<?php $this->insert('includes/message'); ?>

<div class="w-full">
    <form class="bg-white rounded px-0 pt-2 pb-8 mb-4" action="<?=$this->_l('cmd=admin-create-monster')?>" method="post">
        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" type="hidden" name="id" value="<?=$monster['id'] ?? 0?>">
        <div class="mb-2">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="username">
                怪物名称
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" type="text" name="name" value="<?=$monster['name'] ?? ''?>">
        </div>
        <div class="mb-2">
            <label class="block text-gray-700 text-sm font-bold mb-2">
                说明
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" name="info" value="<?=$monster['info'] ?? ''?>" >
        </div>
        <div class="mb-2">
            <label class="block text-gray-700 text-sm font-bold mb-2">
                性别
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" name="sex"  value="<?=$monster['sex'] ?? ''?>">
        </div>
        <div class="mb-2">
            <label class="block text-gray-700 text-sm font-bold mb-2">
                level
            </label>
            <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" name="level" id="level">
                <?php for($i = 1; $i <= 120; $i++): ?>
                    <?php if (isset($monster['level']) && $i == $monster['level']) :?>
                        <option value="<?=$i?>" selected data-uri="<?=$this->_l('cmd=admin-show-create-monster&id=%d&level=%d', $monster['id'] ?? 0, $i)?>"><?=$i?></option>
                    <?php else: ?>
                        <option value="<?=$i?>" data-uri="<?=$this->_l('cmd=admin-show-create-monster&id=%d&level=%d', $monster['id'] ?? 0, $i)?>"><?=$i?></option>
                    <?php endif; ?>
                <?php endfor;?>
            </select>
        </div>
        <div class="mb-2">
            <label class="block text-gray-700 text-sm font-bold mb-2">
                掉落道具
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" name="gdj"  value="<?=$monster['gdj'] ?? ''?>">
        </div>
        <div class="mb-2">
            <label class="block text-gray-700 text-sm font-bold mb-2">
                是否群体怪
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" name="is_group" value="<?=$monster['is_group'] ?? 0?>" >
        </div>
        <div class="mb-2">
            <label class="block text-gray-700 text-sm font-bold mb-2">
                是否主动怪
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" name="is_aggressive"  value="<?=$monster['is_aggressive'] ?? 0?>">
        </div>
        <div class="mb-2">
            <label class="block text-gray-700 text-sm font-bold mb-2">
                怪物类型
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" name="type"  value="<?=$monster['type'] ?? 1?>">
        </div>
        <div class="mb-2">
            <label class="block text-gray-700 text-sm font-bold mb-2">
                怪物属性
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" name="flags"  value="<?=$monster['flags'] ?? 0?>">
        </div>
        <div class="mb-2">
            <label class="block text-gray-700 text-sm font-bold mb-2">
               怪物血量
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" name="hp"  value="<?=$monster['hp'] ?? 10?>">
        </div>
        <div class="mb-2">
            <label class="block text-gray-700 text-sm font-bold mb-2">
                怪物蓝量
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" name="mp"  value="<?=$monster['mp'] ?? 10?>">
        </div>
        <div class="mb-2">
            <label class="block text-gray-700 text-sm font-bold mb-2">
                怪物威压
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" name="baqi"  value="<?=$monster['baqi'] ?? 0?>">
        </div>
        <div class="mb-2">
            <label class="block text-gray-700 text-sm font-bold mb-2">
                怪物物攻
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" name="wugong"  value="<?=$monster['wugong'] ?? 10?>">
        </div>
        <div class="mb-2">
            <label class="block text-gray-700 text-sm font-bold mb-2">
                怪物法攻
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" name="fagong"  value="<?=$monster['fagong'] ?? 0?>">
        </div>
        <div class="mb-2">
            <label class="block text-gray-700 text-sm font-bold mb-2">
                怪物物防
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" name="wufang" value="<?=$monster['wufang'] ?? 0?>" >
        </div>
        <div class="mb-2">
            <label class="block text-gray-700 text-sm font-bold mb-2">
                怪物法防
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" name="fafang"  value="<?=$monster['fafang'] ?? 0?>">
        </div>
        <div class="mb-2">
            <label class="block text-gray-700 text-sm font-bold mb-2">
                怪物闪避
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" name="shanbi"  value="<?=$monster['shanbi'] ?? 0?>">
        </div>
        <div class="mb-2">
            <label class="block text-gray-700 text-sm font-bold mb-2">
                怪物命中
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" name="mingzhong"  value="<?=$monster['mingzhong'] ?? 0?>">
        </div>
        <div class="mb-2">
            <label class="block text-gray-700 text-sm font-bold mb-2">
                怪物暴击
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" name="baoji"  value="<?=$monster['baoji'] ?? 0?>">
        </div>
        <div class="mb-2">
            <label class="block text-gray-700 text-sm font-bold mb-2">
                怪物神明
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" name="shenming"  value="<?=$monster['shenming'] ?? 0?>">
        </div>
        <div class="mb-2">
            <label class="block text-gray-700 text-sm font-bold mb-2">
                怪物经验
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" name="exp" value="<?=$monster['exp'] ?? 0?>" >
        </div>
        <div class="mb-2">
            <label class="block text-gray-700 text-sm font-bold mb-2">
                是否私有
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" name="is_private"  value="<?=$monster['is_private'] ?? 0?>">
        </div>
        <div class="mb-2">
            <label class="block text-gray-700 text-sm font-bold mb-2">
                最大击杀次数
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" name="max_amount" value="<?=$monster['max_amount'] ?? 0?>">
        </div>
        <div class="mb-2">
            <label class="block text-gray-700 text-sm font-bold mb-2">
                怪物功法等级
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" name="manual_level_id"  value="<?=$monster['manual_level_id'] ?? 0?>">
        </div>
        <div class="mb-2">
            <label class="block text-gray-700 text-sm font-bold mb-2">
                怪物技能列表
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" name="skills"  value="<?=$monster['skills'] ?? 0?>">
        </div>
        <div class="flex items-center justify-between">
            <button class="bg-blue-500 hover:bg-blue-700 text-white py-1 px-4 rounded focus:outline-none focus:shadow-outline" type="submit" name="submit" value="登录">
                保存
            </button>
        </div>
    </form>
</div>
<div class="inline-block my-4">
    <a class="link mr-4" href="<?=$this->_l('cmd=admin-monster-list')?>">返回列表</a>
</div>

<?php $this->push('scripts');?>
    <script>
      document.getElementById('level').addEventListener('change', function() {
        let option = this.options[this.selectedIndex];
        let url = option.getAttribute('data-uri');
        window.location.replace(url);
      });
    </script>
<?php $this->end();?>
