<?php

namespace App\Models;
use App\Models\Base as BaseModel;

class Level extends BaseModel
{
    //表名
    public $table = 'level';
    //主键
    protected $primaryKey = 'lev_id';

    public function getList()
    {
    	return $this->get();
    }
}