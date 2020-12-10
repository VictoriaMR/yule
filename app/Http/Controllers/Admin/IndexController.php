<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use frame\Html;

class IndexController extends Controller
{
	public function index()
	{
		if (!isMobile()) {
			Html::addCss();
			Html::addJs();
		}

		//用户信息
		$memberService = make('App/Services/Admin/MemberService');
		$info = $memberService->getInfoCache(\frame\Session::get('admin_mem_id'));

		$this->assign('info', $info);
		$this->assign('title', '管理后台');

		return view();
	}
}