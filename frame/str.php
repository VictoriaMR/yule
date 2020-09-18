<?php

namespace frame;

class Str 
{
    public static function random($len) 
    {
        $str = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $key = '';
        for ($i=0; $i<$len; $i++) {
            $key .= $str[mt_rand(0, 32)];//生成php随机数
        }
        return $key;
    }

    public static function compressCss($css)
	{
		if (empty($css)) return '';
		//去除注释
	  	$css = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css);
	  	//去除多个空格
	  	$css = preg_replace("/\s(?=\s)/", "\\1", $css);
	  	//去除换行
	  	$css = str_replace(["\r", "\n", "\t", ';}',': ', ' {', '{ ', '; '], ['', '', '', '}', ':', '{', '{', ';'], $css);
	  	return $css;
	}

	public static function compressJs($js)
	{
		if (empty($js)) return '';
		$temArr = explode(PHP_EOL, $js);
		$js = '';
		$kuai = false;
		foreach ($temArr as $temp) {
			$temp = trim($temp);
			if ($kuai && substr($temp, 0, 1) != '*') $kuai = false;
			if ($kuai) continue;
			if (substr($temp, 0, 2) == '/*' && substr($temp, -2, 2) == '*/') continue;
			if (substr($temp, 0, 2) == '/*') $kuai = true;
			if ($kuai) continue;
			if (substr($temp, 0, 2) == '//') continue;
			if (empty($temp)) continue;
			$temp = preg_replace("/\s(?=\s)/", "\\1", $temp);
			$temp = explode('//', $temp)[0];
			$js .= $temp;
		}
		return $js;
	}
}