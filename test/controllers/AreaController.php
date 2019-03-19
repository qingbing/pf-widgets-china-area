<?php
/**
 * Link         :   http://www.phpcorner.net
 * User         :   qingbing<780042175@qq.com>
 * Date         :   2018-11-26
 * Version      :   1.0
 */

namespace Controllers;


use Helper\Exception;
use Render\Abstracts\Controller;

class AreaController extends Controller
{
    /**
     * 获取并返回行政区域数据
     * @return array
     * @throws Exception
     */
    protected function getData()
    {
        $file = dirname(dirname(__FILE__)) . '/source/code.txt';
        $fp = fopen($file, 'r');
        $R = [];
        while (!feof($fp)) {
            $line = trim(fgets($fp));
            if (empty($line)) {
                continue;
            }
            $tt = explode("\t", $line);
            if (2 !== count($tt)) {
                throw new Exception("{$line} 不是有效数据");
            }
            $R[$tt[0]] = $tt;
        }
        fclose($fp);
        return $R;
    }

    private $_json = [];
    private $_data = [];

    /**
     * 省份的处理
     * @param string $key
     * @param null|array $data
     */
    protected function province($key, $data = null)
    {
        if (!isset($this->_json[$key])) {
            $this->_json[$key] = [
                'code' => '',
                'name' => '',
                'data' => [],
            ];
        }
        if (null !== $data) {
            $this->_json[$key]['code'] = $data[0];
            $this->_json[$key]['name'] = $data[1];
            unset($this->_data[$data[0]]);
        }
    }

    /**
     * 城市的处理
     * @param string $key
     * @param null|mixed $data
     */
    protected function city($key, $data = null)
    {
        $province_code = substr($key, 0, -2);
        $this->province($province_code);
        if (!isset($this->_json[$province_code]['data'][$key])) {
            $this->_json[$province_code]['data'][$key] = [
                'code' => '',
                'name' => '',
                'data' => [],
            ];
        }
        if (null !== $data) {
            $this->_json[$province_code]['data'][$key]['code'] = $data[0];
            $this->_json[$province_code]['data'][$key]['name'] = $data[1];
            unset($this->_data[$data[0]]);
        }
    }

    /**
     * 县区的处理
     * @param string $key
     * @param array $data
     */
    protected function district($key, $data)
    {
        $city_code = substr($key, 0, -2);
        $this->city($city_code);
        $province_code = substr($city_code, 0, -2);
        $this->_json[$province_code]['data'][$city_code]['data'][$key] = [
            'code' => $data[0],
            'name' => $data[1],
        ];
        unset($this->_data[$data[0]]);
    }

    /**
     * 处理成json，供json调用
     * @throws Exception
     */
    public function actionJson()
    {
        $this->_data = $this->getData();
        foreach ($this->_data as $code => $datum) {
            $key = rtrim($datum[0], '0');
            if (1 === strlen($key) || 3 === strlen($key) || 5 === strlen($key)) {
                $key = "{$key}0";
            }
            $key = strval($key);
            $len = strlen($key);
            if (2 === $len) {
                $this->province($key, $datum);
                continue;
            }
            if (4 === $len) {
                $this->city($key, $datum);
                continue;
            }
            $this->district($key, $datum);
        }
        foreach ($this->_json as $key => &$item) {
            if (empty($item['data'])) {
                // 弥补没有第二、第三层的情况，如台湾省，香港特别行政区，澳门特别行政区
                $item['data'] = [
                    $item['code'] => [
                        'code' => $item['code'],
                        'name' => $item['name'],
                        'data' => [
                            $item['code'] => [
                                'code' => $item['code'],
                                'name' => $item['name'],
                            ]
                        ]
                    ]
                ];
            } else {
                foreach ($item['data'] as $subKey => &$subItem) {
                    if (empty($subItem['code'])) {
                        // 弥补没有第二层的区域，例如北京市，天津市
//                        $subItem['code'] = $item['code'];
//                        $subItem['name'] = $item['name'];

                        foreach ($subItem['data'] as $thirdCode => &$thirdItem) {
                            $thirdItem['data'] = [
                                $thirdItem['code'] => [
                                    'code' => $thirdItem['code'],
                                    'name' => $thirdItem['name'],
                                ]
                            ];
                            $item['data'][$thirdCode] = $thirdItem;
                        }
                        unset($thirdItem);
                        unset($item['data'][$subKey]);
                    }
                }
            }
        }

        $R = [];
        foreach ($this->_json as $key => &$item1) {
            $temp1 = [];
            foreach ($item1['data'] as $item2) {
                $temp2 = [];
                foreach ($item2['data'] as $item3) {
                    $temp2[$item3['code']] = [
                        $item3['name'],
                    ];
                }
                $temp1[$item2['code']] = [
                    $item2['name'],
                    $temp2,
                ];
            }
            $R[$item1['code']] = [
                $item1['name'],
                $temp1,
            ];
        }
        echo json_encode($R, JSON_UNESCAPED_UNICODE);
//        $R = [];
//        foreach ($this->_json as $key => &$item1) {
//            $temp1 = [];
//            foreach ($item1['data'] as $item2) {
//                $temp2 = [];
//                foreach ($item2['data'] as $item3) {
//                    array_push($temp2, [
//                        $item3['code'],
//                        $item3['name'],
//                    ]);
//                }
//                array_push($temp1, [
//                    $item2['code'],
//                    $item2['name'],
//                    $temp2,
//                ]);
//            }
//            array_push($R, [
//                $item1['code'],
//                $item1['name'],
//                $temp1,
//            ]);
//        }
//        echo json_encode($R, JSON_UNESCAPED_UNICODE);
    }

    /**
     * 创建行政代码入库
     * @throws Exception
     */
    public function actionSql()
    {
        $db = \PF::app()->getComponent('database');
        /* @var \Components\Db $db */
        $data = $this->getData();

        $query = $db->getInsertBuilder()
            ->setTable('pf_china_area')
            ->setMultiFields(['code', 'name'])
            ->setMultiData(array_values($data))
            ->getQuery();

        $KS = [];
        foreach ($query['multi-fields'] as $k) {
            $KS[] = "`{$k}`";
        }
        $_VS = [];
        foreach ($query['multi-data'] as $data) {
            $bks = [];
            foreach ($data as $v) {
                $bks[] = "'{$v}'";
            }
            $_VS[] = '(' . implode(',', $bks) . ')';
        }
        $sql = "INSERT INTO \n"
            . "`{$query['table']}`\n"
            . ' (' . implode(',', $KS) . ') VALUES ' . "\n" . implode(",\n", $_VS);

        echo $sql;
    }

    /**
     * widget 测试视图
     * @throws Exception
     * @throws \ReflectionException
     */
    public function actionWidget()
    {
        $this->render('widget', []);
    }

    /**
     * 地址获取
     * @throws \Exception
     */
    public function actionAddress()
    {
        $code = $this->getApp()->getRequest()->getParam('code');
        $info = \Tools\ChinaArea::getAreaInfo($code);
        var_dump($info);
        $address = \Tools\ChinaArea::getAddress($code);
        var_dump($address);
    }
}