<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="<?php echo $this->getApp()->getRequest()->getBaseUrl(); ?>/assets/jquery-3.2.1.min.js"></script>
    <title>Document</title>
</head>
<body>
<h2>China-Area 行政区划级联视图</h2>
<hr>
<p>
    <?php $this->widget('\Widgets\ChinaArea', [
        'name' => 'aaaa',
        'defaultCode' => '820000',
    ]); ?>
</p>
<p>
    <?php $this->widget('\Widgets\ChinaArea', [
        'name' => 'cccc',
        'defaultCode' => '110101',
    ]); ?>
</p>
<p>
    <?php $this->widget('\Widgets\ChinaArea', [
            'name' => 'dddd',
            'defaultCode' => '421122',
    ]); ?>
</p>
</body>
</html>