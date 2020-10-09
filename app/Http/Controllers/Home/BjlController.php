<?php

namespace App\Http\Controllers\Home;
use App\Http\Controllers\Controller;
use GatewayClient\Gateway;
use frame\Html;

class BjlController extends Controller
{
	public function __construct()
    {
        parent::_init();
    }

	public function index()
	{
		Html::addCss();
		Html::addJs();

		//获取用户信息
		$memberService = make('App/Services/MemberService');
		$info = $memberService->getInfoCache($this->mem_id);
		$walletService = make('App/Services/WalletService');
		$data = $walletService->getInfo($this->mem_id);
		$data = [
			'nickname' => $info['nickname'] ?? '',
			'avatar' => $info['avatar'] ?? '',
			'balance' => (int) ($data['balance'] ?? 0),
		];

		$this->assign('info', $data);
		$this->assign('_title', '百家乐');
		
		return view();
	}

	public function initGame()
	{
		$client_id = ipost('client_id');
		if (empty($client_id)) {
			$this->error(['message'=>'param error', 'type'=>'init']);
		}
		//绑定客户端
		Gateway::bindUid($client_id, $this->mem_id);
		//加入分组
		Gateway::joinGroup($client_id, 'group_bjl_clint');
		//获取游戏状态
		$ffcService = make('App/Services/FfcService');
		$data = $ffcService->getStatus();
		$this->success('success', $data);
	}

	protected function checkStatus()
	{
		return redis(2)->get('BJL_STATUS');
	}

	public function wager()
	{
		$amount = (int) ipost('amount');
		$type = (int) ipost('type');
		if (empty($amount) || empty($type)) {
			$this->error('参数不正确');
		}
		if (!empty($this->checkStatus())) {
			$this->error('等待下期开始');
		}
		$ffcService = make('App/Services/FfcService');
		if ($ffcService->getNextQishu() != redis(2)->get('BJL_NEXT_QISHU')) {
			$this->error('系统异常, 请联系客服');
		}
		$blingService = make('App/Services/GamblingService');
		$res = $blingService->create($this->mem_id, $blingService::constant('TYPE_BJL'), $amount, ['entity_id' => $type, 'qishu' => date('Ymd', time()).(str_pad(date('H', time())*60 + date('i', time()), 4, '0', STR_PAD_LEFT))], $error);
		if (!empty($error)) {
			$this->error($error);
		}
		//获取账户
		$walletService = make('App/Services/WalletService');
		$data = $walletService->getInfo($this->mem_id);
		$data = ['balance' => (int) $data['balance']];
		//发送通知
		$sendData = [
			'type' => 'bjl',
			'entity_type' => $type,
			'amount' => $amount,
		];
		Gateway::sendToGroup('group_bjl_clint', json_encode($sendData));
		$this->success('下注成功', $data);
	}

	public function getzoushiList()
	{
		$page = (int) iget('page', 1);
		$size = (int) iget('size', 20);
		$ffcService = make('App/Services/FfcService');
		$list = $ffcService->getList([], $page, $size);
		if (!empty($list)) {
			foreach ($list as $key => $value) {
				$value['ffc_num1'] = $value['ffc_num1'] == 0 ? 10 : $value['ffc_num1'];
				$value['ffc_num2'] = $value['ffc_num2'] == 0 ? 10 : $value['ffc_num2'];
				$value['ffc_num4'] = $value['ffc_num4'] == 0 ? 10 : $value['ffc_num4'];
				$value['ffc_num5'] = $value['ffc_num5'] == 0 ? 10 : $value['ffc_num5'];

				$list[$key]['xian'] = [
					0 => mediaUrl('image/common/p'.$value['ffc_num1'].'_'.rand(1, 4).'.png'),
					1 => mediaUrl('image/common/p'.$value['ffc_num2'].'_'.rand(1, 4).'.png'),
					2 => mediaUrl('image/common/dian'.(($value['ffc_num1']+$value['ffc_num2']) % 10).'.png'),
				];
				$list[$key]['zhuang'] = [
					0 => mediaUrl('image/common/p'.$value['ffc_num4'].'_'.rand(1, 4).'.png'),
					1 => mediaUrl('image/common/p'.$value['ffc_num5'].'_'.rand(1, 4).'.png'),
					2 => mediaUrl('image/common/dian'.(($value['ffc_num4']+$value['ffc_num5']) % 10).'.png'),
				];
			}
		}
		$this->success('success', $list);
	}

	public function getxiazhuList()
	{
		$page = (int) iget('page', 1);
		$size = (int) iget('size', 20);
		$blingService = make('App/Services/GamblingService');
		$list = $blingService->getList(['mem_id'=>$this->mem_id, 'type'=>$blingService::constant('TYPE_BJL')], $page, $size);
		if (!empty($list)) {
			foreach ($list as $key => $value) {
				$value['entity_text'] = $blingService->getTypeText($value['type'], $value['entity_id']);
				$value['status_text'] = $blingService->getStatusText($value['status']);
				$value['create_at'] = date('Y-m-d H:i', strtotime($value['create_at']));
				unset($value['mem_id']);
				$list[$key] = $value;
			}
		}
		$this->success('success', $list);
	}

	public function getjiaoyiList()
	{
		$page = (int) iget('page', 1);
		$size = (int) iget('size', 20);
		$walletService = make('App/Services/WalletService');
		$list = $walletService->getLogList(['mem_id' => $this->mem_id], $page, $size, ['log_id', 'type', 'subtotal', 'remark', 'create_at']);
		if (!empty($list)) {
			foreach ($list as $key => $value) {
				unset($value['mem_id']);
				$value['create_at'] = date('Y-m-d H:i', strtotime($value['create_at']));
				$arr = explode('-', $value['remark']);
				$value['type_text'] = $arr[0] ?? '';
				$value['entity_text'] = $arr[1] ?? '';
				$list[$key] = $value;
			}
		}
		$this->success('success', $list);
	}

	public function cancelWager()
	{
		if (empty($this->getStatus())) {
			$this->error('今期已经截止');
		}
		$ffcService = make('App/Services/FfcService');
		if ($ffcService->getNextQishu() != redis(2)->get('BJL_NEXT_QISHU')) {
			$this->error('系统异常, 请联系客服');
		}
		$blingService = make('App/Services/GamblingService');
		$where = [
			'qishu' => $ffcService->getNextQishu(),
			'type' => $blingService::constant('TYPE_BJL'),
			'mem_id' => $this->mem_id,
			'status' => $blingService::constant('STATUS_REBACK'),
		];
		//允许撤回一次
		if ($blingService->count($where)) {
			$this->error('每期只能撤回一次');
		}
		$where['status'] = $blingService::constant('STATUS_DEFAULT');
		if (!$blingService->count($where)) {
			$this->error('无可撤回数据');
		}
		$list = $blingService->getList($where);
		if (!empty($list)) {
			$walletService = make('App/Services/WalletService');
			foreach ($list as $key => $value) {
				$blingService->updateDataById($value['bl_id'], ['status' => $blingService::constant('STATUS_REBACK')]);
                $walletService->incrementByMemId($value['mem_id'], $value['amount'], ['creater'=>$value['mem_id'], 'entity_type' => $walletService::constant('ENTITY_TYPE_BLING', 'log'), 'entity_id'=>$value['bl_id'], 'remark' => '百家乐撤回下注']);
			}
		}
		$data = $walletService->getInfo($this->mem_id);
		$data = ['balance' => (int) $data['balance']];
		$this->success('撤回成功', $data);
	}
}