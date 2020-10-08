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

	public function __construct(Wallet $model, WalletLog $logModel)
    {
        $this->baseModel = $model;
        $this->logModel = $logModel;
    }

    public function incrementByMemId($memId, $money, $data=[], $isBalance=true)
    {
    	$memId = (int) $memId;
        $money = (int) $money;
        if (empty($memId) || empty($money)) return false;
        $this->baseModel->begin();
        $this->baseModel->where('mem_id', $memId)->increment($isBalance ? 'balance' : 'subtotal,balance', $money);
        $data['mem_id'] = $memId;
        $data['subtotal'] = $money;
        $data['type'] = $this->logModel::TYPE_INCREMENT;
        $data['create_at'] = $this->getTime();
        $id = $this->logModel->insertGetId($data);
        $this->baseModel->commit();
        return $id;
    }

    public function decrementByMemId($memId, $money, $data=[])
    {
    	$memId = (int) $memId;
        $money = (int) $money;
        if (empty($memId) || empty($money)) return false;
        $this->baseModel->begin();
        $this->baseModel->where('mem_id', $memId)->decrement('balance', $money);
        $data['mem_id'] = $memId;
        $data['subtotal'] = $money;
        $data['type'] = $this->logModel::TYPE_DECREMENT;
        $data['create_at'] = $this->getTime();
        $id = $this->logModel->insertGetId($data);
        $this->baseModel->commit();
        return $id;
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

    public function getLogList($where = [], $page = 0, $size = 20, $fields = ['*'])
    {
        return $this->logModel->getList($where, $page, $page, $fields);
    }
}