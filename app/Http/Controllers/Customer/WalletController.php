<?php

namespace App\Http\Controllers\Customer;
use App\Http\Controllers\Controller;
use frame\Html;

class WalletController extends Controller
{
	public function __construct()
    {
        parent::_init();
    }
    
	public function index()
	{
		Html::addCss(['index']);
		Html::addJs(['index']);
		$this->assign('type', iget('type'));
		return view();
	}

	public function getList()
	{
		$page = iget('page', 1);
		$size = iget('size', 20);
		$type = iget('type');
		$walletService = make('App/Service/Customer/WalletService');
		$where = [
			'mem_id' => $this->mem_id,
		];
		if (!empty($type)) {
			$where['type'] = (int) $type;
		}
		$list = $walletService->getList($where, $page, $size, ['log_id', 'type', 'subtotal', 'remark', 'create_at']);
		$this->success('success', $list);
	}
}