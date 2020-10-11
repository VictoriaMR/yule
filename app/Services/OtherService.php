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
				sleep(1);
			} else {
				//每轮筹码
				$number = rand(3, 8);
				for ($i=1; $i <= $number; $i++) { 
					$sendData = [
						'type' => 'bjl',
						'entity_type' => $typeArr[array_rand($typeArr)],
						'amount' => $amountArr[array_rand($amountArr)],
					];
					Gateway::sendToGroup('group_bjl_clint', json_encode($sendData));
					usleep(100000);
				}
			}
		}
		return true;
	}

	protected function checkStatus()
	{
		return redis(2)->get('BJL_STATUS');
	}
}
