<?php

namespace App\Models\Customer;

use App\Models\Base as BaseModel;

class Member extends BaseModel
{
	//表名
    protected $table = 'customer_member';
    //主键
    protected $primaryKey = 'mem_id';

    public function getCode($len)
    {
    	$key = '';
        $counter = 0;
        do {
            $key = \frame\Str::random($len);
        } while ($this->isExistCode($key) && ($counter++) < 10);
        return $key;
    }

    public function isExist($memberId)
    {
        return $this->where('mem_id', (int) $memberId)->count() > 0;
    }

    public function isExistCode($code)
    {
        return $this->where('code', $code)->count() > 0;
    }
}