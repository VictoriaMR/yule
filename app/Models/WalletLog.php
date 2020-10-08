<?php

namespace App\Models;
use App\Models\Base as BaseModel;

class WalletLog extends BaseModel
{
	const TYPE_INCREMENT = 0;
	const TYPE_DECREMENT = 1;
	const ENTITY_TYPE_BLING = 1;
    const ENTITY_TYPE_PINGTAI = 2;

    //表名
    public $table = 'wallet_log';
    //主键
    protected $primaryKey = 'log_id';

    public function getList($where, $page, $size, $fields) 
    {
    	return $this->where($where)
    				->page($page, $size)
                    ->select($fields)
    				->orderBy('log_id', 'desc')
    				->get();
    }
}