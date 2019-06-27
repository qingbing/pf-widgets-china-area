<?php
/**
 * Link         :   http://www.phpcorner.net
 * User         :   qingbing<780042175@qq.com>
 * Date         :   2019-01-20
 * Version      :   1.0
 */

namespace Tools;


class ChinaArea
{
    /**
     * 获取包含上级代码的行政区划信息
     * @param string $code
     * @return array|mixed
     * @throws \Exception
     */
    public static function getAreaInfo($code)
    {
        $cache = \PF::app()->getCache();
        if (null === ($output = $cache->get($code))) {
            $db = \PF::app()->getComponent('database');
            /* @var \Components\Db $db */
            $res = $db->getFindBuilder()
                ->setTable('pub_china_area')
                ->addWhereIn('code', [
                    $code,
                    substr($code, 0, 4) . '00',
                    substr($code, 0, 2) . '0000',
                ])
                ->queryAll();
            $output = [];
            foreach ($res as $re) {
                $output[$re['code']] = $re['name'];
            }
            ksort($output);
            $cache->set($code, $output);
        }
        return $output;
    }

    /**
     * @param string $code
     * @param string $glue
     * @return string
     * @throws \Exception
     */
    static public function getAddress($code, $glue = ',')
    {
        return implode($glue, self::getAreaInfo($code));
    }
}