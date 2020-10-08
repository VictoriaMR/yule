<?php

namespace App\Models\Customer;
use App\Models\WalletLog as BaseModel;

class WalletLog extends BaseModel
{
	const TYPE_INCREMENT = 0;
	const TYPE_DECREMENT = 1;

    //表名
    public $table = 'wallet_log';
    //主键
    protected $primaryKey = 'log_id';

    public function getList($where, $page, $size) 
    {
    	return $this->where($where)
    				->page($page, $size)
    				->orderBy('log_id', 'desc')
    				->get();
    }
}