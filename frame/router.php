<?php

namespace frame;

class Router
{
	public static $_route = []; //路由
	public static $_param = []; //参数

	public static function analyze()
	{
		//网址进行拆分
		$pathInfo = explode(DS, explode('?', trim($_SERVER['REQUEST_URI'] ?? '', DS))[0] ?? '');
		//压入默认站点到路由数组
		if (empty($pathInfo[0])) {
			array_unshift($pathInfo, 'home');
		} elseif (!in_array(strtolower($pathInfo[0]), ['home', 'proxy', 'admin', 'uploads'])) {
			array_unshift($pathInfo, 'home');
		}
        // 类名
        $funcArr = [];
        $funcArr['class'] = array_shift($pathInfo);
		if (count($pathInfo) > 1) {
	        $funcArr['func'] = array_pop($pathInfo);
	       	$funcArr['path'] = $pathInfo;
		} else {
	        $funcArr['path'] = array_pop($pathInfo);
	        $funcArr['func'] = 'index';
		}
        $funcArr = [
			'class' => !empty($funcArr['class']) ? $funcArr['class'] : 'Home',
			'path' => !empty($funcArr['path']) ? $funcArr['path'] : 'Index',
			'func' => !empty($funcArr['func']) ? $funcArr['func'] : 'index',
		];
		self::$_route = self::realFunc($funcArr);
		return true;
	}

	public static function realFunc($funcArr)
	{
		if (empty($funcArr)) return $funcArr;
		foreach ($funcArr as $key => $value) {
			if (empty($value)) continue;
			$funcArr[$key] = $key == 'func' ? lcfirst($value) : (is_array($value) ? implode(DS, array_map('ucfirst', $value)) : ucfirst($value));
		}
		return $funcArr;
	}
}