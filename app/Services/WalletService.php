<?php 

namespace App\Services;
use App\Services\Base as BaseService;
use App\Models\Wallet;
use App\Models\WalletLog;

class WalletService extends BaseService
{	
	protected static $constantMap = [
        'base' => Wallet::class,
        'log' => WalletLog::class,
    ];

	public function __construct(Wallet $model)
    {
        $this->baseModel = $model;
    }

    public function incrementByMemId($memId, $money, $data=[])
    {
    	return $this->baseModel->incrementByMemId($memId, $money, $data);
    }

    public function decrementByMemId($memId, $money, $data=[])
    {
    	return $this->baseModel->decrementByMemId($memId, $money, $data);
    }

    public function checkMoney($memId, $money)
    {
    	return $this->baseModel->checkMoney($memId, $money);
    }

    public function exist($memId)
    {
    	return $this->baseModel->exist($memId);
    }

    public function getInfo($memId)
    {
        return $this->baseModel->getInfo($memId);
    }

    public function getList($where = [], $page = 0, $size = 20)
    {
        return $this->baseModel->getList($where, $page, $page);
    }

    public function getLogList($where = [], $page = 0, $size = 20)
    {
        $logModel = make('App/Models/WalletLog');
        return $logModel->getList($where, $page, $page);
    }
}