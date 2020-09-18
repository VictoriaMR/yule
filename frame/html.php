<?php 

namespace frame;

class Html
{
	public static $_CSS = [];
	public static $_JS = [];

	public static function addCss($name = '', $public = false)
	{
		if (empty($name)) $name = [Router::$_route['func']];
		if (!is_array($name)) $name = [$name];
		foreach ($name as $value) { 
			$str = (APP_TEMPLATE_TYPE ? (isMobile() ? 'mobile' : 'computer').DS : '');
			if (!$public)
				$str .= strtolower(Router::$_route['class'].DS.Router::$_route['path']).DS;
			$str .= $value;
			self::$_CSS[] = env('APP_DOMAIN').'css'.DS.$str.'.css';
		}
		return true;
	}

	public static function addJs($name = '', $public = false)
	{
		if (empty($name)) $name = [Router::$_route['func']];
		if (!is_array($name)) $name = [$name];
		foreach ($name as $value) {
			$str = (APP_TEMPLATE_TYPE ? (isMobile() ? 'mobile' : 'computer').DS : '');
			if (!$public)
				$str .= strtolower(Router::$_route['class'].DS.Router::$_route['path']).DS;
			$str .= $value;
			self::$_JS[] = env('APP_DOMAIN').'js'.DS.$str.'.js';
		}
		return true;
	}

	public static function getCss()
	{
		if (empty(self::$_CSS)) return [];
		return array_unique(self::$_CSS);
		$name = md5(implode(',', array_unique(self::$_CSS)));

		$path = ROOT_PATH.'public'.DS.'css'.DS.'_temp'.DS;
		if (!is_dir($path))
			mkdir($path, 0777, true);
		$path .= $name.'.css';
		if (true || !is_file($path)) {
			$str = '';
			foreach (self::$_CSS as $value) {
				$str .= file_get_contents(ROOT_PATH.'public'.DS.'css'.DS.$value.'.css');
			}
			file_put_contents($path, Str::compressCss($str));
			unset($str);
		}
		return str_replace(ROOT_PATH.'public'.DS, env('APP_DOMAIN'), $path);
	}

	public static function getJs()
	{
		if (empty(self::$_JS)) return [];
		return array_unique(self::$_JS);

		$name = md5(implode(',', array_unique(self::$_JS)));

		$path = ROOT_PATH.'public'.DS.'js'.DS.'_temp'.DS;
		if (!is_dir($path))
			mkdir($path, 0777, true);
		$path .= $name.'.js';
		if (true || !is_file($path)) {
			$str = '';
			foreach (self::$_JS as $value) {
				$temp = file_get_contents(ROOT_PATH.'public'.DS.'js'.DS.$value.'.js');
				if (strpos($value, '.min') === false)
					$temp = Str::compressJs($temp);
				$str .= $temp;
			}
			file_put_contents($path, $str);
			unset($str);
			unset($temp);
		}
		return str_replace(ROOT_PATH.'public'.DS, env('APP_DOMAIN'), $path);
	}
}