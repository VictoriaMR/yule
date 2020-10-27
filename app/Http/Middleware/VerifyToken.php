<?php

namespace App\Http\Middleware;
use \frame\Session;

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
                $keyId = 'home_mem_id';
                break;
            case 'Proxy':
                $keyId = 'proxy_mem_id';
                break;
            case 'Admin':
                $keyId = 'admin_mem_id';
                break;
        }

        if (!empty($keyId)) {
            if (empty(Session::get('home_mem_id'))) {
                //记录跳转地址
                Session::set('callback_url', $_SERVER['REQUEST_URI'].(!empty($_SERVER['QUERY_STRING']) ? '?'.$_SERVER['QUERY_STRING'] : ''));
                redirect(url('login'));
            }
        }
        return true;
    }
}
