<?php
//框架文件加载
require_once ROOT_PATH.'frame/helper.php';
require_once ROOT_PATH.'frame/env.php';
require_once ROOT_PATH.'frame/app.php';
require_once ROOT_PATH.'frame/container.php';
if (is_file(ROOT_PATH . 'vendor/autoload.php'))
	require_once ROOT_PATH . 'vendor/autoload.php';
if (is_cli()) {
	App::init();
} else {
	header('Content-Type: text/html;charset=utf-8');
	header('Access-Control-Allow-Origin: *');
	header('Content-Root: ' . env('APP_DOMAIN'));
	header('Connection: close');
	@session_start();
	App::run()->send();
}