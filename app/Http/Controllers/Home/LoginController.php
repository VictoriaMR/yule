<?php

namespace App\Http\Controllers\Home;
use App\Http\Controllers\Controller;
use frame\Html;

class LoginController extends Controller
{
	public function index()
	{
		$code = iget('code');
		if (empty($code)) {
			$secretService = make('App/Services/SecretService');
			$info = $secretService->getOne();
			$url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$info['appid'].'&redirect_uri='.urlencode(url('login]')).'&response_type=code&scope=snsapi_base&state=bjl#wechat_redirec';
			$this->assign('url', $url);
		} else {
			$info = $this->getInfoByCode($code);
		}


		return view();
	}

	protected function getInfoByCode($code)
	{
		dd($code);
	}
}