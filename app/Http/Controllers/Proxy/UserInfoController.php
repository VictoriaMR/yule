<?php

namespace App\Http\Controllers\Proxy;
use App\Http\Controllers\Controller;
use frame\Html;

class UserInfoController extends Controller
{
	public function __construct()
    {
        parent::_init();
    }
    
	public function index()
	{
		Html::addCss(['index']);

		//用户信息
		$memberService = make('App/Services/Proxy/MemberService');
		$info = $memberService->getInfoCache($this->mem_id);
		if (!empty($info)) {
			//钱包信息
			$walletService = make('App/Services/Proxy/WalletService');
			$data = $walletService->getInfo($this->mem_id);
			$info['subtotal'] = $data['subtotal'] ?? '0.00';
			$info['balance'] = $data['balance'] ?? '0.00';	
		}
		
		$this->assign('info', $info);
		$this->assign('title', '个人中心');

		return view();
	}

	public function setting()
	{
		Html::addCss(['index']);
		Html::addJs(['index']);
		
		return view();
	}
}