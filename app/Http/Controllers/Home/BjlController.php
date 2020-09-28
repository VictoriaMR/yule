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
			'balance' => $data['balance'] ?? 0.00,
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
		//绑定客户端地址
		Gateway::bindUid($client_id, $this->mem_id);
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
		$data = ['balance' = $data['balance']];
		$this->success('下注成功', $data);
	}
}