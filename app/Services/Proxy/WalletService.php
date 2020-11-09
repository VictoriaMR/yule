<?php 

namespace App\Services\Proxy;

use App\Services\WalletService as BaseService;
use App\Models\Proxy\Wallet;
use App\Models\Proxy\WalletLog;

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
}