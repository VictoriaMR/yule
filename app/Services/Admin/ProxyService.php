<?php 

namespace App\Services\Admin;
use App\Services\MemberService as BaseService;
use App\Models\Customer\Member;

class ProxyService extends BaseService
{	
    public function __construct(Member $model)
    {
        $this->baseModel = $model;
    }

    public function getRecommenderList($id)
    {
    	if (empty($id)) return [];
    }
}
