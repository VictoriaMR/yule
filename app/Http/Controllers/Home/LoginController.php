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
			$url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$info['appid'].'&redirect_uri='.url('login').'&response_type=code&scope=SCOPE&state=STATE#wechat_redirec';
			redirect($url);
			// https://open.weixin.qq.com/connect/oauth2/authorize?appid=APPID&redirect_uri=REDIRECT_URI&response_type=code&scope=SCOPE&state=STATE#wechat_redirec
		}
		dd(iget());
		Html::addCss();
		Html::addJs();

		$i = redis()->get('login_code');
		echo $i.PHP_EOL;
		$i++;
		echo $i.PHP_EOL;
		redis()->set('login_code', $i);
		return view();
	}

	public function checktoken()
	{
	}
}