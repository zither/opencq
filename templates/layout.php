<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="utf-8" content="width=device-width,initial-scale=1,user-scalable=no" name="viewport">
    <link rel="icon" type="image/png" sizes="32x32" href="/images/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/images/favicon-16x16.png">
    <title>王者归来 - Wap文字游戏</title>
    <link rel="stylesheet" href="css/gamecss.css?v=<?=DEBUG? time(): '202012290849'?>">
</head>
<body class="">
<div class="">
    <div class="main p-2 rounded max-w-sm mx-auto">
        <?php if (!empty($notifications)): ?>
            <div class="mb-2">
                <?php foreach ($notifications as $v):?>
                    <?php if ($v['type'] == 1):?>
                        [<span class="color-red">系统</span>] <?=$v['content']?><br/>
                    <?php elseif ($v['type'] == 2):?>
                        [<span class="color-blue">消息</span>] <?=$v['content']?><br/>
                    <?php elseif ($v['type'] == 3):?>
                        [<span class="color-green">队伍</span>] <?=$v['content']?><br/>
                    <?php endif;?>
                <?php endforeach;?>
            </div>
        <?php endif; ?>
        <?=$this->section('content')?>
    </div>
</div>
<?=$this->section('scripts')?>
</body>
</html>