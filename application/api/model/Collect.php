<?php
// +----------------------------------------------------------------------
// | KingTP
// +----------------------------------------------------------------------
// | 2018/1/19 10:03
// +----------------------------------------------------------------------
// | **基于ThinkPHP 5.0.14 + LayUI2.2.5封装**
// +----------------------------------------------------------------------
// | Author: 晓晓攻城师 <邮箱：1228676735@qq.com><博客：http://blog.csdn.net/qq_26245325>
// +----------------------------------------------------------------------

namespace app\api\model;


use think\Db;

class Collect
{

    public static $sTableName = 'user_collect';

    /**
     * 判断是否收藏
     * @param $userId
     * @param $shopId
     * @return string 0未收藏  1已收藏
     */
    public static function isCollect($userId, $shopId){
        return Db::name(static::$sTableName)->where('user_id='.$userId.' AND shop_id='.$shopId)->count() == 1 ? 1 : 0;
    }


    /**
     * 收藏
     * @param $userId
     * @param $shopId
     * @return bool
     */
    public static function collect($userId, $shopId){
        Db::name(static::$sTableName)->insert(['user_id'=>$userId, 'shop_id'=>$shopId]);
        return true;
    }

    /**
     * 取消收藏
     * @param $userId
     * @param $shopId
     * @return bool
     * @throws \think\Exception
     */
    public static function cancleCollect($userId, $shopId){
        Db::name(static::$sTableName)->where(['user_id'=>$userId, 'shop_id'=>$shopId])->delete();
        return true;
    }

    /**
     * 获取收藏列表
     * @param $userId
     * @param $page
     * @return false|\PDOStatement|string|\think\Collection
     */
    public static function collects($userId, $page){
        return Db::name(static::$sTableName)->alias('c')->field('cast(c.collect_id as char) as collect_id,u.user_id as shop_id,u.real_name,h.hospital_name,d.department_name,u.profession,u.poster,u.tag,u.diagnose_count,u.dayu_id,u.xiaoyu_id')
            ->join('p_user u', 'u.user_id=c.shop_id')
            ->join('p_hospital h', 'h.hospital_id=u.hospital_id')
            ->join('p_hospital_department d', 'd.department_id=u.department_id')
            ->where('c.user_id='.$userId)
            ->limit($page*10, 10)
            ->order('c.collect_id desc')
            ->select();
    }

    /**
     * 获取收藏数量最多的城市
     * @param $userId
     * @return array|false|\PDOStatement|string|\think\Model
     */
    public static function getCollectHospitalId($userId){
        return Db::name(static::$sTableName)->alias('c')->field('u.province_id,u.city_id,count(city_id) as totalCount')
            ->join('p_user u', 'u.user_id=c.shop_id')
            ->where('c.user_id='.$userId)
            ->group('u.city_id')
            ->order('totalCount desc')
            ->find();
    }

}