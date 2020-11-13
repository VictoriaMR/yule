<?php

namespace frame;

class Error
{
	private static $_error = [];
	private static $_instance = null;

	public static function instance()
	{
		if (is_null(self::$_instance)) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
    
    public function register()
    {
    	error_reporting(E_ALL|E_STRICT);
        set_exception_handler([self::$_instance, 'exceptionHandler']);
        set_error_handler([self::$_instance, 'errorHandler']);
        register_shutdown_function([$this, 'fatalErrorHandler']);
    }

    public function errorHandler($errno, $errstr, $errfile, $errline)
    {
    	self::$_error[] = [
    		'errno' => $errno,
    		'errfile' => $errfile,
    		'errstr' => $errstr,
    		'errline' => $errline,
    	];
    	$this->error_echo();
    }

    public function exceptionHandler($exception)
    {
    	$this->unregister();
    	$code = $exception->getCode();
    	self::$_error[] = [
    		'errno' => $code,
    		'errfile' => $exception->getFile(),
    		'errstr' => $exception->getMessage(),
    		'errline' => $exception->getLine(),
    	];
    	foreach ($exception->getTrace() as $key => $value) {
    		self::$_error[] = [
	    		'errno' => $code,
	    		'errfile' => $value['file'] ?? '',
	    		'errline' => $value['line'] ?? '',
	    	];
    	}
    	$this->error_echo();
    }

    public function fatalErrorHandler()
    {
    	$error = error_get_last();
    	if (empty($error)) return false;
    	self::$_error[] = [
    		'errno' => $error['type'],
    		'errfile' => $error['file'],
    		'errstr' => $error['message'],
    		'errline' => $error['line'],
    	];
    	$this->error_echo();
    }

    private function unregister()
    {
        restore_error_handler();
        restore_exception_handler();
    }

    private function error_echo()
	{
		if (isCli()) {
			$msg = '';
			foreach (self::$_error as $value) {
				$str = sprintf('[ %s ] 文件: %s, 行: %s  %s', $this->getErrorText($value['errno'] ?? 0), $value['errfile'] ?? '', $value['errline'] ?? '', !empty($value['errstr']) ? '错误: '.$value['errstr'] : '').PHP_EOL;
				$msg .= $str;
				echo $str;
			}
			\App::Log($msg);
		} else {
			$msg = '';
			if (env('APP_DEBUG')) {
				$route = Router::$_route;
				echo '<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=0.5, maximum-scale=2.0, user-scalable=yes" /><style>*{padding:0;margin:0;}img{max-width:100%;max-height:100%;}</style><div style="z-index: 9999;"><div style="clear:both;word-wrap: break-word;font-family: Arial;font-size: 18px;border: 2px solid #c00;border-radius: 20px;margin:0 30px;padding: 20px;background-color:#FFFFE1;font-weight:600;">';
				echo '<div>网址: '.$_SERVER['HTTP_HOST'].$_SERVER['QUERY_STRING'].'</div>';
				echo '<div>入口: <strong>'.implode('/', $route).'</strong></div>';
				echo '<div>子目录: <strong>'.$route['path'].'</strong></div>';
				echo '<div>类: <strong>'.$route['class'].'</strong></div>';
				echo '<div>方法: <strong>'.$route['func'].'</strong></div>';
				$params = array_merge($_GET, $_POST);
				if (!empty($params)) {
					echo '<div>参数: <strong>'. json_encode($params) .'</strong></div>';
				}
				foreach (self::$_error as $value) {
					if (!empty($value['errstr'])) {
						echo '<div>错误: <strong style="color:#c00">'.$value['errstr'].'</strong></div>';
					}
					echo '文件: <strong>'.$value['errfile'].'</strong></br>';
					echo '在第: <strong>'.$value['errline'].'</strong> 行</br>';
					$msg .= sprintf('[ %s ] 文件: %s, 行: %s %s', $this->getErrorText($value['errno'] ?? 0), $value['errfile'] ?? '', $value['errline'] ?? '', !empty($value['errstr']) ? ', 错误: '.$value['errstr'] : '').PHP_EOL;
				}
			} else {
				foreach (self::$_error as $value) {
					$msg .= sprintf('[ %s ] 文件: %s, 行: %s %s', $this->getErrorText($value['errno'] ?? 0), $value['errfile'] ?? '', $value['errline'] ?? '', !empty($value['errstr']) ? ', 错误: '.$value['errstr'] : '').PHP_EOL;
				}
				echo '<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=0.5, maximum-scale=2.0, user-scalable=yes" /><style>*{padding:0;margin:0;}img{max-width:100%;max-height:100%;}</style><div style="width: 100%;text-align:center;padding-top:200px;"><a href="'.url('').'"><img src="'.mediaUrl('image/common/error.png').'"/></a></div>';
			}
			\App::Log($msg);
		}
		exit();
	}

	private function getErrorText($type)
	{
		switch ($type) {
		    case E_PARSE:
		    	$text = 'FATAL';
		        break;
		    case E_WARNING:
		    	$text = 'WARNING';
		        break;
		    case E_NOTICE:
		    	$text = 'NOTICE';
		        break;
		    case E_STRICT:
		    	$text = 'FATAL';
		    	break;
		    default:
		    	$text = 'UNKNOWN';
		        break;
		    }
		return $text;
	}
}