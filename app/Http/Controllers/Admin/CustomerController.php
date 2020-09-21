<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use frame\Html;

class CustomerController extends Controller
{
	public function __construct()
	{
		parent::_initialize();
	}

	public function index()
	{
		$this->baseService = make('App/Services/Admin/CustomerService');
		$opt = ipost('opt');
		switch ($opt) {
			case 'edit':
				$this->editMember();
				break;
			case 'delete':
				$this->deleteMember();
				break;
		}
		Html::addJs();
		Html::addCss();
		$page = iget('page', 1);
		$size = iget('size', 20);
		$keyword = iget('keyword', '');
		$status = iget('status', '');
		$where = [];
		if (!empty($keyword))
			$where['name, nickname, mobile'] = ['like', '%'.$keyword.'%'];

		$total = $this->baseService->getTotal($where);
		if ($total > 0) {
			$list = $this->baseService->getList($where, $page, $size);
			if (!empty($list)) {
				foreach ($list as $key => $value) {
					if (!empty($value['recommender'])) {
						$value['recommender_info'] = $this->baseService->getInfoCache($value['recommender']);
					}
					$list[$key] = $value;
				}
			}
		}

		$paginator = paginator()->make($size, $total, $page);

		//代理列表
		$customerList = $this->baseService->getList(['status'=>1], 1, 9999);
		//等级列表
		$levelService = make('App/Services/LevelService');
		$levelList = $levelService->getListCache();
		
		$this->assign('list', $list ?? []);
		$this->assign('paginator', $paginator);
		$this->assign('keyword', $keyword);
		$this->assign('status', $status);
		$this->assign('customerList', $customerList);
		$this->assign('levelList', $levelList);

		return view();
	}

	protected function editMember()
	{
		$memId = (int) ipost('mem_id');
		$name = ipost('name', '');
		$nickname = ipost('nickname', '');
		$password = ipost('password');
		$status = (int)ipost('status');
		$remark = ipost('remark', '');
		$mobile = ipost('mobile', '');
		$level = (int)ipost('level');
		$recommender = (int)ipost('recommender');
		$commission = ipost('commission');

		$data = [];
		if (!empty($mobile)) {
			$res = $this->baseService->isExistMobile($mobile, $memId);
			if ($res)
				$this->error('手机号码已存在');
			$data['mobile'] = $mobile;
		}
		if (!empty($name))
			$data['name'] = $name;
		if (!empty($nickname))
			$data['nickname'] = $nickname;
		if (!is_null($status))
			$data['status'] = (int) $status;
		if (!empty($remark)) 
			$data['remark'] = $remark;
		if (!empty($level))
			$data['level'] = $level;
		if (!empty($recommender)) {
			if ($memId == $recommender)
				$this->error('推荐人错误');
			$data['recommender'] = $recommender;
		}
		if (!empty($commission)) {
			if ($commission > 50) {
				$this->error('费率不正确');
			}
			$data['commission'] = number_format($commission, 2);
		}
		if (!empty($password)) {
			$data['password'] = password_hash($this->baseService->getPasswd($password, $this->baseService::PASSWORD_SALT), PASSWORD_DEFAULT);
		}
		//会员与等级
		if (!empty($recommender) && empty($level)) {
			//获取上级信息
			$info = $this->baseService->getInfoCache($recommender);
			$data['level'] = ($info['level'] ?? 0) + 1;
		}
		//等级与费率
		if (empty($commission) && !empty($level)) {
			$levelService = make('App/Services/LevelService');
			$levelList = $levelService->getListCache();
			$levelList = array_column($levelList, 'value', 'lev_id');
			$data['commission'] = $levelList[$level] ?? 0;
		}
		if (!empty($memId)) {
			$res = $this->baseService->updateDataById($memId, $data);
			$this->baseService->deleteCache($memId);
		} else {
			$data['create_at'] = $this->baseService->getTime();
			$data['code'] = $this->baseService->getCode(32);
			$res = $this->baseService->insertGetId($data);
		}

		if ($res) {
			$this->success('配置成功', $res);
		} else {
			$this->error('配置失败', $res);
		}
	}

	protected function deleteMember()
	{
		$memId = (int) ipost('mem_id');
		if (empty($memId))
			return $this->result(10000, false, ['message'=>'缺失参数']);

		$result = $this->baseService->deleteById($memId);
		if ($result) {
			$this->baseService->deleteCache($memId);
			return $this->result(200, $result, ['message' => '删除成功']);
		} else {
			return $this->result(10000, $result, ['message' => '删除失败']);
		}
	}

	public function view()
	{
		$this->baseService = make('App/Services/Admin/CustomerService');
		Html::addCss();
		$id = iget('id');
		$info = $this->baseService->getInfoCache($id);

		//钱包信息
		$walletService = make('App/Services/WalletService');
		$walletInfo = $walletService->getInfo($id);
		
		$recommenderList = $this->baseService->getRecommenderList($id);

		$this->assign('info', $info);
		$this->assign('walletInfo', $walletInfo);
		$this->assign('recommenderList', $recommenderList);

		return view();
	}
}