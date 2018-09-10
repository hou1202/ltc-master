<?php
// +----------------------------------------------------------------------
// | KingTP
// +----------------------------------------------------------------------
// | 2018/1/20 16:17
// +----------------------------------------------------------------------
// | **基于ThinkPHP 5.0.14 + LayUI2.2.5封装**
// +----------------------------------------------------------------------
// | Author: 晓晓攻城师 <邮箱：1228676735@qq.com><博客：http://blog.csdn.net/qq_26245325>
// +----------------------------------------------------------------------

namespace app\common\utils;


use app\api\model\User;
use JPush\Client;
use JPush\Exceptions\JPushException;
use think\Config;
use think\Log;

class JPushUtils
{
    const CATE_ORDER_PUSH = 0;
    const CATE_ORDER_COMMIT = 1;
    const CATE_CALL = 2;
    const CATE_ZHANGDAN = 3;

    public static $sMsgs = [
        self::CATE_ORDER_PUSH => '您收到一个新的病人档案',
        self::CATE_ORDER_COMMIT => '专家会诊了您的病人档案',
        self::CATE_CALL => '您收到远程会诊',
        self::CATE_ZHANGDAN => '您收到账单',
    ];

    public static $sCates = [
        self::CATE_ORDER_PUSH => 'ORDER_PUSH',
        self::CATE_ORDER_COMMIT => 'ORDER_COMMIT',
        self::CATE_CALL => 'CALL',
        self::CATE_ZHANGDAN => 'BILL',
    ];

    /**
     * 极光推送
     * @param $receiveId int 接受者user_id
     * @param $orderId int 订单order_id
     * @param $fileId int  病人档案file_id
     * @param $status int  订单状态
     * @param $isSend int  是否档案推送者
     * @param $msgId int  消息ID
     * @param $type int   常量
     * @return bool
     */
    public static function push($receiveId, $orderId, $fileId, $status, $isSend, $msgId, $type){
        $alias = User::getMobileByUserId($receiveId);
        $extras = ['category'=>static::$sCates[$type], 'file_id'=>$fileId, 'order_id'=>$orderId, 'msg_id'=>$msgId, 'status'=>$status, 'is_send'=>$isSend];
        $iosSend = ['sound' => 'sound', 'badge' => '+1','extras' => $extras];
        $androidSend = ['extras' => $extras];
        $msg = static::$sMsgs[$type];
        if(Config::get('app_debug')==false){
            $options = ['apns_production'=>false];
        }else{
            $options = ['apns_production'=>true];
        }
        try {
            //$jPushClient = new Client(Config::get('jpush_app_key'), Config::get('jpush_master_secret'), Config::get('jpush_log'));
            $jPushClient = new Client(Config::get('jpush_app_key'), Config::get('jpush_master_secret'), null);
            $push = $jPushClient->push();
            $push->setPlatform(['ios','android'])
                ->addAlias($alias)
                ->iosNotification($msg, $iosSend)
                ->androidNotification($msg, $androidSend)
                ->options($options)
                ->send();
        }catch(JPushException $e){
            Log::error($e->getMessage());
        }
        return true;
    }
}