<?php
// +----------------------------------------------------------------------
// | KingTP
// +----------------------------------------------------------------------
// | 2018/1/13 14:50
// +----------------------------------------------------------------------
// | **基于ThinkPHP 5.0.14 + LayUI2.2.5封装**
// +----------------------------------------------------------------------
// | Author: 晓晓攻城师 <邮箱：1228676735@qq.com><博客：http://blog.csdn.net/qq_26245325>
// +----------------------------------------------------------------------

namespace app\common\model;


use think\Db;

class Area
{

    public static $sTableName = 'area';

    /**
     * 根据地区ID获取地区
     * @param $areaId int
     * @return array|null
     */
    public static function getAreaById($areaId){
        return Db::name(static::$sTableName)->field('area_id,name,typeid,level')->where('area_id=:area_id')->bind(['area_id'=>$areaId])->find();
    }

    /**
     * 获取省份
     * @return array
     */
    public static function getProvinces(){
        return Db::name(static::$sTableName)->field('area_id,name')->where('level=1')->select();
    }

    /**
     * 获取城市
     * @param $provinceId int
     * @return array|null
     */
    public static function getCitys($provinceId){
        return Db::name(static::$sTableName)->field('area_id,name')->where('typeid='.$provinceId.' AND level=2')->select();
    }

    /**
     * 获取城市
     * @param $provinceId int
     * @return array|null
     */
    public static function getDistricts($cityId){
        return Db::name(static::$sTableName)->field('area_id,name')->where('typeid='.$cityId.' AND level=3')->select();
    }
}