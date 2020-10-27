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
			$url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$info['appid'].'&redirect_uri='.urlencode(url('login/')).'&response_type=code&scope=snsapi_base&state=bjl#wechat_redirec';
			redirect($url);
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

	protected function getInfoByCode()
	{
	}
}