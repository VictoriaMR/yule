<?php

namespace App\Models;
use App\Models\Base as BaseModel;

class Wallet extends BaseModel
{
    //表名
    public $table = 'wallet';
    //主键
    protected $primaryKey = 'wallet_id';

    public function incrementByMemId($memId, $money, $data=[], $isBalance = true)
    {
        $memId = (int) $memId;
        $money = (int) $money;
    	if (empty($memId) || empty($money)) return false;
    	$logModel = make('App/Models/WalletLog');
    	$this->begin();
    	$this->where('mem_id', $memId)->increment($isBalance ? 'balance' : 'subtotal,balance', $money);
        $data['mem_id'] = $memId;
    	$data['subtotal'] = $money;
    	$data['type'] = $logModel::TYPE_INCREMENT;
    	$data['create_at'] = $this->getTime();
    	$logModel->insert($data);
    	$this->commit();
    	return true;
    }

    public function decrementByMemId($memId, $money, $data=[])
    {
        $memId = (int) $memId;
        $money = (int) $money;
    	if (empty($memId) || empty($money)) return false;
    	$logModel = make('App/Models/WalletLog');
    	$this->begin();
    	$this->where('mem_id', $memId)->decrement('balance', $money);
        $data['mem_id'] = $memId;
    	$data['subtotal'] = $money;
    	$data['type'] = $logModel::TYPE_DECREMENT;
    	$data['create_at'] = $this->getTime();
    	$logModel->insert($data);
    	$this->commit();
    	return true;
    }

    public function checkMoney($memId, $money)
    {
        $memId = (int) $memId;
        $money = (int) $money;
    	if (empty($memId) || empty($money)) return false;
    	return $this->where('mem_id', $memId)->where('balance', '>=', (int) $money)->count() > 0;
    }

    public function exist($memId)
    {
        $memId = (int) $memId;
    	if (empty($memId)) return false;
    	return $this->where('mem_id', $memId)->count() > 0;
    }

    public function getInfo($memId)
    {
        $memId = (int) $memId;
        if (empty($memId)) return false;
        return $this->where('mem_id', (int) $memId)->find();
    }

    public function getList($where, $page, $size)
    {
        return $this->where($where)
                    ->page($page, $size)
                    ->get();
    }
}