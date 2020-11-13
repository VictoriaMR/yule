<?php

namespace App\Http\Controllers\Home;
use App\Http\Controllers\Controller;
use App\Services\SecretService;
use frame\Http;
use frame\Html;

class LoginController extends Controller
{
	private $config = [];

	public function __construct(SecretService $service)
	{
		$this->config = $service->getOne();
	}

	public function index()
	{
		if (!empty($this->config)) {
			$code = iget('code');
			if (empty($code)) {
				$url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$this->config['appid'].'&redirect_uri='.urlencode(url('login]')).'&response_type=code&scope=snsapi_base&state=bjl#wechat_redirec';
				redirect($url);
			} else {
				$info = $this->getInfoByCode($code);
				if ($info === false) {
					$status = false;
				} else {
					$bindService = make('App/Services/BindRelationService');
					$memId = $bindService->getIdByOpenid($info['openid'], 1);
					$memberService = make('App/Services/MemberService');
					if (empty($memId)) {
						$data = [
							'name' => $info['nickname'],
							'nickname' => $info['nickname'],
							'sex' => $info['sex'],
							'language' => $info['language'],
							'city' => $info['city'],
							'province' => $info['province'],
							'country' => $info['country'],
							'openid' => $info['openid'],
							'recommender' => (int) iget('recommender', 0),
						];
						$memId = $memberService->addMember($data);
						$memberService->updateUserAvatar($memId, $info['headimgurl']);
					}
					if (!empty($memId)) {
						$memberService->login($memId, 1);
						redirect(url(iget('state')));
					}
				}
			}
		}
		$this->assign('status', $status ?? false);
		return view();
	}

	protected function getInfoByCode($code)
	{
		if (empty($code)) {
			return false;
		}
		if (empty($this->config)) {
			return false;
		}
		$params = [
			'appid' => $this->config['appid'],
			'secret' => $this->config['secret'],
			'code' => $code,
			'grant_type' => 'authorization_code',
		];
		$data = Http::get('https://api.weixin.qq.com/sns/oauth2/access_token', $params);
		$data = isJson($data);
		if (empty($data['access_token']) || empty($data['openid'])) {
			return false;
		}
		//获取信息
		$data = $this->getInfoByToken($data['access_token'], $data['openid']);
		return $data;
	}

	protected function getToken()
	{

	}

	protected function getInfoByToken($access_token, $openid)
	{
		$params = [
			'access_token' => $access_token,
			'openid' => $openid,
		];
		$data = Http::get('https://api.weixin.qq.com/sns/userinfo', $params);
		$data = isJson($data);
		if (empty($data['errmsg'])) {
			return $data;
		} else {
			return [];
		}
	}
}