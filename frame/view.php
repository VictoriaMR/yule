<?php

namespace frame;

class View 
{
    private static $_instance = null;
    
    protected static $data = [];

    public static function getInstance() 
    {
        if (!self::$_instance instanceof self) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function display($template = '', $rewrite = false)
    {
        $template = $this->getTemplate($template, $rewrite);
        if (!file_exists($template)) {
            throw new \Exception($template . ' 模板不存在', 1);
        }
        extract(self::$data);
        include($template);
        $this->clear();
        return true;
    }

    private function getTemplate($template, $rewrite = false) 
    {
        if ($rewrite) {
            $template = ROOT_PATH.$template.'.php';
        } else {
            if (!empty($template)) 
                $template = Router::$_route['class'].DS.strtr($template, '.', '/');
            else 
                $template = implode(DS, Router::$_route);
            $template = ROOT_PATH.'view'.DS.(APP_TEMPLATE_TYPE ? (isMobile() ? 'mobile' : 'computer').DS : '').$template.'.php';
        }
        return $template;
    }

    private function clear()
    {
        self::$data = [];
    }

    public function fetch($template = '')
    {
        ob_start();
        $this->display($template);
        $content = ob_get_clean();
        return $content;
    }

    public function assign($name, $value = null)
    {
        if (is_array($name)) {
            self::$data = array_merge(self::$data, $name);
        } else {
            self::$data[$name] = $value;
        }
        return $this;
    }

    public function load($template = '')
    {
        return $this->display($template);
    }
}