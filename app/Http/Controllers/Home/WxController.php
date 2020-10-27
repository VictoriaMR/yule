<?php

namespace App\Http\Controllers\Home;
use App\Http\Controllers\Controller;

class WxController extends Controller
{
	public function checkSignature()
	{
		echo $_GET['echostr'];
		exit();
	}
}