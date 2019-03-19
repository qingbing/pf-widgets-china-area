<?php
/**
 * Link         :   http://www.phpcorner.net
 * User         :   qingbing<780042175@qq.com>
 * Date         :   2019-01-21
 * Version      :   1.0
 */

namespace Widgets;


use Abstracts\OutputProcessor;
use Components\AssetsManager;
use Helper\Exception;
use Html;

class ChinaArea extends OutputProcessor
{
    /* @var boolean 保存js资源是否加载 */
    private static $_isLoadScript = false;
    /* @var \Abstracts\Model */
    public $model;
    /* @var string 隐藏域"name" */
    public $name;
    /* @var string 隐藏域"id" */
    public $hiddenId;
    /* @var string 隐藏域默认值 */
    public $defaultCode;

    /**
     * 加载js资源文件
     * @throws \Exception
     */
    public function init()
    {
        if (!self::$_isLoadScript) {
            self::$_isLoadScript = true;
            $src = dirname(__DIR__) . '/source/china_area.js';
            $baseUrl = AssetsManager::getInstance('assets-manager')->publish($src, 'chinaArea/area.js');
            \ClientScript::getInstance()->registerScriptFile("{$baseUrl}");
        }
    }

    /**
     * 运行组件
     * @throws \Exception
     */
    public function run()
    {
        $options = [];
        if (null !== $this->hiddenId) {
            $options['id'] = $this->hiddenId;
        }
        if (null !== $this->model) {
            if (null !== $this->defaultCode) {
                $options['value'] = $this->defaultCode;
            }
            $coding = Html::activeHiddenField($this->model, $this->name, $options);
        } else {
            $name = null === $this->name ? $this->hiddenId : $this->name;
            if (empty($name)) {
                throw new Exception(str_cover('未设置区域的"{name}"', [
                    '{name}' => $name,
                ]), 102800101);
            }
            $value = null === $this->defaultCode ? '' : $this->defaultCode;
            $coding = Html::hiddenField($name, $value, $options);
        }

        echo <<<EDO
<span class="CHINA-AREA" >{$coding}
<label class="CHINA-AREA-PROVINCE"><select><option value="">选择省份</option></select></label>
<label class="CHINA-AREA-CITY"><select><option value="">选择城市</option></select></label>
<label class="CHINA-AREA-DISTRICT"><select><option value="">选择乡镇</option></select></label>
</span>
EDO;
    }
}