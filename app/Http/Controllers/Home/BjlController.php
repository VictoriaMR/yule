<?php

namespace App\Http\Controllers\Home;
use App\Http\Controllers\Controller;
use frame\Html;

class BjlController extends Controller
{
	public function index()
	{
		Html::addCss();
		Html::addJs();
		
		return view();
	}
}