<?php 

namespace App\Services;
use App\Services\Base as BaseService;
use App\Models\Level;

class LevelService extends BaseService
{	
    const LIST_CACHE_KEY = 'lEVEL_LIST_CACHE';

    public function __construct(Level $model)
    {
        $this->baseModel = $model;
    }

    public function getListCache()
    {
    	$list = redis()->get(self::LIST_CACHE_KEY);
        if (empty($list)) {
            $list = $this->getList();
            redis()->set(self::LIST_CACHE_KEY, $list);
        }
        return $list;
    }

    public function getList()
    {
        $list = $this->baseModel->getList();
        $list = array_column($list, null, 'lev_id');
        return $list;
    }

    public function deleteCache()
    {
        return redis()->del(self::LIST_CACHE_KEY);
    }
}
