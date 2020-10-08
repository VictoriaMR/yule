<?php 

namespace App\Services;
use App\Services\Base as BaseService;
use App\Models\Member;

class MemberService extends BaseService
{	
    const INFO_CACHE_KEY = 'MEMBER_INFO_CACHE_';
    const INFO_CACHE_EXPIRETIME = 60*60*24;
    const TOKEN_EXPIRED = 60*60*8;
    const REFRESH_TOKEN_EXPIRED = 60*60*24*15;
    const SPECIAL_MEMBER = [1000000000];
    const PASSWORD_SALT = 'baiwangyule';

	public function __construct(Member $model)
    {
        $this->baseModel = $model;
    }

    public function addMember($data)
    {
        return $this->baseModel->addMember($data);
    }

    public function updateUserAvatar($memberId, $url)
    {
        if (empty($memberId) || empty($url)) return false;

        $fileService = make('App/Services/FileService');
        $attach = $fileService->uploadByUrl($url, 'avatar');
        if (empty($attach)) return false;
        //加入关联
        $attachmentService = make('App\Services\AttachmentService');
        $attachmentService->updateData($memberId, $attach['attach_id'], $attachmentService::constant('TYPE_MEMBER_AVATAR'));
        // 更新用户信息
        $res = $this->updateDataById($memberId, ['avatar'=>$attach['path'].DS.$attach['name'].'.'.$attach['type']]);
        if ($res) {
            $this->deleteCache($memberId);
        }
        return $res;
    }

    public function getInfoCache($memberId)
    {
        if (empty($memberId)) return [];
        $cacheKey = self::INFO_CACHE_KEY.$memberId;
        $info = redis()->get($cacheKey);
        if (empty($info)) {
            $info = $this->getInfo($memberId);
            redis()->set($cacheKey, $info, self::INFO_CACHE_EXPIRETIME);
        }
        return $info;
    }

    public function getInfo($memberId)
    {
        if (empty($memberId)) return [];
        $info = $this->loadData($memberId);
        if (empty($info)) return [];
        $info['avatar'] = !empty($info['avatar']) ? mediaUrl('upload'.DS.$this->_thumbImage($info['avatar'])) : $this->getDefaultAvatar($memberId, $info['sex']);
        unset($info['password']);
        return $info;
    }

    protected function _thumbImage($image)
    {
        if (empty($image)) return '';
        $temp = explode(DS, $image);
        $count = count($temp);
        $temp[$count] = $temp[$count - 1];
        $temp[$count-1] = '_thumb';
        return implode(DS, $temp);
    }

    public function getDefaultAvatar($memberId, $sex=0)
    {
        $type = substr($memberId, 0, 1);
        switch ($type) {
            case 1:
                if ($sex)
                    return mediaUrl('image'.DS.'computer/male.jpg');
                else
                    return mediaUrl('image'.DS.'computer/female.jpg');
                break;
            case 3:
                if ($sex)
                    return mediaUrl('image'.DS.'computer/male.jpg');
                else
                    return mediaUrl('image'.DS.'computer/female.jpg');
                break;
            case 5:
                if ($sex)
                    return mediaUrl('image'.DS.'computer/male.jpg');
                else
                    return mediaUrl('image'.DS.'computer/female.jpg');
                break;
            
            default:
                if ($sex)
                    return mediaUrl('image'.DS.'computer/male.jpg');
                else
                    return mediaUrl('image'.DS.'computer/female.jpg');
                break;
        }
    }

    public function deleteCache($memberId)
    {
        $cacheKey = self::INFO_CACHE_KEY.$memberId;
        return redis()->del($cacheKey);
    }

    public function isExist($memberId)
    {
        return $this->baseModel->isExist($memberId);
    }

    public function isExistCode($code)
    {
        return $this->baseModel->isExistCode($code);
    }

    public function isExistMobile($mobile, $noIn = '')
    {
        if (empty($mobile)) return false;
        $this->baseModel->where('mobile', $mobile);
        if (!empty($noIn))
            $this->baseModel->where('mem_id', '<>', (int)$noIn);
        return $this->baseModel->count() > 0;
    }

    public function login($memberId, $type=1)
    {
        if (empty($memberId)) return false;
        $info = $this->getInfoCache($memberId);
        if (empty($info)) return false;
        $key = $this->getTypeText($type);
        \frame\Session::set($key, $info);
        return true;
    }

