<?php

namespace App\Models;

use App\Models\Base as BaseModel;

class Member extends BaseModel
{
    //表名
    public $table = 'member';
    //主键
    public $primaryKey = 'mem_id';

    public function addMember($data)
    {
        $relation = make('App/Models/BindRelation');
        $this->begin(); //事务开启
        if (!empty($data['openid'])) {
            $openid = $data['openid'];
            unset($data['openid']);

        }
        $data['code'] = $this->getCode(32);
        $memberId = $this->insertGetId($data);
        //绑定关系
        if (!empty($openid)) {
           $relation->addNotExist($openid, $memberId, $this->getType($memberId));
        }
        $this->commit(); //事务结束
        return $memberId;
    }

    public function getType($memberId)
    {
        return substr($memberId, 0, 1);
    }

    public function getCode($len = 6)
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