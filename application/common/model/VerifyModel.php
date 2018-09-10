<?php
/**
 * Project: 基础库-验证码类
 * User: Zhu Ziqiang
 * Date: 2017/10/12
 * Time: 15:53
 */

namespace app\common\model;


use think\Config as ThinkConfig;
use think\Config;
use think\Db;
use think\Request;

class VerifyModel
{

    const TYPE_REG = 0;       //注册
    const TYPE_EDIT_PASSWD = 1;     //忘记密码
    const TYPE_SIGN = 2;     //电子签名


    public static $sVerifyLogTable = 'zp_log_verify';

    public static $sVerifyPrefix = [
        self::TYPE_REG => 'login_',
        self::TYPE_EDIT_PASSWD => 'epass_',
        self::TYPE_SIGN => 'cooperation_'
    ];

    /**
     * 发送验证码
     * @param $type int 验证码类型
     * @param $mobile string 手机号
     * @param $logData array 日志记录
     * @return bool
     */
    public function send($type, $mobile, $logData){
        if(Config::get('app_debug')){
            return true;
        }

        $verifyPrifix = static::$sVerifyPrefix[$type];
        $cache = RedisCache::newInstance();
        //发送验证码
        $verifyCode = rand(100000, 999999);
        if ($this->sendVerify($mobile, $verifyCode)) {
            //记录验证码
            $logData['verify'] = $verifyCode;
            Db::table(static::$sVerifyLogTable)->insert($logData);
            $cache->set($verifyPrifix . $mobile, $verifyCode, 10 * 60);
            $cache->set($verifyPrifix . 's_' . $mobile, $verifyCode, 60);     //用于计时60秒之内不可重复获取验证码
            return true;
        }
        return false;
    }

    /**
     * 检查验证码是否正确
     * @param $code string 验证码
     * @param $type int    验证码类型
     * @param $mobile string 手机号
     * @return bool
     */
    public static function check($code, $type, $mobile){
        if(Config::get('app_debug')){
            return true;
        }
        $cache = RedisCache::newInstance();
        return $code == $cache->get(self::$sVerifyPrefix[$type] . $mobile);
    }

    /**
     * 刷新验证码  在成功操作过后刷新（比如注册成功，修改密码成功）
     * @param $code string 验证码
     * @param $type int    验证码类型
     * @param $mobile string 手机号
     * @throws \think\Exception
     */
    public static function flushVerify($code, $type, $mobile){
        $cache = RedisCache::newInstance();
        //删除该验证码
        $verifyPrifix = static::$sVerifyPrefix[$type];
        $key = $verifyPrifix . $mobile;
        $cache->del($key);
        //更新数据库log
        Db::table(static::$sVerifyLogTable)->where('mobile='.$mobile.' AND type='.$type.' AND verify='.$code)->update(['status'=>1, 'e_time'=>date('Y-m-d H:i:s')]);
    }

    /**
     * 是否有发送的权限
     * @return bool
     */
    public static function hasAuth(){
        return Db::table(static::$sVerifyLogTable)->where('ip like \''.Request::instance()->ip().'\' AND status=0 AND c_time>=\''.date('Y-m-d').'\'')->count()<=100;
    }

    /**
     * 向验证码服务器发送请求
     * @param $mobile string 手机号
     * @param $verifyCode string 验证码
     * @return bool
     */
    private function sendVerify($mobile, $verifyCode)
    {
        /*$text = '您的验证码为：' . $verifyCode . '，请在10分钟内完成验证';
        $objecturl = 'http://web.cr6868.com/asmx/smsservice.aspx?name='
            . ThinkConfig::get('sms_account')
            . '&pwd='.ThinkConfig::get('sms_password')
            . '&mobile='.$mobile
            . '&content='.$text
            . '&sign=单去哪儿'.'&type=pt&stime='
        ;
        try {
            $results = file_get_contents($objecturl);
        }catch(\Exception $e){
            Log::error($e->getMessage());
            return false;
        }
        $res = explode(',', $results);
        return $res[0] == 0 ;*/
        $text = '【LTC】您的验证码为：' . $verifyCode . '，请在10分钟内完成验证';
        $objecturl = 'https://dx.ipyy.net/sms.aspx?action=send&userid=&account='
            . ThinkConfig::get('sms_account')
            . '&password='
            . ThinkConfig::get('sms_password')
            . '&mobile='
            . $mobile
            . '&content=' . urlencode($text) . '&sendTime=&extno=';
        try {
            $results = file_get_contents($objecturl);
        }catch(\Exception $e){
            return false;
        }
        $results = explode('<returnstatus>', $results);
        $results = explode('</returnstatus>', $results[1]);
        $results = $results[0];
        return $results == 'Success' ? true : false;
    }




}
