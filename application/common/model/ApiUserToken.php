<?php
/**
 * Project: 基础库-APP 用户TOKEN存储
 * User: Zhu Ziqiang
 * Date: 2017/3/17
 * Time: 14:48
 */

namespace app\common\model;


use app\api\model\FailureToken;
use app\api\model\User;
use think\Db;

class ApiUserToken
{
    private static $instance;
    /**
     * @var RedisCache
     */
    private $mRedisCache;
    private $tokenTime = 7 * 24 * 3600;
    private $updateTime = 24 * 3600;

    private function __construct()
    {
        $this->mRedisCache = RedisCache::newInstance();
    }

    /**
     * @return ApiUserToken
     */
    public static function newInstance()
    {
        if (ApiUserToken::$instance == null) {
            ApiUserToken::$instance = new ApiUserToken();
        }
        return ApiUserToken::$instance;
    }

    /**
     * 设置用户TOKEN
     * @param $mobile
     * @param $token
     * @param $userId
     * @return bool
     */
    public function setApiToken($mobile, $token, $userId)
    {
        $result = $this->mRedisCache->set($mobile, $token);
        return $result ? $this->mRedisCache->set($token, $userId, $this->tokenTime) : $result;
    }

    /**
     * 更新用户唯一TOKEN
     * @param $mobile
     * @param $userId
     * @param $newToken
     * @return bool
     */
    public function updateUniqueApiToken($mobile, $userId, $newToken)
    {
        $this->delTokenKey($mobile, $newToken);
        return $this->setApiToken($mobile, $newToken, $userId);
    }

    /**
     * 删除
     * @param $mobile
     * @param string $newToken
     */
    public function delTokenKey($mobile, $newToken='')
    {
        $token = $this->getToken($mobile);
        if ($token) {
            $this->mRedisCache->del($token);
            //记录用户失效的token
            $newToken!='' && FailureToken::add(['old_token'=>$token, 'new_token'=>$newToken, 'user_id'=>User::getUserIdByMobile($mobile)]);
        }
    }

    /**
     * 通过手机号获取TOKEN
     * @param $mobile
     * @return mixed
     */
    public function getToken($mobile)
    {
        return $this->mRedisCache->get($mobile);
    }

    /**
     * 更新TOKEN的存活时间
     * @param $token
     * @return bool
     */
    public function updateApiTokenTime($token)
    {
        $ttlTime = $this->mRedisCache->ttl($token);
        $result = false;
        if ($ttlTime > 5 && $ttlTime < ($this->tokenTime - $this->updateTime)) {
            $result = $this->mRedisCache->updateTime($token, $this->tokenTime);
        }
        return $result;
    }

    /**
     * 单例模式 私有化克隆
     */
    private function __clone(){}
}