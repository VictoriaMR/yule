<?php

namespace App\Http\Controllers\Home;
use App\Http\Controllers\Controller;

class WxController extends Controller
{
	public function checkSignature()
	{
		header("Content-Type:text/html; charset=utf-8");
		echo $_GET['echostr'];
		exit();
	}
}