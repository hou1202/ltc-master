<?php
// +----------------------------------------------------------------------
// | KingTP
// +----------------------------------------------------------------------
// | 2018/1/13 14:41
// +----------------------------------------------------------------------
// | **基于ThinkPHP 5.0.14 + LayUI2.2.5封装**
// +----------------------------------------------------------------------
// | Author: 晓晓攻城师 <邮箱：1228676735@qq.com><博客：http://blog.csdn.net/qq_26245325>
// +----------------------------------------------------------------------

namespace app\api\model;


use app\admin\model\OpenArea;
use think\Db;

class Hospital
{

    public static $sTableName = 'hospital';

    /**
     * 获取城市的医院数目巨
     * @param $cityId
     * @return int
     */
    public static function getCount($cityId){
        return (int)Db::name(static::$sTableName)->where('city_id='.$cityId)->count();
    }

    /**
     * 通过医院ID获取医院名
     * @param $hospitalId
     * @return string
     */
    public static function getHospitalNameById($hospitalId){
        return Db::name(static::$sTableName)->where('hospital_id='.$hospitalId)->value('hospital_name', '');
    }

    /**
     * 通过医院ID获取医院
     * @param $hospitalId
     * @return array|null
     */
    public static function getHospital($hospitalId){
        return static::getHospitalByWhereAndField('hospital_id='.$hospitalId);
    }

    /**
     * 获取排序第一的医院
     * @param $where
     * @param bool|string $field
     * @return array|null
     */
    public static function getHospitalByWhereAndField($where, $field=true){
        return Db::name(static::$sTableName)->field($field)->where($where)->order('sort asc')->find();
    }

    /**
     * 通过where查询条件获取医院列表
     * @param array $where
     * @return array
     */
    public static function getHospitals($where=[]){
        $where['is_del'] = 0;
        return Db::name(static::$sTableName)->field('hospital_id,hospital_name')->where($where)->order('sort ASC')->select();
    }

    /**
     * 通过定位区域ID获取医院列表
     * @param $areaId
     * @return array
     */
    public static function getHospitalsByAreaId($areaId){
        $area = OpenArea::get($areaId);
        $hospitals = [];
        if($area != null){
            switch($area['level']){
                case 1:
                    //$hospitals = static::getHospitals(['city_id' => $areaId, 'district_id'=>0]);
                    $hospitals = static::getHospitals(['city_id' => $areaId]);
                    /*if(empty($hospitals)){
                        $hospitals = static::getHospitals(['city_id' => $area['typeid']]);
                    }*/
                    break;
                case 2:
                    $hospitals = static::getHospitals(['district_id' => $areaId]);
                    if(empty($hospitals)){
                        //$hospitals = static::getHospitals(['city_id' => $area['typeid'], 'district_id'=>0]);
                        $hospitals = static::getHospitals(['city_id' => $area['typeid']]);
                        /*if(empty($hospitals)){
                            $hospitals = static::getHospitals(['city_id' => $area['typeid']]);
                        }*/
                    }
                    break;
            }
        }
        return $hospitals;
    }

    /**
     * 通过定位区域ID获取医院
     * @param $areaId
     * @return array|null
     */
    public static function getHospitalByAreaId($areaId){
        $area = OpenArea::get($areaId);
        $hospital = null;
        if($area != null){
            switch($area['level']){
                case 1:
                    $hospital = static::getHospitalByWhereAndField(['city_id' => $areaId, 'district_id'=>0, 'is_del'=>0], 'hospital_id,hospital_name');
                    if(empty($hospital)){
                        $hospital = static::getHospitalByWhereAndField(['city_id' => $area['typeid'], 'is_del'=>0], 'hospital_id,hospital_name');
                    }
                    break;
                case 2:
                    $hospital = static::getHospitalByWhereAndField(['district_id' => $areaId, 'is_del'=>0], 'hospital_id,hospital_name');
                    if(empty($hospital)){
                        $hospital = static::getHospitalByWhereAndField(['city_id' => $area['typeid'], 'district_id'=>0, 'is_del'=>0], 'hospital_id,hospital_name');
                        if(empty($hospitals)){
                            $hospital = static::getHospitalByWhereAndField(['city_id' => $area['typeid'], 'is_del'=>0], 'hospital_id,hospital_name');
                        }
                    }
                    break;
            }
        }
        return $hospital;
    }

    /**
     * 通过城市ID和区、县ID获取医院列表
     * @param $cityId
     * @param $districtId
     * @return false|\PDOStatement|string|\think\Collection
     */
    public static function getHospitalsByCityIdAndDistrictId($cityId, $districtId){
        return static::getHospitals(['city_id' => $cityId, 'district_id'=>$districtId]);
    }

    /**
     * 通过城市ID获取医院列表
     * @param $cityId
     * @return array
     */
    public static function getHospitalsByCityId($cityId){
        return static::getHospitals(['city_id' => $cityId]);
    }

    /**
     * 获取诊断费用
     * @param $hospitalId
     * @return double
     */
    public static function getDiagnosePrice($hospitalId){
        return Db::name(static::$sTableName)->where('hospital_id='.$hospitalId)->value('diagnose_price', '');
    }

    /**
     * 获取带省份id的医院列表
     * @param array $where
     * @return array
     */
    public static function getHospitalsProvinceId(){
        $where['is_del'] = 0;
        return Db::name(static::$sTableName)->field('hospital_id,province_id,hospital_name')->where($where)->order('sort ASC')->select();
    }

}