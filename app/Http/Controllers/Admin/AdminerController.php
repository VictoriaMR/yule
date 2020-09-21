<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use frame\Html;

class AdminerController extends Controller
{
	public function __construct()
	{
		parent::_initialize();
	}

	public function index()
	{
		$opt = ipost('opt');
		switch ($opt) {
			case 'edit':
				$this->featureModify();
				break;
			case 'delete':
				$this->featureDelete();
				break;
		}
		Html::addCss();
		Html::addJs();
		$page = iget('page', 1);
		$size = iget('size', 20);
		$keyword = iget('keyword', '');
		$status = iget('status', '');
		$where = [];
		if (!empty($keyword))
			$where['name, nickname, mobile'] = ['like', '%'.$keyword.'%'];

		$memberService = make('App/Services/Admin/MemberService');
		$total = $memberService->getTotal($where);
		if ($total > 0) {
			$list = $memberService->getList($where, $page, $size);
		}

		$paginator = paginator()->make($size, $total, $page);

		$this->assign('list', $list ?? []);
		$this->assign('paginator', $paginator);
		$this->assign('keyword', $keyword);
		$this->assign('status', $status);

		return view();
	}

	protected function featureModify()
	{
		$id = (int) ipost('id');
		$status = ipost('status', null);
		$name = ipost('name', '');
		$nickname = ipost('nickname', '');
		$mobile = ipost('mobile', '');
		$data = [];

		if ($status !== null)
			$data['status'] = (int) $status;
		if (!empty($name))
			$data['name'] = $name;
		if (!empty($nickname))
			$data['nickname'] = $nickname;
		if (!empty($icon))
			$data['mobile'] = $mobile;

		if (empty($data))
			return $this->result(10000, false, ['message'=>'参数不正确']);

		if (!empty($conId)) {
			$result = $this->baseService->updateInfo($conId, $data);
		} else {
			if (!empty($parentId))
				$data['parent_id'] = $parentId;
			$result = $this->baseService->insertGetId($data);
		}

		if ($result) {
			$this->baseService->deleteCache();
			return $this->result(200, $result, ['message' => '保存成功']);
		}
		else
			return $this->result(10000, $result, ['message' => '保存失败']);
	}

	public function edit()
	{
		Html::addCss();
		Html::addJs();
		
		$id = iget('id');
		$memberService = make('App/Services/Admin/MemberService');
		$info = $memberService->getInfoCache($id);
		// print_r($info);
		$this->assign('info', $info);

		return view();
	}
}