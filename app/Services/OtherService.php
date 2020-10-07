<?php 

namespace App\Services;
use App\Services\Base as BaseService;
use GatewayClient\Gateway;

class OtherService extends BaseService
{
	public function randBlingBjl()
	{
		sleep(10);
		$count = 0;
		$amountArr = [10, 50, 100, 1000, 5000];
		$typeArr = [1, 2, 3, 4, 5];
		while ($count <= 23) {
			$count++;
			if (empty($this->checkStatus())) {
				sleep(rand(1, 3));
			} else {
				//每轮筹码
				$number = rand(10, 30);
				for ($i=1; $i <= $number; $i++) { 
					$sendData = [
						'type' => 'bjl',
						'entity_type' => $typeArr[array_rand($typeArr)],
						'amount' => $amountArr[array_rand($amountArr)],
					];
					Gateway::sendToGroup('group_bjl_clint', json_encode($sendData));
				}
				sleep(rand(1, 3));
			}
		}
		return true;
	}

	protected function checkStatus()
	{
		return redis()->get('BJL_STATUS');
	}
}
