<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use frame\Html;

class ProxyController extends Controller
{
	public function __construct()
    {
        parent::_init();
    }
    
	public function index()
	{
		Html::addCss();
		Html::addJs();
		$this->assign('title', '代理管理');
		return view();
	}

	public function getList()
	{
		$page = iget('page', 1);
		$size = iget('size', 20);

		$memberService = make('App/Services/Proxy/MemberService');
		$idArr = $memberService->getProxyId($this->mem_id);
		if (!is_array($idArr)) {
			$idArr = explode(',', $idArr);
		}
		$idArr = array_unique(array_filter($idArr));
		if (!empty($idArr)) {
			$list = $memberService->getList(['mem_id' => ['in', $idArr]], $page, $size);
			$memIdArr = array_column($list, 'mem_id');
			$walletService = make('App/Services/Proxy/WalletService');
			$walletList = $walletService->getList(['mem_id'=>['in', $memIdArr]]);
			$walletList = array_column($walletList, null, 'mem_id');
			foreach ($list as $key => $value) {
				if (empty($walletList[$value['mem_id']])) {
					$value['subtotal'] = '--';
					$value['balance'] = '--';
				} else {
					$value['subtotal'] = $walletList[$value['mem_id']]['subtotal'];
					$value['balance'] = $walletList[$value['mem_id']]['balance'];
				}
				$list[$key] = $value;
			}
		}
		$this->success('success', $list ?? []);
	}

	public function addProxy()
	{
		$mobile = ipost('mobile');
		$nickname = ipost('nickname');
		$password = ipost('password');
		if (empty($mobile)) {
			$this->error('账号不能为空');
		}
		if (empty($nickname)) {
			$this->error('名称不能为空');
		}
		if (empty($password)) {
			$this->error('密码不能为空');
		}
		$memberService = make('App/Services/Proxy/MemberService');
		if ($memberService->isExistMobile($mobile)) {
			$this->error('账号已存在');
		}

		$info = $memberService->getInfoCache($this->mem_id);
		$levelService = make('App/Services/LevelService');
		$levelList = $levelService->getListCache();
		$level = $info['level'] + 1;
		$data = [
			'mobile' => $mobile,
			'nickname' => $nickname,
			'password' => password_hash($memberService->getPasswd($password, $memberService::PASSWORD_SALT), PASSWORD_DEFAULT),
			'recommender' => $this->mem_id,
			'commission' => !empty($levelList[$level]) ? $levelList[$level]['value'] ?? 0 : 0,
			'level' => $level,
		];
		$res = $memberService->addMember($data);
		if ($res) {
			$this->success('新增成功', $res);
		} else {
			$this->success('新增失败', $res);
		}
	}
}