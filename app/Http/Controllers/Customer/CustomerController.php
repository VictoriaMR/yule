<?php

namespace App\Http\Controllers\Customer;
use App\Http\Controllers\Controller;
use frame\Html;

/**
 * 客户列表
 */
class CustomerController extends Controller
{
	public function __construct()
    {
        parent::_init();
    }
    
	public function index()
	{
		Html::addCss(['index']);
		Html::addJs(['index']);
		
		$list = $this->getList();
		$this->assign('list', $list);
		$this->assign('title', '我的代理');

		return view();
	}

	protected function getList($page = 1, $size = 20)
	{
		$memberService = make('App/Services/Customer/MemberService');
		$idArr = $memberService->getProxyId($this->mem_id);
		if (!is_array($idArr)) {
			$idArr = explode(',', $idArr);
		}
		$idArr[] = $this->mem_id;
		$idArr = array_unique(array_filter($idArr));
		$memberService = make('App/Services/MemberService');
		$list = $memberService->getList(['recommender'=>['in', $idArr]], $page, $size, ['mem_id'=>'desc']);
		if (!empty($list)) {
			$memIdArr = array_column($list, 'mem_id');
			$walletService = make('App/Services/WalletService');
			$walletList = $walletService->getList(['mem_id'=>['in', $memIdArr]]);
			$walletList = array_column($walletList, null, 'mem_id');
			foreach ($list as $key => $value) {
				$value['subtotal'] = $walletList[$value['mem_id']]['subtotal'];
				$value['balance'] = $walletList[$value['mem_id']]['balance'];
				$list[$key] = $value;
			}
		}
		return $list;
	}
}