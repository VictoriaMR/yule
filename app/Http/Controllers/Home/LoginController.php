<?php

namespace App\Http\Controllers\Home;
use App\Http\Controllers\Controller;
use frame\Html;

class LoginController extends Controller
{
	public function index()
	{
		// $ffcService = make('App/Services/FfcService');
		// $ffcService->getOriginFfc();
		// die();
		
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