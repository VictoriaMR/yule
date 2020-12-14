<?php

namespace App\Http\Controllers\Admin;
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
		Html::addCss();
		Html::addJs();

		$param['page'] = iget('page', 1);
		$param['size'] = iget('size', 20);
		$param['mem_id'] = iget('mem_id');
		$param['keyword'] = iget('keyword');
		$param['recommender'] = iget('recommender');
		$mem_id = iget('mem_id');
		$where = [];
		if (!empty($param['keyword'])) {
			$where['mobile,name,nickname'] = ['like', '%'.$param['keyword'].'%'];
		}
		if (!empty($mem_id)) {
			$where['recommender'] = $mem_id;
		} else if (!empty($param['recommender'])) {
			$proxyService = make('App/Services/Proxy/MemberService');
			$list = $proxyService->getList(['mobile,name,nickname' => ['like', '%'.$param['recommender'].'%']]);
			if (!empty($list)) {
				$where['recommender'] = ['in', array_column($list, 'mem_id')];
			} else {
				$where = ['mem_id' => 0];
			}
		}
		$memberService = make('App/Services/MemberService');
		$total = $memberService->getTotal($where);

		$this->assign('title', '客户 ('.$total.')');
		$this->assign('param', $param);
		return view();
	}

	public function getList()
	{
		$page = iget('page', 1);
		$size = iget('size', 20);
		$mem_id = iget('mem_id');
		$recommender = iget('recommender');
		$keyword = iget('keyword');

		$proxyService = make('App/Services/Proxy/MemberService');
		$where = [];
		if (!empty($keyword)) {
			$where['mobile,name,nickname'] = [ 'like', '%'.$keyword.'%'];
		}
		if (!empty($mem_id)) {
			$where['recommender'] = $mem_id;
		} else if (!empty($recommender)) {
			$list = $proxyService->getList(['mobile,name,nickname' => ['like', '%'.$recommender.'%']]);
			if (!empty($list)) {
				$where['recommender'] = ['in', array_column($list, 'mem_id')];
			} else {
				$where = ['mem_id' => 0];
			}
		}
		$memberService = make('App/Services/MemberService');
		$list = $memberService->getList($where, $page, $size, ['mem_id'=>'desc']);
		if (!empty($list)) {
			$memIdArr = array_column($list, 'mem_id');
			$walletService = make('App/Services/WalletService');
			$walletList = $walletService->getList(['mem_id'=>['in', $memIdArr]]);
			$walletList = array_column($walletList, null, 'mem_id');
			foreach ($list as $key => $value) {
				if (!empty($walletList[$value['mem_id']])) {
					$value['subtotal'] = $walletList[$value['mem_id']]['subtotal'];
					$value['balance'] = $walletList[$value['mem_id']]['balance'];
				}
				//推荐人
				$recommenderInfo = $proxyService->getInfoCache($value['recommender']);
				$value['recommender_name'] = $recommenderInfo['name'] ?? '';
				$value['recommender_nickname'] = $recommenderInfo['nickname'] ?? '';
				$value['recommender_avatar'] = $recommenderInfo['avatar'] ?? '';
				$list[$key] = $value;
			}
		}
		$this->success('success', $list ?? []);
	}

	public function info()
	{
		Html::addCss();
		Html::addJs();

		$mem_id = (int) iget('mem_id');
		if (empty($mem_id)) {
			$error = 'ID不能为空';
		}

		$proxyService = make('App/Services/Proxy/MemberService');
		$idArr = $proxyService->getProxyId($this->mem_id);
		if (!is_array($idArr)) {
			$idArr = explode(',', $idArr);
		}
		$idArr[] = $this->mem_id;
		$idArr = array_unique(array_filter($idArr));
		$memberService = make('App/Services/MemberService');
		if (!$memberService->getTotal(['mem_id' => $mem_id, 'recommender'=>['in', $idArr]])) {
			$error = '无权限查看用户';
		}

		if (empty($error)) {
			$info = $memberService->getInfoCache($mem_id);
			//钱包
			$walletService = make('App/Services/WalletService');
			$data = $walletService->getInfo($mem_id);
			if (!empty($data)) {
				$info['subtotal'] = $data['subtotal'];
				$info['balance'] = $data['balance'];	
			}
			//推荐人
			$data = $proxyService->getInfoCache($info['recommender']);
			$info['recommender_name'] = $data['name'] ?? '';
			$info['recommender_nickname'] = $data['nickname'] ?? '';
			$info['recommender_avatar'] = $data['avatar'] ?? '';

			$this->assign('info', $info);
			$this->assign('show', $this->getShowArr());
		}
		$this->assign('mem_id', $mem_id);
		$this->assign('error', $error ?? '');
		$this->assign('title', '客户信息');

		return view();
	}

	protected function getShowArr()
	{
		return [
			'mem_id' => '用户ID',
			'recommender' => '推荐人ID',
			'recommender_name' => '推荐人名称',
			'recommender_nickname' => '推荐人昵称',
			'create_at' => '加入时间',
		];
	}

	public function getBlingList()
	{
		$page = iget('page', 1);
		$size = iget('size', 20);
		$mem_id = (int) iget('mem_id');
		if (empty($mem_id)) {
			$this->error('ID不能为空');
		}
		$proxyService = make('App/Services/Proxy/MemberService');
		$idArr = $proxyService->getProxyId($this->mem_id);
		if (!is_array($idArr)) {
			$idArr = explode(',', $idArr);
		}
		$idArr[] = $this->mem_id;
		$idArr = array_unique(array_filter($idArr));
		$memberService = make('App/Services/MemberService');
		if (!$memberService->getTotal(['mem_id' => $mem_id, 'recommender'=>['in', $idArr]])) {
			$this->error('无权限查看用户');
		}
		$blingService = make('App/Services/GamblingService');
		$list = $blingService->getList(['mem_id'=>$mem_id], $page, $size);
		$this->success('success', $list);
	}

	public function tuiguang()
	{
		Html::addCss();
		$type = iget('type');
		$file = ROOT_PATH.'public/image/tuiguang/'.$this->mem_id.'.png';
		if (!is_file($file) || $type == 'reset') {
			$fileService = make('App/Services/FileService');
			$url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx2e3ee71ac2d9f0b8&redirect_uri='.url('login', ['recommender' => $this->mem_id]).'&response_type=code&scope=snsapi_base&state=bjl#wechat_redirec';
			$fileService->qr_code($url, $file);
		}

		//下载
		if ($type == 'download') {
			\frame\Http::download($file);
		}

		$this->assign('url', mediaUrl('image/tuiguang/'.$this->mem_id.'.png'));
		$this->assign('title', '推广');
		return view();
	}

	public function recharge()
	{
		$mem_id = (int)ipost('mem_id');
		$total = (int)ipost('total');
		if (empty($mem_id)) {
			$this->error('用户ID不正确');
		}
		if (empty($total)) {
			$this->error('充值金额不正确');
		}
		$walletService = make('App/Services/WalletService');
		$result = $walletService->incrementByMemId($mem_id, $total, ['creater'=>$this->mem_id, 'remark' => '充值']);
		if ($result) {
			$this->success('充值成功');
		} else {
			$this->error('充值失败');
		}
	}
}