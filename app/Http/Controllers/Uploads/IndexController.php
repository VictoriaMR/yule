<?php

namespace App\Http\Controllers\Uploads;
use App\Http\Controllers\Controller;

class IndexController extends Controller
{
	public function index()
	{
		$file = ifile('file'); //长传文件
        $site = input('site', 'product'); //类型

        if (empty($file))
        	$this->error('文件为空');
        if (!in_array($site, ['avatar']))
        	$this->error('无权限上传文件');

        $fileService = make('App/Services/FileService');
        $res = $fileService->upload($file, $site);
        if ($res)
        	$this->success('上传成功', $res);
        else
        	$this->error('上传失败');
	}
}