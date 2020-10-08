<?php 

namespace App\Services\Customer;
use App\Services\MemberService as BaseService;
use App\Models\Customer\Member;

class MemberService extends BaseService
{	
    public function __construct(Member $model)
    {
        $this->baseModel = $model;
    }

    public function loginByPassword($phone, $password)
    {
        if (empty($phone) || empty($password)) return false;
        $info = $this->getInfoByPhone($phone);
        if (empty($info)) return false;
        $res = $this->checkPassword($this->getPasswd($password, self::PASSWORD_SALT), $info['password']);
        if (!$res) return false;
        return $this->login($info['mem_id'], 3);
    }

    public function getProxyId($memId)
    {
        if (empty($memId)) return [];
        return $this->baseModel->where('recommender', $memId)
                               ->value('mem_id');
    }
}