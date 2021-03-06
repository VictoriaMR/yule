<?php 

namespace App\Services;
use App\Services\Base as BaseService;
use App\Models\Ffc;
use GatewayClient\Gateway;
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
        //状态停止
        redis(2)->set('BJL_STATUS', '0');
        //发送通知
        try {
            Gateway::sendToGroup('group_bjl_clint', json_encode(['type' => 'wait', 'status'=>'0', 'time' => '0']));
        } catch (Exception $e) {
            echo $e->getMessage().PHP_EOL;
        }
        sleep(3);
        Gateway::sendToGroup('group_bjl_clint', json_encode(['type' => 'jiesuan']));
        //当前期数
        $status = true;
        $i = 0;
        while ($status) {
            $content = Http::post($this->_url);
            if (empty($content) || !empty($content['errno'])) {
                if ($i > 20) {
                    $arr = [];
                    $qishu = $this->getNowQishu();
                    $arr['ffc_key'] = $qishu;
                    $arr['status'] = 0;
                    $this->addIfNotExist($qishu, $arr, false);
                    exit();
                }
                $i ++;
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
            $qishu = $this->getNowQishu();
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
            $this->addIfNotExist($qishu, $arr);
            $status = false;
        }
        return true;
    }

    public function getNowQishu()
    {
        return date('Ymd', time()).(str_pad(date('H', time())*60 + date('i', time()), 4, '0', STR_PAD_LEFT));
    }

    public function getNextQishu()
    {
        $time = strtotime(date('Y-m-d H:i', strtotime('+1 Minute')).':00');
        return date('Ymd', $time).(str_pad(date('H', $time)*60 + date('i', $time), 4, '0', STR_PAD_LEFT));
    }

    protected function addIfNotExist($qishu, array $data, $checkout = true)
    {
        $res = $this->baseModel->addIfNotExist($qishu, $data);
        if ($res && $checkout) {
            //计算结果
            $blingService = make('App/Services/GamblingService');
            $list = $blingService->getList(['qishu' => $qishu, 'type' => $blingService::constant('TYPE_BJL'), 'status' => $blingService::constant('STATUS_DEFAULT')]);
            if (!empty($list)) {
                $memIdArr = array_unique(array_column($list, 'mem_id'));
                $walletService = make('App/Services/WalletService');
                foreach ($list as $key => $value) {
                    switch ($value['entity_id']) {
                        case $blingService::constant('ENTITY_TYPE_BJL_ZHUANG'):
                            if (($data['ffc_num1'] + $data['ffc_num2']) % 10 < ($data['ffc_num4'] + $data['ffc_num5']) % 10) {
                                $blingService->updateDataById($value['bl_id'], ['status' => $blingService::constant('STATUS_WIN')]);
                                $walletService->incrementByMemId($value['mem_id'], $value['amount'], ['creater'=>'10000', 'entity_type' => $walletService::constant('ENTITY_TYPE_BLING', 'log'), 'entity_id'=>$value['bl_id'], 'remark' => '百家乐返奖']);
                            } else {
                                $blingService->updateDataById($value['bl_id'], ['status' => $blingService::constant('STATUS_FAIL')]);
                            }
                            break;
                        case $blingService::constant('ENTITY_TYPE_BJL_XIAN'):
                            if (($data['ffc_num1'] + $data['ffc_num2']) % 10 > ($data['ffc_num4'] + $data['ffc_num5']) % 10) {
                                $blingService->updateDataById($value['bl_id'], ['status' => $blingService::constant('STATUS_WIN')]);
                                $walletService->incrementByMemId($value['mem_id'], $value['amount'], ['creater'=>'10000', 'entity_type' => $walletService::constant('ENTITY_TYPE_BLING', 'log'), 'entity_id'=>$value['bl_id'], 'remark' => '百家乐返奖']);
                            } else {
                                $blingService->updateDataById($value['bl_id'], ['status' => $blingService::constant('STATUS_FAIL')]);
                            }
                            break;
                        case $blingService::constant('ENTITY_TYPE_BJL_HE'):
                            if (($data['ffc_num1'] + $data['ffc_num2']) % 10 == ($data['ffc_num4'] + $data['ffc_num5']) % 10) {
                                $blingService->updateDataById($value['bl_id'], ['status' => $blingService::constant('STATUS_WIN')]);
                                $walletService->incrementByMemId($value['mem_id'], $value['amount'], ['creater'=>'10000', 'entity_type' => $walletService::constant('ENTITY_TYPE_BLING', 'log'), 'entity_id'=>$value['bl_id'], 'remark' => '百家乐返奖']);
                            } else {
                                $blingService->updateDataById($value['bl_id'], ['status' => $blingService::constant('STATUS_FAIL')]);
                            }
                            break;
                        case $blingService::constant('ENTITY_TYPE_BJL_ZHUANGDUI'):
                            if ($data['ffc_num4'] == $data['ffc_num5']) {
                                $blingService->updateDataById($value['bl_id'], ['status' => $blingService::constant('STATUS_WIN')]);
                                $walletService->incrementByMemId($value['mem_id'], $value['amount'], ['creater'=>'10000', 'entity_type' => $walletService::constant('ENTITY_TYPE_BLING', 'log'), 'entity_id'=>$value['bl_id'], 'remark' => '百家乐返奖']);
                            } else {
                                $blingService->updateDataById($value['bl_id'], ['status' => $blingService::constant('STATUS_FAIL')]);
                            }
                            break;
                        case $blingService::constant('ENTITY_TYPE_BJL_XIANDUI'):
                            if ($data['ffc_num1'] == $data['ffc_num2']) {
                                $blingService->updateDataById($value['bl_id'], ['status' => $blingService::constant('STATUS_WIN')]);
                                $walletService->incrementByMemId($value['mem_id'], $value['amount'], ['creater'=>'10000', 'entity_type' => $walletService::constant('ENTITY_TYPE_BLING', 'log'), 'entity_id'=>$value['bl_id'], 'remark' => '百家乐返奖']);
                            } else {
                                $blingService->updateDataById($value['bl_id'], ['status' => $blingService::constant('STATUS_FAIL')]);
                            }
                            break;
                    }
                }
                //发送通知
                if (!empty($memIdArr)) {
                    foreach ($memIdArr as $value) {
                        $info = $walletService->getInfo($value);
                        Gateway::sendToUid($value, json_encode(['type'=>'wallet', 'balance' => $info['balance'] ?? '0.00']));
                    }
                }
            }
            redis(2)->set('BJL_STATUS', '1');
            redis(2)->set('BJL_QISHU', $qishu);
            redis(2)->set('BJL_NEXT_QISHU', $this->getNextQishu());
            redis(2)->set('BJL_QISHU_VALUE', $data);
            Gateway::sendToGroup('group_bjl_clint', json_encode($this->getStatus()));
        }
        return $res;
    }

    public function getStatus()
    {
        $data = redis(2)->get('BJL_QISHU_VALUE');
        if (empty($data)) return [];
        return [
            'type' => 'prize',
            'status'=> redis(2)->get('BJL_STATUS'),
            'time' => strtotime(date('Y-m-d H:i', strtotime('+1 Minute')).':00') - time(),
            'qishu' => redis(2)->get('BJL_QISHU'),
            'next_qishu' => $this->getNextQishu(),
            'value' => $data,
            'xian' => [
                0 => mediaUrl('image/common/p'.$data['ffc_num1'].'_'.rand(1, 4).'.png'),
                1 => mediaUrl('image/common/p'.$data['ffc_num2'].'_'.rand(1, 4).'.png'),
                2 => mediaUrl('image/common/dian'.(($data['ffc_num1']+$data['ffc_num2']) % 10).'.png'),
            ],
            'zhuang' => [
                0 => mediaUrl('image/common/p'.$data['ffc_num4'].'_'.rand(1, 4).'.png'),
                1 => mediaUrl('image/common/p'.$data['ffc_num5'].'_'.rand(1, 4).'.png'),
                2 => mediaUrl('image/common/dian'.(($data['ffc_num4']+$data['ffc_num5']) % 10).'.png'),
            ],
            'result' => $this->getResult($data['ffc_num1'], $data['ffc_num2'], $data['ffc_num4'], $data['ffc_num5']),
        ];
    }

    public function getResult($num1, $num2, $num3, $num4)
    {
        $num1 = ($num1 + $num2) % 10;
        $num2 = ($num3 + $num4) % 10;
        if ($num1 < $num2) {
            return 'zhuang';
        } elseif ($num1 > $num2) {
            return 'xian';
        } else {
            return 'he';
        }
    }

    public function getList($where = [], $page = 1, $size = 20)
    {
        return $this->baseModel->getList($where, $page, $size);
    }
}
