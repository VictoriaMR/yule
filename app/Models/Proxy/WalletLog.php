<?php

namespace App\Models\Proxy;
use App\Models\WalletLog as BaseModel;

class WalletLog extends BaseModel
{
	const TYPE_INCREASE = 1;//增加
	const TYPE_DECREASE = 2;//减少
    //表名
    public $table = 'proxy_wallet_log';
}