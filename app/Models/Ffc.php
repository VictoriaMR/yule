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

    public function getList($where, $page, $size)
    {
        return $this->where($where)
                    ->page($page, $size)
                    ->orderBy('ffc_id')
                    ->select(['ffc_key', 'ffc_num1', 'ffc_num2', 'ffc_num3', 'ffc_num4', 'ffc_num5'])
                    ->get();
    }
}