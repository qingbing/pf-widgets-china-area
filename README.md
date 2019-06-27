# pf-widgets-china-area
## 描述
渲染部件——中国行政区划代码及级联组件（php+js）

## 数据来源
- http://www.mca.gov.cn/article/sj/xzqh/2018/201804-12/20181101021046.html

## 注意事项
- 该小部件基于"qingbing/php-html"，"qingbing/php-render"开发
- 内部的area.js可以在引入"jQuery"的情况下直接拷贝单独使用
- 如果使用 "\Tools\ChinaArea"， 需要引入"qingbing/php-database"
  - 代码里的使用的数据库配置为 "application.database" ,因此，务必在application中配置明为"database"的数据库数据
  - 需要在数据库中手动执行"src/source/pub_china_area.sql"
- 区域使用的源数据来源于"民政部网站"，被拷贝在"test/source/code.txt"里
- 对于直接使用的项目，js资源无需手动管理，参考"qingbing/php-assets-manager"

## 使用方法
### 1. widget 的使用
#### 1.1 普通区域地址
```php
<?php $this->widget('\Widgets\ChinaArea', [
    'name' => 'aaaa',
    'defaultCode' => '820000',
]); ?>
```

#### 1.2 模型区域地址
```php
<?php $this->widget('\Widgets\ChinaArea', [
    'model' => $model,
    'name' => 'aaaa',
]); ?>

```
### 2. tools 的使用
```php
$code = $this->getApp()->getRequest()->getParam('code');
$info = \Tools\ChinaArea::getAreaInfo($code);
var_dump($info);
$address = \Tools\ChinaArea::getAddress($code);
var_dump($address);
```

## ====== 异常代码集合 ======

异常代码格式：1028 - XXX - XX （组件编号 - 文件编号 - 代码内异常）
```
 - 102800101 : 未设置区域的"{name}"
```