<?php 

namespace App\Services\Admin;
use App\Services\MemberService as BaseService;

class CustomerService extends BaseService
{	
	public function recharge($memId, $account, $creater = 0, &$error)
	{
		if (empty($memId) || empty($account)) {
			$error = '参数不正确';
			return false;
		}
		$info = $this->getInfoCache($memId);
		if (empty($info)) {
			$error = '找不到用户';
			return false;
		}
		$walletService = make('App/Services/WalletService');
		$data = [
			'entity_type' => $walletService::constant('ENTITY_TYPE_PINGTAI', 'log'),
			'creater' => $creater,
			'remark' => '平台充值',
		];
		$res = $walletService->incrementByMemId($memId, $money, $data);
		if ($res) {
			//计算佣金
			if (!empty($info['recommender'])) {
				$recommender = $info['recommender'];
				$proxyService = make('App/Services/Proxy/MemberService');
				$info = $proxyService->getInfoCache($recommender);
				//第一层佣金
				if (empty($info)) {
					$proxyWalletService = make('App/Services/Proxy/WalletService');
					$recommender = $info['recommender'];
					$commission = $info['commission'];
					$data = [
						'entity_type' => $proxyWalletService::constant('ENTITY_TYPE_PINGTAI', 'log'),
						'entity_id' => $res,
						'creater' => $creater,
						'remark' => '充值佣金',
					];
					$proxyWalletService->incrementByMemId($recommender, $account*$commission/100, $data);
					$start = true;
					while ($start) {
						if (empty($recommender)) {
							$start = false;
							break;
						}
						$info = $proxyService->getInfoCache($recommender);
						if (empty($info)) {
							$start = false;
							break;
						}
						$diff = $info['commission'] - $commission;
						if ($diff <= 0) {
							$start = false;
							break;
						}
						$recommender = $info['recommender'];
						$commission = $info['commission'];
						$proxyWalletService->incrementByMemId($recommender, $account*$diff/100, $data);
					}
				}
			}
		}
		return $res;
	}

	public function cashOut($memId, $account, $creater = 0, &$error)
	{
		if (empty($memId) || empty($account)) {
			$error = '参数不正确';
			return false;
		}
		if (!$this->checkMoney($memId, $account)) {
			$error = '余额不足';
			return false;
		}
		$data = [
			'entity_type' => $walletService::constant('ENTITY_TYPE_PINGTAI', 'log'),
			'creater' => $creater,
			'remark' => '平台提现',
		];
		$walletService = make('App/Services/WalletService');
		$res = $walletService->decrementByMemId($memId, $money, $data);
		return $res;
	}
}