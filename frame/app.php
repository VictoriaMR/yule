<?php
class App 
{
	private static $_instance = null;

    public static function instance() 
    {
    	if (!(self::$_instance instanceof self)) {
			self::$_instance = new self();
		}
		return self::$_instance;
    }

	public static function run() 
	{
		//初始化方法
		self::init();
        //注册异常处理
        \frame\Router::analyze();
		//解析路由
		return self::instance();
	}

    public function send()
    {
        //路由解析
        $info = \frame\Router::$_route;
        $class = 'App\\Http\\Controllers\\'.$info['class'].'\\'.$info['path'].'Controller';
        //中间组件
        $handle = make('App\Http\Middleware\VerifyToken');
        $handle->handle($info);

        //公共样式
        if (!isAjax()) {
            \frame\Html::addJs(['jquery.min', 'common', 'button'], true);
            \frame\Html::addCss(['common', 'font', 'space', 'icon'], true);
            if ($info['class'] == 'Admin' && !isMobile()) {
                \frame\Html::addCss(['acommon', 'bootstrap', 'plugin'], true);
                \frame\Html::addJs(['acommon', 'bootstrap', 'modal', 'plugin'], true);
            }
        }
        if (is_callable([self::autoload($class), $info['func']])) {
            call_user_func_array([self::autoload($class), $info['func']], []);
            if (env('APP_DEBUG'))
                $this->end(in_array(implode('/', $info), ['Admin/Index/index']));
            else 
                exit();
        } else {
            throw new \Exception(implode('->', [$class, $info['func']]) .' was not exist!', 1);
        }
    }

	public static function init() 
	{
		spl_autoload_register([__CLASS__ , 'autoload']);
        \frame\Error::instance()->register();
	}

	private static function autoload($abstract) 
    {
        //命名空间反斜杠
        $abstract = strtr($abstract, ['/'=>'\\']);
        //容器加载
        if (!empty(Container::$_building[$abstract]))
            return Container::$_building[$abstract];

        $fileName = $abstract;
        $fileName = strtr($fileName, ['\\'=>'/', 'App\\'=>'app/']);
        if (strpos($fileName, 'frame/') !== false)
            $fileName = strtolower($fileName);

        $file = ROOT_PATH.$fileName.'.php';
        if (is_file($file))
			require_once $file;
		else 
			throw new \Exception($abstract.' was not exist!', 1);

        $concrete = Container::getInstance()->autoload($abstract);
		return $concrete;
    }

    public static function make($abstract)
    {
    	return self::autoload($abstract);
    }

    protected function end($show = true)
    {   
        // 应用调试模式
        if (env('APP_DEBUG') && $show)
            \frame\Debug::debugInit();
        exit();
    }

    public static function Log($msg = '')
    {
        $now = date('Y-m-d H:i:s');
        $path = ROOT_PATH.'runtime/'.date('Ymd').'/';
        !is_dir($path) && mkdir($path, 0777, true);
        // 获取基本信息
        if (isset($_SERVER['HTTP_HOST'])) {
            $path .= 'runlog.log';
            $current_uri = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        } else {
            $path .= 'tasklog.log';
            $current_uri = 'cmd:' . implode(' ', $_SERVER['argv']);
        }
        $runtime    = number_format(microtime(true) - APP_TIME_START, 10,'.','');
        $reqs       = $runtime > 0 ? number_format(1 / $runtime, 2,'.','') : '∞';
        $time_str   = '[Time：' . number_format($runtime, 6) . 's] [QPS：' . $reqs . 'req/s]';
        $memory_use = number_format((memory_get_usage() - APP_MEMORY_START) / 1024, 2,'.','');
        $memory_str = '[MEM：' . $memory_use . 'kb]';
        $file_load  = '[Files：' . count(get_included_files()) . ']';
        $server = $_SERVER['SERVER_ADDR'] ?? '0.0.0.0';
        $method = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'CLI';
        $message = error_get_last()['message'] ?? '';
        if (empty($message)) {
            $message = preg_replace('/\s(?=\s)/', '\\1', $msg);
        }
        $str = sprintf('[%s] %s %s %s', $now, $server, $method, $current_uri).PHP_EOL;
        $str .= sprintf('%s %s %s', $time_str, $memory_str, $file_load).PHP_EOL;
        if (!empty($message)) {
            $str .= rtrim($message, PHP_EOL).PHP_EOL;
        }
        $str .= '---------------------------------------------------------------'.PHP_EOL;
        return error_log($str, 3, $path);
    }
}