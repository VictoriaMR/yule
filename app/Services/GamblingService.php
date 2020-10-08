<?php 

namespace App\Services;
use App\Services\Base as BaseService;
use App\Models\Gambling;

class GamblingService extends BaseService
{	
	protected static $constantMap = [
        'base' => Gambling::class,
    ];

	public function __construct(Gambling $model)
    {
        $this->baseModel = $model;
    }

	public function create($memId, $type, $amount, $data = [], &$error)
	{
		$memId = (int) $memId;
		$amount = (int) $amount;
		if (empty($memId) || empty($type) || empty($amount)) {
			$error = '参数不正确';
			return false;
		}
		//类型
		switch ($type) {
			case self::constant('TYPE_BJL'):
				if (!in_array($data['entity_id'], [
					self::constant('ENTITY_TYPE_BJL_ZHUANG'), 
					self::constant('ENTITY_TYPE_BJL_HE'), 
					self::constant('ENTITY_TYPE_BJL_XIAN'), 
					self::constant('ENTITY_TYPE_BJL_ZHUANGDUI'), 
					self::constant('ENTITY_TYPE_BJL_XIANDUI')])) 
				{
					$error = '百家乐类型不正确';
					return false;
				}
				break;
			default:
				$error = '类型不正确';
				return false;
				break;
		}
		//余额
		$walletService = make('App/Services/WalletService');
		$res = $walletService->checkMoney($memId, $amount);
		if (!$res) {
			$error = '余额不足';
			return false;
		}
		//下单
		$res = $this->baseModel->create($memId, $type, $amount, $data);
		return $res;
	}

	public function count($where = [])
	{
		return $this->baseModel->count();
	}

	public function getList($where = [], $page = 0, $size = 20)
	{
		return $this->baseModel->getList($where, $page, $size);
	}

	public function getTypeText($type, $entityId)
	{
		return $this->baseModel->getTypeText($type, $entityId);
	}

	public function getStatusText($status)
	{
		return $this->baseModel->getStatusText($status);
	}
}
