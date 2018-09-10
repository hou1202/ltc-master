<?php
/**
 * Project: horsesrun
 * User: Zhu Ziqiang
 * Date: 2017/5/15
 * Time: 16:45
 */

namespace app\common\model;


use think\Config;
use think\Db;

class CommonUtils
{

    public static function concatWhere($where, $andWhere){
        if(!empty($where)){
            $where .= ' AND ';
        }
        return $where.$andWhere;
    }

    /**
     * 所有数组的笛卡尔积
     *
     * @param array $data
     */
    public static function combineDika($data) {
        //dump($data);
        $result = [];
        $firstData = array_shift($data);
       //dump($firstData);
        foreach($firstData as $item) {
            $result[] = array($item);
        }
        foreach($data as $k=>$v) {
            $result = static::combineArray($result, $data[$k]);
        }
        return $result;
    }

    /**
     * 两个数组的笛卡尔积
     *
     * @param array $arr1
     * @param array $arr2
     */
    public static function combineArray($arr1,$arr2) {
        $result = [];
        foreach ($arr1 as $item1) {
            foreach ($arr2 as $item2) {
                $temp = $item1;
                $temp[] = $item2;
                $result[] = $temp;
            }
        }
        return $result;
    }

    /**
     * 获取sql  的距离
     * @param $latitude
     * @param $longitude
     * @return string
     */
    public static function getCalItudeStr($latitude, $longitude){
        return '(ACOS(SIN((s.latitude * 3.1415) / 180 ) *SIN((' . $latitude . ' * 3.1415) / 180 ) +COS((s.latitude * 3.1415) / 180 ) * COS((' . $latitude . ' * 3.1415) / 180 ) *COS((s.longitude * 3.1415) / 180 - (' . $longitude . ' * 3.1415) / 180 ) ) * 6380 * 1000) as distance';
    }

    public static function parseTableName($name){
        $prefix = Config::get('database.prefix');
        $name = substr($name, strpos($name, $prefix)+strlen($prefix));
        $name = preg_replace_callback('/_([a-zA-Z])/', function ($match) {
            return strtoupper($match[1]);
        }, $name);
        return ucfirst($name);
    }

    public static function getProvinces(){
        return Db::table('p_area')->field('name,area_id')->where('level=1 AND is_del=0')->select();
    }

    public static function getCitys($provinceId){
        return Db::table('p_area')->field('name,area_id')->where('typeid='.$provinceId.' AND is_del=0')->select();
    }

    public static function getCityName($areaId){
        return Db::table('p_area')->field('name,area_id')->where('area_id='.$areaId)->value('name', '');
    }

    public static function getCity($areaId){
        return Db::table('p_area')->where('area_id='.$areaId)->find();
    }

    public static function saveImage($path) {
        if($path == ''){
            return '';
        }
        $image_name ='uploads/wx_poster/'.md5($path).'.jpg';
        $ch = curl_init ($path);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
        $img = curl_exec ($ch);
        curl_close ($ch);
        $fp = fopen($image_name,'w');
        fwrite($fp, $img);
        fclose($fp);
        return Config::get('upload_file_domain').'/'.$image_name;
    }

    public static function secToTime($times){
        $result = '00:00:00';
        if ($times>0) {
            $hour = floor($times/3600);
            $minute = floor(($times-3600 * $hour)/60);
            $second = floor((($times-3600 * $hour) - 60 * $minute) % 60);
            $hour<10 && $hour = '0'.$hour;
            $minute<10 && $minute = '0'.$minute;
            $second<10 && $second = '0'.$second;
            $result = $hour.':'.$minute.':'.$second;
        }
        return $result;
    }

    public static function flushOrders($userId=0, $sellId=0){
        $where = [];
        if($userId>0){
            $where['user_id'] = $userId;
        }
        if($sellId>0){
            $where['sell_id'] = $sellId;
        }
        $where['status'] = 2;
        $where['ty_time'] = ['<=', date('Y-m-d H:i:s', time()-10800)];
        Db::name('order')->where($where)->update(['status'=>9, 'sx_time'=>date('Y-m-d H:i:s')]);
        $where['status'] = 3;
        $where['dqr_time'] = ['<=', date('Y-m-d H:i:s', time()-10800)];
        Db::name('order')->where($where)->update(['status'=>9, 'sx_time'=>date('Y-m-d H:i:s')]);
    }

}