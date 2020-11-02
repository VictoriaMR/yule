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
				$this->assign('url', $url);
			} else {
				$info = $this->getInfoByCode($code);
			}
		}


		return view();
	}

	protected function getInfoByCode($code)
	{
		if (empty($code)) {
			return false;
		}
		if (!empty($this->config)) {
			return false;
		}
		$params = [
			'appid' => $this->config['appid'],
			'secret' => $this->config['secret'],
			'code' => $code,
			'grant_type' => 'authorization_code',
		];
		$token = Http::get('https://api.weixin.qq.com/sns/oauth2/access_token', $params);
		dd($token);
		//获取access_token
		https://api.weixin.qq.com/sns/oauth2/access_token?appid=${appId}&secret=${secret}&code=${code}&grant_type=authorization_code
	}
}