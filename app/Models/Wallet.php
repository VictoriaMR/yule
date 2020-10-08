<?php

namespace App\Models;
use App\Models\Base as BaseModel;

class Wallet extends BaseModel
{
    //表名
    public $table = 'wallet';
    //主键
    protected $primaryKey = 'wallet_id';

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