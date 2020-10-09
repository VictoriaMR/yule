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
	App::run()->send();
}