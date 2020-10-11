<?php

namespace App\Console;

class Kernal 
{
    const COMMON_LIST = [
        ['App/Services/FfcService', 'getOriginFfc', '1'],
        ['App/Services/OtherService', 'randBlingBjl', '1'],
    ];

    public function run()
    {
        if (empty(self::COMMON_LIST)) return false;
        \App::log();
        if (!empty($_SERVER['argv'][1])) {
            // dd($_SERVER['argv'][2]);
            $func = $_SERVER['argv'][2];
            make($_SERVER['argv'][1])->$func();
            exit();
        }
        $minute = date('i', time());
        foreach (self::COMMON_LIST as $value) {
            if ($this->matchTime($minute, $value[2])) {
                $this->execCommand('php '.ROOT_PATH.'artisan '.$value[0].' '.$value[1]);
            }
        }
        return true;
    }

    private function execCommand($cmd)
    {
        if (strpos(php_uname(), 'Windows') === false) {
            exec($cmd . ' >> /tmp/out.log 2>&1');
        } else {
            pclose(popen('start /B '.$cmd, 'r')); 
        }
    }

    private function matchTime($nowTime, $setTime)
    {
        return $nowTime % $setTime == 0;
    }
}
