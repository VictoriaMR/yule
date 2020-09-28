<?php

namespace App\Http\Controllers;

class Controller 
{
    protected $tabs = '';
    protected $mem_id = 0;

    protected function result($code, $data=[], $options=[])
    {
       $data = [
            'code' => $code,
            'data' => $data
        ];
        if (!empty($options)) {
            if (!is_array($options)) {
                $options = ['message' => $options];
            } else if (!empty($options[0])) {
                $options['message'] = $options[0];
                unset($options[0]);
            }
        }
        $data = array_merge($data, $options);
        header('Content-Type:application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit();
    }

    protected function error($msg, $data = false)
    {
        return $this->result(10000, $data, $msg);
    }

    protected function success($msg, $data = true)
    {
        return $this->result(200, $data, $msg);
    }

    protected function assign($name = '', $value = '')
    {
        return \frame\View::getInstance()->assign($name, $value);
    }

    protected function _init()
    {
        $this->mem_id = \frame\Session::get('home_mem_id');
    }
}
