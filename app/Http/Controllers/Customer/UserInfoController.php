<?php

namespace App\Http\Controllers\Customer;
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
		Html::addJs(['index']);

		//用户信息
		$memberService = make('App/Services/Admin/MemberService');
		$info = $memberService->getInfoCache(\frame\Session::get('admin_member_id'));
		
		$this->assign('info', $info);

		return view();
	}
}