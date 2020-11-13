<?php

namespace frame;

class Http 
{
    public static function get($url, $params = '', $header = [], $timeout = 30, $options = [])
    {
        return self::send($url, $params, 'GET', $header, $timeout, $options);
    }

    /**
     * POST请求
     * @param string $url 请求的地址
     * @param mixed $params 传递的参数
     * @param array $header 传递的头部参数
     * @param int $timeout 超时设置，默认30秒
     * @param mixed $options CURL的参数
     * @return array|string
     */
    public static function post($url, $params = '', $header = [], $timeout = 30, $options = [])
    {
        return self::send($url, $params, 'POST', $header, $timeout, $options);
    }

    /**
     * DELETE请求
     * @param string $url 请求的地址
     * @param mixed $params 传递的参数
     * @param array $header 传递的头部参数
     * @param int $timeout 超时设置，默认30秒
     * @param mixed $options CURL的参数
     * @return array|string
     */
    public static function delete($url, $params = '', $header = [], $timeout = 30, $options = [])
    {
        return self::send($url, $params, 'DELETE', $header, $timeout, $options);
    }

    /**
     * PUT请求
     * @param string $url 请求的地址
     * @param mixed $params 传递的参数
     * @param array $header 传递的头部参数
     * @param int $timeout 超时设置，默认30秒
     * @param mixed $options CURL的参数
     * @return array|string
     */
    public static function put($url, $params = '', $header = [], $timeout = 30, $options = [])
    {
        return self::send($url, $params, 'PUT', $header, $timeout, $options);
    }

    /**
     * 下载远程文件
     * @param string $url 请求的地址
     * @param string $savePath 本地保存完整路径
     * @param mixed $params 传递的参数
     * @param array $header 传递的头部参数
     * @param int $timeout 超时设置，默认3600秒
     * @return bool|string
     */
    public static function down($url, $savePath, $params = '', $header = [], $timeout = 3600)
    {
        if (!is_dir(dirname($savePath))) {
            Dir::create(dirname($savePath));
        }
        
        $ch = curl_init();
        $fp = fopen($savePath, 'wb');

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header ? : ['Expect:']);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_NOPROGRESS, 0);
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($ch, CURLOPT_BUFFERSIZE, 64000);
        curl_setopt($ch, CURLOPT_POST, FALSE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);

        $res        = curl_exec($ch);
        $curlInfo   = curl_getinfo($ch);

        if (curl_errno($ch) || $curlInfo['http_code'] != 200) {
            curl_error($ch);
            @unlink($savePath);
            return false;
        } else {
            curl_close($ch);
        }

        fclose($fp);

        return $savePath;
    }

    /**
     * CURL发送Request请求,支持GET、POST、PUT、DELETE
     * @param string $url 请求的地址
     * @param mixed $params 传递的参数
     * @param string $method 请求的方法
     * @param array $header 传递的头部参数
     * @param int $timeout 超时设置，默认30秒
     * @param mixed $options CURL的参数
     * @return array|string
     */
    private static function send($url, $params = '', $method = 'GET', $header = [], $timeout = 30, $options = [])
    {
        $ch = curl_init();
        $opt                            = [];
        $opt[CURLOPT_COOKIEJAR]         = $cookieFile ?? '';
        $opt[CURLOPT_COOKIEFILE]        = $cookieFile ?? '';
        $opt[CURLOPT_CONNECTTIMEOUT]    = $timeout;
        $opt[CURLOPT_TIMEOUT]           = $timeout;
        $opt[CURLOPT_RETURNTRANSFER]    = true;
        $opt[CURLOPT_HTTPHEADER]        = $header ? : ['Expect:'];
        $opt[CURLOPT_FOLLOWLOCATION]    = true;

        if (substr($url, 0, 8) == 'https://') {
            $opt[CURLOPT_SSL_VERIFYPEER] = false;
            $opt[CURLOPT_SSL_VERIFYHOST] = 2;
        }

        if (is_array($params)) {
            $params = http_build_query($params);
        }

        switch (strtoupper($method)) {
            case 'GET':
                $extStr             = (strpos($url, '?') !== false) ? '&' : '?';
                $opt[CURLOPT_URL]   = $url . (($params) ? $extStr . $params : '');
                break;
            case 'POST':
                $opt[CURLOPT_POST]          = true;
                $opt[CURLOPT_POSTFIELDS]    = $params;
                $opt[CURLOPT_URL]           = $url;
                break;
            case 'PUT':
                $opt[CURLOPT_CUSTOMREQUEST] = 'PUT';
                $opt[CURLOPT_POSTFIELDS]    = $params;
                $opt[CURLOPT_URL]           = $url;
                break;
            case 'DELETE':
                $opt[CURLOPT_CUSTOMREQUEST] = 'DELETE';
                $opt[CURLOPT_POSTFIELDS]    = $params;
                $opt[CURLOPT_URL]           = $url;
                break;
            default:
                return ['error' => 0, 'msg' => '请求的方法不存在', 'info' => []];
                break;
        }

        curl_setopt_array($ch, (array) $opt + $options);
        $result = curl_exec($ch);
        $error  = curl_error($ch);

        if ($result == false || !empty($error)) {
            $errno  = curl_errno($ch);
            $info   = curl_getinfo($ch);
            curl_close($ch);
            return [
                'errno' => $errno,
                'msg'   => $error,
                'info'  => $info,
            ];
        }

        curl_close($ch);

        return $result;
    }

    public static function download($url)
    {
        //低版本需要将中文文件名utf-8转换成gb2312，否则找不到文件
        $fileName = iconv('utf-8', 'gb2312', $url);
        //绝对路径
        if (!file_exists($fileName)) {
            return false;
        }
        //打开文件，返回句柄,r以只读的方式
        $fp = fopen($fileName, 'r');
        //获取文件大小，单位是byte
        $file_size = filesize($fileName);
        //声明返回的是文件类型
        header("Content-type:application/octet-stream");
        //按照字节大小返回
        header("Accept-Ranges:bytes");
        //返回文件大小
        header("Accept-Length:$file_size");
        //客户端弹出对话框，对应的名字
        $name = explode('/', $fileName);
        $name = end($name);
        header("Content-Disposition:attachment;filename=" . $name);
        //向客户端回送数据
        //每次发送的大小
        $buffer = 1024;
        //为了下载的安全，我们最好做一个文件直接读取计数器
        $file_count = 0;
        //判断文件是否结束
        while (!feof($fp) && ($file_size - $file_count) > 0) {
            $file_data = fread($fp, $buffer);
            //把部分数据回送给浏览器
            $file_count += $buffer;
            echo $file_data;
        }
        //关闭文件
        fclose($fp);
        exit();
    }
}