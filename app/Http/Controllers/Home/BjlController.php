<?php

namespace App\Http\Controllers\Home;
use App\Http\Controllers\Controller;
use GatewayClient\Gateway;
use frame\Html;

class BjlController extends Controller
{
	public function index()
	{
		Html::addCss();
		Html::addJs();

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
		Gateway::bindUid($client_id, \frame\Session::get('home_mem_id'));
		//获取游戏状态
		
	}
}