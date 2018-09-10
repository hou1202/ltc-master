<?php
// +----------------------------------------------------------------------
// | KingTP
// +----------------------------------------------------------------------
// | 2018/1/20 15:01
// +----------------------------------------------------------------------
// | **基于ThinkPHP 5.0.14 + LayUI2.2.5封装**
// +----------------------------------------------------------------------
// | Author: 晓晓攻城师 <邮箱：1228676735@qq.com><博客：http://blog.csdn.net/qq_26245325>
// +----------------------------------------------------------------------

namespace app\api\model;


use app\admin\model\Config;
use app\admin\model\Order;
use app\admin\model\PatientFile;
use app\common\utils\JPushUtils;
use think\Db;
use think\helper\Time;

class Push
{

    public static $sTableName = 'push';

    const STATUS_READ = 1;
    const STATUS_NOT_READ = 0;

    const PUSH_TYPE_PUSH = 0;
    const PUSH_TYPE_COMMIT = 1;
    const PUSH_TYPE_ZHANGDAN = 2;

    public static $sMsgs = [self::PUSH_TYPE_PUSH=>'会诊提醒', self::PUSH_TYPE_COMMIT=>'会诊完成', self::PUSH_TYPE_ZHANGDAN=>'账单信息'];

    /**
     * 推送病人档案消息
     * @param $userId
     * @param $shopId
     * @param $fileId
     * @param $orderId
     * @param $msg
     */
    public static function pushOrder($userId, $shopId, $fileId, $orderId, $msg){
        $content = User::getDoctorInfo($userId);
        $msgId = Db::name(static::$sTableName)->insert(['send_id' => $userId, 'type'=>self::PUSH_TYPE_PUSH, 'receive_id' => $shopId, 'file_id' => $fileId, 'order_id'=>$orderId, 'msg' => '病人：'.$msg, 'content'=>$content.'向您推送了远程诊断'], false, true);
        JPushUtils::push($shopId, $orderId, $fileId, 0, 0, $msgId, JPushUtils::CATE_ORDER_PUSH);
    }

    /**
     * 添加诊断完成消息
     * @param $userId
     * @param $shopId
     * @param $fileId
     * @param $orderId
     * @param $msg
     */
    public static function diagnoseOrder($userId, $shopId, $fileId, $orderId, $msg){
        $content = User::getDoctorInfo($shopId);
        $msgId = Db::name(static::$sTableName)->insert(['send_id' => $shopId, 'type'=>self::PUSH_TYPE_COMMIT, 'receive_id' => $userId, 'file_id' => $fileId, 'order_id'=>$orderId, 'msg' => '病人：'.$msg, 'content'=>$content.'已完成会诊'], false, true);
        JPushUtils::push($userId, $orderId, $fileId, 1, 1, $msgId, JPushUtils::CATE_ORDER_COMMIT);
    }

    /**
     * 推送账单
     * @param $receiveId
     * @param $zdId
     * @param $msg
     */
    public static function pushZd($receiveId, $zdId, $msg){
        $msgId = Db::name(static::$sTableName)->where('zd_id='.$zdId)->value('msg_id', 0);
        if( $msgId == 0 ){
            $msgId = Db::name(static::$sTableName)->insert(['type'=>self::PUSH_TYPE_ZHANGDAN, 'receive_id' => $receiveId, 'zd_id' => $zdId, 'msg' => $msg, 'content'=>'请您在7日内核对，如有问题，请联系客服'], false, true);
        }
        JPushUtils::push($receiveId, 0, 0, 0, 0, $msgId, JPushUtils::CATE_ZHANGDAN);
    }

    /**
     * 获取消息列表
     * @param $userId
     * @param $page
     * @param $searchName
     * @return false|\PDOStatement|string|\think\Collection
     */
    public static function getApiList($userId, $page, $searchName){
        $query = Db::name(static::$sTableName)->field('msg_id,type,msg,content,file_id,status,c_time')->where('receive_id='.$userId.' AND is_del=0');
        if(!empty($searchName)){
            $searchName = '%'.$searchName.'%';
            $query = $query->where(['msg|content'=>['like', $searchName]]);
        }
        $msgs = $query->limit($page*10, 10)->order('msg_id desc')->select();
        foreach($msgs as $k=>$v){
            $msgs[$k]['c_time'] = Time::dateFormat($v['c_time']);
            $msgs[$k]['type_info'] = static::$sMsgs[$v['type']];
        }
        return $msgs;
    }

    /**
     * 获取Index消息列表
     * @param $userId
     * @param $page
     * @param $searchName
     * @return false|\PDOStatement|string|\think\Collection
     */
    public static function getIndexList($userId){
        return Db::name(static::$sTableName)->alias('m')
            ->field('m.msg_id,m.type,m.status,m.file_id,u.poster,u.real_name,f.patient_name')
            ->join('p_user u', 'u.user_id=m.send_id AND m.type<2')
            ->join('p_patient_file f', 'f.file_id=m.file_id')
            ->where('m.receive_id='.$userId.' AND m.is_del=0')
            ->order('msg_id desc')->select();
    }

    /**
     * 获取消息详情
     * @param $msgId
     * @param $userId
     * @return array|false|\PDOStatement|\stdClass|string|\think\Model
     * @throws \think\Exception
     */
    public static function getApiDetail($msgId, $userId){
        $msg = Db::name(static::$sTableName)->field('msg_id,type,msg,content,file_id,status,c_time')->where('msg_id='.$msgId.' AND receive_id='.$userId)->find();
        if($msg==null){
            return new \stdClass();
        }
        if($msg['status'] == self::STATUS_NOT_READ){
            Db::name(static::$sTableName)->where('msg_id='.$msgId)->update(['status'=>self::STATUS_READ]);
        }
        $msg['is_complete'] = 0;
        if($msg['type'] == 0){
            //判断该订单是否完成
            $msg['is_complete'] = PatientFile::getStatusByFileId($msg['file_id']) == PatientFile::STATUS_COMPLETE ? 1:0;
        }
        $msg['service_tel'] = Config::getServiceTel();
        return $msg;
    }

    /**
     * 更新信息状态
     * @param $msgId
     * @param $userId
     * @throws \think\Exception
     */
    public static function updateMsgStatus($msgId, $userId){
        $msg = Db::name(static::$sTableName)->field('status')->where('msg_id='.$msgId.' AND receive_id='.$userId)->find();
        if($msg!=null && $msg['status']==self::STATUS_NOT_READ){
            Db::name(static::$sTableName)->where('msg_id='.$msgId)->update(['status'=>self::STATUS_READ]);
        }
    }

    /**
     * 删除消息
     * @param $msgId
     * @param $userId
     * @return bool
     * @throws \think\Exception
     */
    public static function del($msgId, $userId){
        Db::name(static::$sTableName)->where('msg_id='.$msgId.' AND receive_id='.$userId)->update(['is_del'=>time()]);
        return true;
    }

    /**
     * 获取未读消息数量
     * @param $userId
     * @return int
     */
    public static function getNotReadCount($userId){
        return (int)Db::name(static::$sTableName)->where('receive_id='.$userId.' AND status=0')->count();
    }



}