    public function getPasswd($password, $salt)
    {
        $passwordArr = str_split($password);
        $saltArr = str_split($salt);
        $countpwd = count($passwordArr);
        $countSalt = count($saltArr);
        $password = '';
        if ($countSalt > $countpwd) {
            foreach ($saltArr as $key => $value) {
                $password .= $passwordArr[$key] ?? '' . $value;
            }
        } else {
            $i = 0;
            $sign = floor($countpwd / $countSalt);
            foreach ($passwordArr as $key => $value) {
                $password .= $value;
                if ($key % $sign == 0) {
                    if (empty($saltArr[$i])) $i = 0;

                    $password .= $saltArr[$i];
                    $i ++;
                }
            }
        }
        return $password;
    }

    public function getInfoByPhone($phone)
    {
        return $this->baseModel->where('mobile', $phone)->find();
    }

    public function getCode($len = 32)
    {
        return $this->baseModel->getCode($len);
    }

    public function checkPassword($inPassword, $sourcePassword)
    {
        return password_verify($inPassword, $sourcePassword);
    }

    protected function getTypeText($type)
    {
        $data = [
            '0' => 'guest',
            '1' => 'home',
            '3' => 'customer',
            '5' => 'admin',
        ];
        return $data[$type] ?? '';
    }

    protected function generateToken($memberId, $type)
    {
        $token = $refreshToken = null;
        $maxTrayCount = 10;
        
        //生成token
        $counter = 0;
        do {
            $token = \frame\Str::random(32);
        } while (redis(1)->exists($token) && ($counter++) < $maxTrayCount);

        $counter = 0;
        do {
            $refreshToken = \frame\Str::random(32);
        } while (redis(1)->exists($refreshToken) && ($counter++) < $maxTrayCount);

        if (empty($token) || empty($refreshToken)) return false;
        
        $expiresIn = self::TOKEN_EXPIRED; //8小时
        $refreshExpiresIn = self::REFRESH_TOKEN_EXPIRED; //15天
               
        redis(1)->set($token, implode(':', [$memberId, $type, $refreshToken]));
        redis(1)->set($refreshToken, implode(':', [$memberId, $type, $token, $refreshExpiresIn, time()]), $refreshExpiresIn);
        
        return [
            'access_token' => $token,
            'refresh_token' => $refreshToken,
            'expires_in' => $expiresIn, // 换成秒
        ];
    }

    public function getToken($token)
    {
        if (empty($token)) return false;
        $data = redis(1)->get($token);
        if (empty($data)) return false;
        return array_combine(['member_id', 'type', 'refresh_token'], explode(':', $data));
    }

    public function checkToken($token, $type)
    {
        $data = $this->getToken($token);
        if (empty($data)) return false;
        return $type == $data['type'];
    }

    public function getRefreshToken($refreshToken)
    {
        if (empty($refreshToken)) return false;
        $data = redis(1)->get($refreshToken);
        if (empty($data)) return false;
        return array_combine(['member_id', 'type', 'token', 'expires', 'create_at'], explode(':', $data));
    }

    public function refreshToken($refreshToken) 
    {
        $data = $this->getRefreshToken($refreshToken);
        if (empty($data)) return false;
        $maxTrayCount = 10;
        //生成token
        $counter = 0;
        do {
            $token = \frame\Str::random(32);
        } while (redis(1)->exists($token) && ($counter++) < $maxTrayCount);

        redis(1)->set($token, implode(':', [$memberId, $type, $refreshToken]), self::TOKEN_EXPIRED);

        return $token;
    }

    public function specialMember($memberId)
    {
        return in_array($memberId, self::SPECIAL_MEMBER);
    }

    public function getTotal($where = [])
    {
        return $this->baseModel->where($where)->count();
    }

    public function getList($where = [], $page=1, $size=20, $orderby=[])
    {
        $list = $this->baseModel->where($where)
                                ->page($page, $size)
                                ->orderBy($orderby)
                                ->get();
        if (!empty($list)) {
            foreach ($list as $key => $value) {
                $value['avatar'] = !empty($value['avatar']) ? url('upload'.DS.$value['avatar']) : $this->getDefaultAvatar($value['mem_id'], $value['sex']);
                unset($value['password']);
                $list[$key] = $value;
            }
        }
        return $list;
    }
}
