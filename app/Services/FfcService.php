<?php 

namespace App\Services;
use App\Services\Base as BaseService;
use App\Models\Ffc;
use frame\Http;

class FfcService extends BaseService
{	
    protected $_url = 'http://www.qq-online.com/openline';
    protected $_fields = ['fcc_num1', 'fcc_num2', 'fcc_num3', 'fcc_num4', 'fcc_num5'];

    const FFC_CACHE_KEY = 'FFC_NOW_QISHU_CACHE';

    public function __construct(Ffc $model)
    {
        $this->baseModel = $model;
    }

    public function getOriginFfc()
    {
        $qishu = date('Ymd').(str_pad(date('H')*60 + date('i'), 4, '0', STR_PAD_LEFT));
        $status = true;
        while ($status) {
            $content = Http::get($this->_url);
            if (!empty($content['errno'])) continue;
            $content = json_decode($content, true);
            $content = array_column($content['listData'], 'opennum', 'issue');
            if (empty($content[$qishu])) continue;
            $this->addIfNotExist($qishu, array_combine($this->_fields, explode(',', $content[$qishu])));
            $status = false;
        }
        return true;
    }

    protected function addIfNotExist($qishu, array $data)
    {
        return $this->baseModel->addIfNotExist($qishu, $data);
    }
}
