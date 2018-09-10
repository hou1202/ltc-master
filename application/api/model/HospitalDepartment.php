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


use app\common\model\Area;
use think\Db;

class HospitalDepartment
{

    public static $sTableName = 'hospital_department';

    public static function getDepartmentNameById($departmentId){
        return Db::name(static::$sTableName)->where('department_id='.$departmentId)->value('department_name', '');
    }

    public static function getDepartments($where){
        $where['is_del'] = 0;
        return Db::name(static::$sTableName)->field('department_id,department_name')->where($where)->order('sort ASC')->select();
    }

    public static function getDepartmentsByHospitalId($hospitlId){
        return static::getDepartments(['hospital_id'=>$hospitlId]);
    }

}