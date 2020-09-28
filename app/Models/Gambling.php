<?php

namespace App\Models;
use App\Models\Base as BaseModel;

class Gambling extends BaseModel
{
	const TYPE_BJL = 1;
	const ENTITY_TYPE_BJL_ZHUANG = 1;
	const ENTITY_TYPE_BJL_HE = 2;
	const ENTITY_TYPE_BJL_XIAN = 3;
	const ENTITY_TYPE_BJL_ZHUANGDUI = 4;
	const ENTITY_TYPE_BJL_XIANDUI = 5;

    //表名
    public $table = 'bling_logger';
    //主键
    protected $primaryKey = 'bl_id';

    public function create($memId, $type, $entityId, $amount)
    {
    	if (empty($memId) || empty($type) || empty($entityId) || empty($amount)) {
			return false;
		}
		$data = [
			'mem_id' => $memId,
			'type' => $type,
			'entity_id' => $entityId,
			'amount' => $amount,
			'create_at' => $this->getTime(),
		];
		$wallet = make('App/Models/Wallet');
		$walletLog = make('App/Models/WalletLog');
		$this->begin();
		$id = $this->insertGetId($data);
		$data = ['creater' => $memId];
		$data['entity_type'] = $walletLog::ENTITY_TYPE_BLING;
		$data['entity_id'] = $id;
		$wallet->incrementByMemId($decrementByMemId, $amount, $data);
		$this->commit();
		return true;
    }
}