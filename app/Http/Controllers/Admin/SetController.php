<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use frame\Html;

class SetController extends Controller
{
	public function __construct()
	{
		parent::_initialize();
	}

	public function index()
	{
		$this->baseService = make('App/Services/Admin/ControllerService');
		$opt = ipost('opt');
		switch ($opt) {
			case 'edit':
				$this->featureModify();
				break;
			case 'delete':
				$this->featureDelete();
				break;
			case 'sort':
				$this->updateSort();
				break;
		}

		Html::addCss(['index']);
		Html::addJs(['index']);
		$list = $this->baseService->getList();
		$iconList = [];
		foreach (scandir(ROOT_PATH.'public/image/computer/icon/feature') as $value) {
			if ($value == '.' || $value == '..') continue;
			$temp = explode('.', $value);
			if (substr($temp[0], 0, 1) == '5') {
				$iconList[] = [
					'name' => $temp[0],
					'type' => $temp[1],
					'value' => $value,
					'url' => mediaUrl('image/computer/icon/feature/'.$value),
				];
			}
		}
		$this->assign('iconList', $iconList);
		$this->assign('list', $list);
		return view();
	}

	public function updateSort()
	{
		$sort = ipost('sort');
		if (empty($sort))
			return $this->result(10000, false, ['message'=>'缺失参数']);
		foreach ($sort as $key => $value) {
			$this->baseService->updateDataById($value, ['sort'=>$key]);
		}
		$this->baseService->deleteCache();
		return $this->result(200, true, ['message' => '排序成功']);
	}

	protected function featureDelete()
	{
		$conId = (int) ipost('con_id');
		if (empty($conId))
			return $this->result(10000, false, ['message'=>'缺失参数']);

		//先删除子类 再删除 主类
		if ($this->baseService->isParent($conId))
			$this->baseService->deleteByParentId($conId);

		$result = $this->baseService->deleteById($conId);

		if ($result) {
			$this->baseService->deleteCache();
			return $this->result(200, $result, ['message' => '删除成功']);
		} else {
			return $this->result(10000, $result, ['message' => '删除失败']);
		}
	}

	protected function featureModify()
	{
		$conId = (int) ipost('con_id');
		$parentId = (int) ipost('parent_id', 0);
		$status = ipost('status', null);
		$name = ipost('name', '');
		$nameEn = ipost('name_en', '');
		$icon = ipost('icon', '');
		$sort = ipost('sort');
		$data = [];

		if ($status !== null)
			$data['status'] = (int) $status;
		if (!empty($name))
			$data['name'] = $name;
		if (!empty($nameEn))
			$data['name_en'] = $nameEn;
		if (!empty($icon))
			$data['icon'] = $icon;
		if (!empty($sort))
			$data['sort'] = $sort;

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

	public function level()
	{
		$opt = ipost('opt');
		switch ($opt) {
			case 'edit':
				$this->editLevel();
				break;
		}
		Html::addJs();
		$levelService = make('App/Services/LevelService');
		$list = $levelService->getList();

		$this->assign('list', $list);

		return view();
	}

	protected function editLevel()
	{
		$id = ipost('lev_id');
		$name = ipost('name', '');
		$value = ipost('value', '');

		if (empty($name) || empty($value))
			$this->error('参数不正确');

		$levelService = make('App/Services/LevelService');
		if (!empty($id))
			$res = $levelService->updateDataById($id, ['name'=>$name, 'value'=>$value]);
		else
			$res = $levelService->insertGetId(['name'=>$name, 'value'=>$value]);

		if ($res) {
			$levelService->deleteCache();
			$this->success('设置成功');
		}
		else
			$this->error('设置失败');
	}

	public function secretKey()
	{
		$opt = ipost('opt');
		switch ($opt) {
			case 'edit':
				$this->editSecret();
				break;
			case 'delete':
				$this->deleteSecret();
				break;
			case 'modify':
				$this->modifySecret();
				break;
		}
		Html::addJs();
		$levelService = make('App/Services/SecretService');
		$list = $levelService->getList();

		$this->assign('list', $list);

		return view();
	}

	protected function editSecret()
	{
		$id = ipost('sec_id');
		$appid = ipost('appid', '');
		$secret = ipost('secret', '');
		$status = ipost('status', null);
		$remark = ipost('remark', '');

		$data = [];
		if (!empty($appid))
			$data['appid'] = $appid;
		if (!empty($secret))
			$data['secret'] = $secret;
		if (!empty($remark))
			$data['remark'] = $remark;
		if (!is_null($status))
			$data['status'] = $status;

		if (empty($data))
			$this->error('参数不正确');

		$secretService = make('App/Services/SecretService');
		if (!empty($id)) {
			$res = $secretService->updateDataById($id, $data);
		} else {
			$data['create_at'] = $secretService->getTime();
			$res = $secretService->insertGetId($data);
		}

		if ($res) {
			$secretService->deleteCache();
			$this->success('设置成功');
		}
		else
			$this->error('设置失败');
	}

	protected function deleteSecret()
	{
		$id = (int) ipost('id');
		if (empty($id))
			return $this->result(10000, false, ['message'=>'缺失参数']);

		$secretService = make('App/Services/SecretService');
		$result = $secretService->deleteById($id);
		if ($result) {
			$secretService->deleteCache();
			return $this->result(200, $result, ['message' => '删除成功']);
		} else {
			return $this->result(10000, $result, ['message' => '删除失败']);
		}
	}
}