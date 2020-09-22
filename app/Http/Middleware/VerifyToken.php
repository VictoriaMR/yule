<?php

namespace App\Http\Middleware;

class VerifyToken
{
    protected static $except = [
        'Home/Login/index',
        'Home/Login/login',
        'Home/Bjl/index',
        'Customer/Login/index',
        'Customer/Login/login',
        'Admin/Login/index',
        'Admin/Login/login',
    ];

    public function handle($request)
    {
        if (in_array(implode('/', $request), self::$except))
            return true;

        //检查登录状态
        switch ($request['class']) {
            case 'Home':
                if (empty(\frame\Session::get('home_mem_id')))
                    redirect(url('login'));
                break;
            case 'Customer':
                if (empty(\frame\Session::get('customer_mem_id')))
                    redirect(url('login'));
                break;
            case 'Admin':
                if (empty(\frame\Session::get('admin_mem_id')))
                    redirect(url('login'));
                break;
        }
        return true;
    }
}
