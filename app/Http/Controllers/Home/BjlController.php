<?php

namespace App\Http\Controllers\Home;
use App\Http\Controllers\Controller;
use GatewayClient\Gateway;
use frame\Html;

class BjlController extends Controller
{
	public function __construct()
    {
        parent::_init();
    }

	public function index()
	{
		Html::addCss();
		Html::addJs();

		//获取用户信息
		$memberService = make('App/Services/MemberService');
		$info = $memberService->getInfoCache($this->mem_id);
		$walletService = make('App/Services/WalletService');
		$data = $walletService->getInfo($this->mem_id);
		$data = [
			'nickname' => $info['nickname'] ?? '',
			'avatar' => $info['avatar'] ?? '',
			'balance' => (int) ($data['balance'] ?? 0),
		];

		$this->assign('info', $data);
		$this->assign('_title', '百家乐');
		
		return view();
	}

	public function initGame()
	{
		$client_id = ipost('client_id');
		if (empty($client_id)) {
			$this->error(['message'=>'param error', 'type'=>'init']);
		}
		//绑定客户端
		Gateway::bindUid($client_id, $this->mem_id);
		//加入分组
		Gateway::joinGroup($client_id, 'group_bjl_clint');
		//获取游戏状态
		
	}

	public function wager()
	{
		$amount = (int) ipost('amount');
		$type = (int) ipost('type');
		if (empty($amount) || empty($type)) {
			$this->error('参数不正确');
		}
		$gamblingService = make('App/Services/GamblingService');
		$res = $gamblingService->create($this->mem_id, $gamblingService::constant('TYPE_BJL'), $type, $amount, $error);
		if (!empty($error)) {
			$this->error($error);
		}
		//获取账户
		$walletService = make('App/Services/WalletService');
		$data = $walletService->getInfo($this->mem_id);
		$data = ['balance' => (int) $data['balance']];
		//发送通知
		$sendData = [
			'type' => 'bjl',
			'entity_type' => $type,
			'amount' => $amount,
		];
		Gateway::sendToGroup('group_bjl_clint', json_encode($sendData), [Gateway::getClientIdByUid($this->mem_id)]);
		$this->success('下注成功', $data);
	}

	public function getzoushiList()
	{
		$page = (int) iget('page', 1);
		$size = (int) iget('size', 20);
		$ffcService = make('App/Services/FfcService');
		$list = $ffcService->getList($page, $size);
		if (!empty($list)) {
			foreach ($list as $key => $value) {
				$value['ffc_num1'] = $value['ffc_num1'] == 0 ? 10 : $value['ffc_num1'];
				$value['ffc_num2'] = $value['ffc_num2'] == 0 ? 10 : $value['ffc_num2'];
				$value['ffc_num4'] = $value['ffc_num4'] == 0 ? 10 : $value['ffc_num4'];
				$value['ffc_num5'] = $value['ffc_num5'] == 0 ? 10 : $value['ffc_num5'];

				$list[$key]['he'] = [
					0 => mediaUrl('image/common/p'.$value['ffc_num1'].'_'.rand(1, 4).'.png'),
					1 => mediaUrl('image/common/p'.$value['ffc_num2'].'_'.rand(1, 4).'.png'),
					2 => mediaUrl('image/common/dian'.(($value['ffc_num1']+$value['ffc_num2']) % 10).'.png'),
				];
				$list[$key]['zhuang'] = [
					0 => mediaUrl('image/common/p'.$value['ffc_num4'].'_'.rand(1, 4).'.png'),
					1 => mediaUrl('image/common/p'.$value['ffc_num5'].'_'.rand(1, 4).'.png'),
					2 => mediaUrl('image/common/dian'.(($value['ffc_num4']+$value['ffc_num5']) % 10).'.png'),
				];
			}
		}
		$this->success('获取成功', $list);
	}
}