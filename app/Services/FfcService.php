<?php 

namespace App\Services;
use App\Services\Base as BaseService;
use App\Models\Ffc;
use frame\Http;

class FfcService extends BaseService
{	
    protected $_url = 'http://api.tenenct.com/api/tencent.php?key=test8888&type=xml';
    protected $_fields = ['ffc_num1', 'ffc_num2', 'ffc_num3', 'ffc_num4', 'ffc_num5'];

    const FFC_CACHE_KEY = 'FFC_NOW_QISHU_CACHE';

    public function __construct(Ffc $model)
    {
        $this->baseModel = $model;
    }

    public function getOriginFfc()
    {
        //当前期数
        $status = true;
        $count = 0;
        while ($status) {
            $content = Http::post($this->_url);
            if (!empty($content['errno'])) {
                if ($count > 30) {
                    $arr = [];
                    $qishu = date('Ymd', time()).(str_pad(date('H', time())*60 + date('i', time()), 4, '0', STR_PAD_LEFT));
                    $arr['ffc_key'] = $qishu;
                    $arr['status'] = 0;
                    $this->addIfNotExist($qishu, $arr, false);
                    exit();
                }
                $count ++;
                usleep(300000);
                continue;
            }
            $content = simplexml_load_string($content);
            $time = (string)$content->row['opentime'];
            $time = strtotime($time);
            if (strtotime(date('Y-m-d H:i', time())) != $time) {
                usleep(300000);
                continue;
            }
            $qishu = date('Ymd', $time).(str_pad(date('H', $time)*60 + date('i', $time), 4, '0', STR_PAD_LEFT));
            $number = (int)$content->row['number'];
            $diff = (int)$content->row['difference'];
            if (empty($number) || empty($time)) continue;
            $count = array_sum(str_split($number)); 
            $arr = substr($count, -1);
            $arr .= substr($number, -4);
            $arr = array_combine($this->_fields, str_split($arr));
            $arr['ffc_key'] = $qishu;
            $arr['status'] = 1;
            $arr['number'] = $number;
            $arr['difference'] = $diff;
            $this->addIfNotExist($qishu, $arr);
            $status = false;
        }
        return true;
    }

    protected function addIfNotExist($qishu, array $data, $checkout = true)
    {
        $res = $this->baseModel->addIfNotExist($qishu, $data);
        if ($res && $checkout) {

        }
        return $res;
    }
}
