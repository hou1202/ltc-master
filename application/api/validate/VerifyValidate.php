<?php
namespace app\api\validate;


use app\api\model\User as UserModel;
use app\common\model\RedisCache;
use app\common\model\VerifyModel;
use think\Db;
use think\Validate;

class VerifyValidate extends Validate
{

    protected $rule = [
        ['type', 'require|checkType'],
        ['mobile', 'require|checkMobile|checkData'],
    ];

    protected $message = [
        'type.checkType' => '您的操作频繁，请稍后再试',
        'mobile.checkMobile' => '手机号格式不正确',
        'mobile.checkData' => '该IP地址获取验证码已达上限',
    ];

    protected $scene = [
        'get' => ['type', 'mobile'],
    ];

    public function checkType($value, $rule, $data)
    {
        $cache = RedisCache::newInstance();
        if (isset($data['mobile'])) {
            $mobile = $data['mobile'];
        } else {
            return false;
        }
        if(!isset(VerifyModel::$sVerifyPrefix[$value])){
            return false;
        }
        $key = VerifyModel::$sVerifyPrefix[$value] . 's_' . $mobile;
        return !$cache->exists($key);
    }

    public function checkMobile($value)
    {
        return (boolean)preg_match('/^1[23465789]{1}\d{9}$/', $value);
    }

    public function checkData($value, $rule, $data)
    {
        if(!VerifyModel::hasAuth()){
            return false;
        }
        switch ($data['type']) {
            case VerifyModel::TYPE_REG:
                if(UserModel::getUserCountByMobile($value) != 0) {
                    $this->message['mobile.checkData'] = '该手机号已注册';
                    return false;
                }
                break;
            case VerifyModel::TYPE_EDIT_PASSWD:
                if(UserModel::getUserCountByMobile($value) == 0) {
                    $this->message['mobile.checkData'] = '该手机号未注册';
                    return false;
                }
                break;
            default:
                return false;
        }
        return true;
    }

}