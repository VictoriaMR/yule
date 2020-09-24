<?php

namespace App\Models;
use App\Models\Base as BaseModel;

class Ffc extends BaseModel
{
    //表名
    public $table = 'tx_ffc';
    //主键
    protected $primaryKey = 'ffc_id';

    public function addIfNotExist($qishu, array $data)
    {
        if (empty($qishu) || empty($data)) return false;
        if ($this->isExist($qishu)) return true;
        $data['create_at'] = $this->getTime();
        $data['ffc_key'] = $qishu;
        return $this->insert($data);
    }

    public function isExist($qishu)
    {
    	return $this->where('ffc_key', $qishu)->count() > 0;
    }
}