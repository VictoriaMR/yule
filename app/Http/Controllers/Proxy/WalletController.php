<?php

namespace App\Http\Controllers\Proxy;
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
		Html::addCss();
		Html::addJs();

		$mem_id = iget('mem_id');
		if (!empty($mem_id)) {
			$proxyService = make('App/Services/Proxy/MemberService');
			$idArr = $proxyService->getProxyId($this->mem_id);
			if (!is_array($idArr)) {
				$idArr = explode(',', $idArr);
			}
			$idArr[] = $this->mem_id;
			$idArr = array_unique(array_filter($idArr));
			$memberService = make('App/Services/MemberService');
			if (!$memberService->getTotal(['mem_id' => $mem_id, 'recommender'=>['in', $idArr]])) {
				$error = '无权限查看';
			}
			$this->assign('mem_id', $mem_id);
		} else {
			$mem_id = $this->mem_id;
		}

		if (empty($error)) {
			if (!empty($mem_id)) {
				$walletService = make('App/Services/WalletService');
			} else {
				$walletService = make('App/Services/Proxy/WalletService');
			}
			$info = $walletService->getInfo($mem_id);
		}

		$this->assign('error', $error ?? '');
		$this->assign('info', $info ?? []);
		$this->assign('type', iget('type'));
		$this->assign('title', iget('type') == 2 ? '提现记录' : '钱包记录');
		return view();
	}

	public function getLogList()
	{
		$page = iget('page', 1);
		$size = iget('size', 20);
		$type = iget('type');
		$mem_id = iget('mem_id');
		if (!empty($mem_id)) {
			$proxyService = make('App/Services/Proxy/MemberService');
			$idArr = $proxyService->getProxyId($this->mem_id);
			if (!is_array($idArr)) {
				$idArr = explode(',', $idArr);
			}
			$idArr[] = $this->mem_id;
			$idArr = array_unique(array_filter($idArr));
			$memberService = make('App/Services/MemberService');
			if (!$memberService->getTotal(['mem_id' => $mem_id, 'recommender'=>['in', $idArr]])) {
				$this->error('无权限查看');
			}
			$this->assign('mem_id', $mem_id);
			$walletService = make('App/Services/WalletService');
		} else {
			$walletService = make('App/Services/Proxy/WalletService');
			$mem_id = $this->mem_id;
		}

		$where = [
			'mem_id' => $mem_id,
		];
		if (!empty($type)) {
			$where['type'] = (int) $type;
		}
		$list = $walletService->getLogList($where, $page, $size, ['log_id', 'type', 'subtotal', 'remark', 'create_at']);
		$this->success('success', $list);
	}
}