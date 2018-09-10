<?php

namespace app\admin\model;


use app\common\model\BaseAdminModel;
use app\common\utils\ToolUtils;
use think\Db;

class Config extends BaseAdminModel
{

    public static $sTableName = 'config';

    function index($where, $page, $limit, $order){}

    function totalCount($where){}

    function add($data){}

    function edit($data){
        $configs = $this->column('content', 'id');
        foreach($data as $k=>$v){
            if($configs[$k] !== $v) {
                $this->where('id=' . $k)->update(['content' => $v]);
                if($k == 4 || $k == 5){
                    //生成二维码
                    ToolUtils::makeQrcode($v);
                }
            }
        }
        return true;
    }

    function del(){}

    function getTitle()
    {
        return '系统配置';
    }

    public static function getServiceTel()
    {
        return Db::name(static::$sTableName)->where('id=1')->value('content', '');
    }

    /**
     * 获取ANDROID下载二维码
     * @return string
     */
    public static function getAndroidQrcode(){
        $content = Db::name(static::$sTableName)->where('id=4')->value('content');
        if($content!=null){
            return '/uploads/qrcode/'.md5($content).'png';
        }
        return '';
    }

    /**
     * 获取IOS下载二维码
     * @return string
     */
    public static function getIOSQrcode(){
        $content = Db::name(static::$sTableName)->where('id=5')->value('content');
        if($content!=null){
            return '/uploads/qrcode/'.md5($content).'png';
        }
        return '';
    }

    /**
     * 获取安卓、ios下载的二维码
     * @return array
     */
    public static function getAndroidAndIosQrcode(){
        $columns = Db::name(static::$sTableName)->where('id=5 OR id=4')->column('content', 'id');
        $ios = isset($columns[5]) && !empty($columns[5]) ? '/uploads/qrcode/'.md5($columns[5]).'.png' : '';
        $android = isset($columns[4]) && !empty($columns[4]) ? '/uploads/qrcode/'.md5($columns[4]).'.png' : '';
        return ['ios'=>$ios,'android'=>$android];
    }

    public static function getIos(){
        return Db::name(static::$sTableName)->where('id=5')->value('content');
    }

    public static function getAndroid(){
        return Db::name(static::$sTableName)->where('id=4')->value('content');
    }

    /**
     * 根据类型获取诊断费用比列
     * @param $type '知名专家'|'主任'|'副主任'
     * @return array
     */
    public static function getDiagnosePriceByType($type){
        switch($type){
            case '知名专家': $id = 7;break;
            case '主任': $id=8;break;
            case '副主任': $id=9;break;
            default:return 0;
        }
        $values = Db::name(static::$sTableName)->field('content')->where('id='.$id.' OR id=6')->order('id asc')->select();
        return [bcdiv($values[0]['content'],100,4), bcdiv($values[1]['content'],100,4)];
    }

    /**
     * 获取注册协议
     * @return string
     */
    public static function getAgreementContent(){
        $content = Db::name(static::$sTableName)->where('id=10')->value('content');
        return $content == null ? '' : $content;
    }

    /**
     * 获取关于我们
     * @return string
     */
    public static function getAboutUs(){
        $content = Db::name(static::$sTableName)->where('id=11')->value('content');
        return $content == null ? '' : $content;
    }

    /**
     * 获取常见问题
     * @return string
     */
    public static function getQuestion(){
        $content = Db::name(static::$sTableName)->where('id=12')->value('content');
        return $content == null ? '' : $content;
    }

    /**
     * 获取结算规则APP
     * @return string
     */
    public static function getAppBillRule(){
        $content = Db::name(static::$sTableName)->where('id=13')->value('content');
        return $content == null ? '' : $content;
    }

    /**
     * 获取结算规则WEB
     * @return string
     */
    public static function getWebBillRule(){
        $content = Db::name(static::$sTableName)->where('id=14')->value('content');
        return $content == null ? '' : $content;
    }

    /**
     * 获取默认邀请码
     * @return string
     */
    public static function getInvitationCode(){
        $content = Db::name(static::$sTableName)->where('id=15')->value('content');
        return $content == null ? '' : $content;
    }

    /**
     * 获取每日签到所获得金币
     * @return string
     */
    public static function getSignMoney(){
        $content = Db::name(static::$sTableName)->where('id=16')->value('content');
        return is_numeric($content) && bccomp($content, 0, 2) > 0 ? $content : 0;
    }

    public static function getIsOpen(){
        return (int)Db::name(static::$sTableName)->where('id=22')->value('content');
    }

}