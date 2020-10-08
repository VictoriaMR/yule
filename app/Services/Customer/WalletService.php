<?php 

namespace App\Services\Customer;
use App\Services\WalletService as BaseService;
use App\Models\Customer\Wallet;
use App\Models\Customer\WalletLog;

class WalletService extends BaseService
{	
	protected static $constantMap = [
        'base' => CustomerWallet::class,
        'log' => CustomerWalletLog::class,
    ];

	public function __construct(Wallet $model, WalletLog $logModel)
    {
        $this->baseModel = $model;
        $this->logModel = $logModel;
    }
}