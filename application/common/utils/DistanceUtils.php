<?php
namespace app\common\utils;

class DistanceUtils
{

    /**
     *计算某个经纬度的周围某段距离的正方形的四个点
     *
     *@param lng float 经度
     *@param lat float 纬度
     *@param distance float 该点所在圆的半径，该圆与此正方形内切，默认值为0.5千米
     *@return array 正方形的四个点的经纬度坐标
     */
    public static function returnSquarePoint($lng, $lat, $distance = 0.5){
        $earthRadius = 6371;
        $dlng =  2 * asin(sin($distance / (2 * $earthRadius)) / cos(deg2rad($lat)));
        $dlng = rad2deg($dlng);
        $dlat = $distance/$earthRadius;
        $dlat = rad2deg($dlat);
        return [$lng-$dlng, $lng+$dlng, $lat-$dlat, $lat+$dlat];
       /* return array(
            'left-top'=>array('lat'=>$lat + $dlat,'lng'=>$lng-$dlng),
            'right-top'=>array('lat'=>$lat + $dlat, 'lng'=>$lng + $dlng),
            'left-bottom'=>array('lat'=>$lat - $dlat, 'lng'=>$lng - $dlng),
            'right-bottom'=>array('lat'=>$lat - $dlat, 'lng'=>$lng + $dlng)
        );*/
    }

    /**
     * @desc 根据两点间的经纬度计算距离
     * @param float $latitude 纬度值
     * @param float $longitude 经度值
     */
    public static function getDistance($latitude1, $longitude1, $latitude2, $longitude2)
    {
        $earth_radius = 6371000; //approximate radius of earth in meters
        $dLat = deg2rad($latitude2 - $latitude1);
        $dLon = deg2rad($longitude2 - $longitude1);
        $a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * sin($dLon/2) * sin($dLon/2);
        $c = 2 * asin(sqrt($a));
        $d = $earth_radius * $c;
        return round($d); //四舍五入
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

}