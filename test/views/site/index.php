<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<ul>
    <li><a href="<?php echo $this->createUrl('/area/json'); ?>" target="_blank">获取区域JSON</a></li>
    <li><a href="<?php echo $this->createUrl('/area/sql'); ?>" target="_blank">获取区域Sql</a></li>
    <li><a href="<?php echo $this->createUrl('/area/widget'); ?>" target="_blank">widget使用</a></li>
    <li><a href="<?php echo $this->createUrl('/area/address', ['code'=>'110101']); ?>" target="_blank">地址获取"110101"</a></li>
    <li><a href="<?php echo $this->createUrl('/area/address', ['code'=>'130105']); ?>" target="_blank">地址获取"130105"</a></li>
    <li><a href="<?php echo $this->createUrl('/area/address', ['code'=>'820000']); ?>" target="_blank">地址获取"110101"</a></li>
</ul>
</body>
</html>