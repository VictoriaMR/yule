<?php
define('APP_MEMORY_START', memory_get_usage());
define('APP_TIME_START', microtime(true));
@session_start();
ini_set('date.timezone', 'Asia/Shanghai');
define('DS', '/');
define('ROOT_PATH', str_replace('\\', '/', realpath(dirname(__FILE__).'/../').'/'));
define('APP_TEMPLATE_TYPE', true);
require_once ROOT_PATH.'frame/start.php';