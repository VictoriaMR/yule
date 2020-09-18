<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use frame\Html;
use frame\Session;
use frame\Str;

class LoginController extends Controller
{
	public function index()
	{
		Html::addCss(['login']);
		Html::addJs(['login']);
		$loginCode = Str::random(6);
		Session::set('admin_login_code', $loginCode);
		$this->assign('login_code', $loginCode);
		return view();
	}

	public function login()
	{
		$phone = ipost('phone');
		$password = ipost('password');
		$loginCode = ipost('login_code');
		if (empty($phone))
			$this->result(10000, false, '账户不能为空');
		if (empty($password))
			$this->result(10000, false, '密码不能为空');

		if ($loginCode != Session::set('admin_login_code'))
			$this->result(10000, false, '验证不通过');

		$memberService = make('App/Services/Admin/MemberService');
		$result = $memberService->loginByPassword($phone, $password);
		if ($result)
			$this->result(200, $result, '登录成功');
		else
			$this->result(10000, $result, '账户或者密码不匹配');
	}

	public function logout()
	{
		Session::set('admin');
		redirect('/');
	}
}