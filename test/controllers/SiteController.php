<?php
/**
 * Link         :   http://www.phpcorner.net
 * User         :   qingbing<780042175@qq.com>
 * Date         :   2018-11-26
 * Version      :   1.0
 */

namespace Controllers;


use Render\Abstracts\Controller;

class SiteController extends Controller
{
    /**
     * 测试入口
     * @throws \Helper\Exception
     * @throws \ReflectionException
     */
    public function actionIndex()
    {
        $this->render('index');
    }
}