<?php

namespace App\Http\Controllers\Proxy;
use App\Http\Controllers\Controller;
use frame\Html;

class IndexController extends Controller
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
		//客户统计
		$customerService = make('App/Services/Proxy/MemberService');
		$idArr = $customerService->getProxyId($this->mem_id);
		if (!is_array($idArr)) {
			$idArr = explode(',', $idArr);
		}
		$idArr[] = $this->mem_id;
		$idArr = array_unique(array_filter($idArr));
		$memberService = make('App/Services/MemberService');
		$total = $memberService->getTotal(['recommender'=>['in', $idArr]]);
		$this->assign('customer_total', $total);
		//代理统计
		$total = $customerService->getTotal(['recommender' => $this->mem_id]);
		$this->assign('proxy_total', $total);

		$this->assign('info', $info);

		return view();
	}
}