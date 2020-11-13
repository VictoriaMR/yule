<?php

namespace App\Models\Proxy;
use App\Models\WalletLog as BaseModel;

class WalletLog extends BaseModel
{
	const TYPE_INCREASE = 1;//增加
	const TYPE_DECREASE = 2;//减少
	const ENTITY_TYPE_ALIPAY = 1; //支付宝
	const ENTITY_TYPE_WEIXIN = 2; //微信
	
    //表名
    public $table = 'proxy_wallet_log';
}