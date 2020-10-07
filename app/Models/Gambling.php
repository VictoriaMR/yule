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
	const STATUS_DEFAULT = 0;
	const STATUS_WIN = 1;
	const STATUS_FAIL = 2;
	const STATUS_REBACK = 8;

    //表名
    public $table = 'bling_logger';
    //主键
    protected $primaryKey = 'bl_id';

    public function create($memId, $type, $amount, $data = [])
    {
    	if (empty($memId) || empty($type) || empty($amount)) {
			return false;
		}
		$temp = [
			'mem_id' => $memId,
			'type' => $type,
			'amount' => $amount,
			'create_at' => $this->getTime(),
		];
		$data = array_merge($data, $temp);
		$wallet = make('App/Models/Wallet');
		$walletLog = make('App/Models/WalletLog');
		$this->begin();
		$id = $this->insertGetId($data);
		$data = ['creater' => $memId];
		$data['entity_type'] = $walletLog::ENTITY_TYPE_BLING;
		$data['entity_id'] = $id;
		$data['remark'] = '百家乐下注';
		$wallet->decrementByMemId($memId, $amount, $data);
		$this->commit();
		return true;
    }

    public function count($where)
    {
    	return $this->where($where)->count() > 0;
    }

    public function getList($where, $page, $size)
    {
    	return $this->where($where)
    				->page($page, $size)
    				->orderBy('bl_id', 'desc')
    				->get();
    }
}