<?php

namespace App\Http\Middleware;

class VerifyToken
{
    protected static $except = [
        'Home/Login/index',
        'Home/Login/login',
        'Proxy/Login/index',
        'Proxy/Login/login',
        'Admin/Login/index',
        'Admin/Login/login',
    ];

    public function handle($request)
    {
        return false;
        if (in_array(implode('/', $request), self::$except)) {
            return true;
        }

        //检查登录状态
        switch ($request['class']) {
            case 'Home':
                if (empty(\frame\Session::get('home_mem_id'))) {
                    redirect(url('login'));
                }
                break;
            case 'Proxy':
                if (empty(\frame\Session::get('proxy_mem_id'))) {
                    redirect(url('login'));
                }
                break;
            case 'Admin':
                if (empty(\frame\Session::get('admin_mem_id'))) {
                    redirect(url('login'));
                }
                break;
        }
        return true;
    }
}
