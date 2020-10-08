<?php

namespace App\Http\Controllers\Customer;
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
		Html::addCss(['index']);
		Html::addJs(['index']);

		$list = $this->getList();

		$this->assign('list', $list);
		return view();
	}

	protected function getList($page = 1, $size = 20)
	{
		$memberService = make('App/Services/Customer/MemberService');
		$idArr = $memberService->getProxyId($this->mem_id);
		if (!is_array($idArr)) {
			$idArr = explode(',', $idArr);
		}
		$idArr = array_unique(array_filter($idArr));
		$list = $memberService->getList(['mem_id' => ['in', $idArr]], $page, $size);
		if (!empty($list)) {
			$memIdArr = array_column($list, 'mem_id');
			$walletService = make('App/Services/Customer/WalletService');
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
		$memberService = make('App/Services/Customer/MemberService');
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