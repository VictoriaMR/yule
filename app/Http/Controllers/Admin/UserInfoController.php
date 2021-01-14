<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use frame\Html;
use frame\Session;

class UserInfoController extends Controller
{
	public function index()
	{
		Html::addCss(['index']);

		//用户信息
		$memberService = make('App/Services/Admin/MemberService');
		$info = $memberService->getInfoCache($this->mem_id);
		
		$this->assign('info', $info);
		$this->assign('title', '个人中心');

		return view();
	}
}