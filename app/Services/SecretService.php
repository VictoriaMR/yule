<?php 

namespace App\Services;
use App\Services\Base as BaseService;
use App\Models\Secret;

class SecretService extends BaseService
{	
    const LIST_CACHE_KEY = 'SECRET_LIST_CACHE';

    public function __construct(Secret $model)
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
        return $this->baseModel->getList();
    }

    public function getOne()
    {
        $list = $this->getListCache();
        if (empty($list)) return [];
        return array_column($list, null, 'status')[1] ?? [];
    }

    public function deleteCache()
    {
        return redis()->del(self::LIST_CACHE_KEY);
    }
}
