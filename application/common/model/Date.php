<?php
/**
 * Created by PhpStorm.
 * User: zhuziqiang
 * Date: 2016/12/9
 * Time: 13:42
 */

namespace app\common\model;


class Date
{

    public static function getToday(){
        return date('Y-m-d');
    }

    public static function getYestoday(){
        return date('Y-m-d',strtotime(date('Y-m-d')."-1 day"));
    }

    public static function  getTomorrow(){
        return date('Y-m-d',strtotime(date('Y-m-d')."+1 day"));
    }

    public static function getMonthFirstDay(){
        return date('Y-m-01');
    }

    public static function getMonth($date)
    {
        $firstday = date("Y-m-01", strtotime($date));
        $lastday = date("Y-m-d", strtotime("$firstday +1 month -1 day"));
        return [$firstday, $lastday];
    }

    public static function getLastMonthDays($date)
    {
        $timestamp = strtotime($date);
        $firstday = date('Y-m-01', strtotime(date('Y', $timestamp) . '-' . (date('m', $timestamp) - 1) . '-01'));
        $lastday = date('Y-m-d', strtotime("$firstday +1 month -1 day"));
        return [$firstday, $lastday];
    }

    public static function getNextMonthDays($date)
    {
        $timestamp = strtotime($date);
        $arr = getdate($timestamp);
        if ($arr['mon'] == 12) {
            $year = $arr['year'] + 1;
            $month = $arr['mon'] - 11;
            $firstday = $year . '-0' . $month . '-01';
            $lastday = date('Y-m-d', strtotime("$firstday +1 month -1 day"));
        } else {
            $firstday = date('Y-m-01', strtotime(date('Y', $timestamp) . '-' . (date('m', $timestamp) + 1) . '-01'));
            $lastday = date('Y-m-d', strtotime("$firstday +1 month -1 day"));
        }
        return [$firstday, $lastday];
    }

    /**
     * 获取当前月的最后一天时间戳
     * @return int
     */
    public static function getCurrentMonthLastDay()
    {
        return strtotime(date('Y-m-01', time()).' +1 month -1 day');
    }

    /**
     * 获取前几个月的第一天
     * @param int $monthAgo
     * @return int
     */
    public static function getMonthAgoFirstDay($monthAgo)
    {
        $arr = getdate(time());
        $year = $arr['year'];
        $month = $arr['mon'] - $monthAgo;
        if($month<0){
            $beishu = $month / 12;
            $month = $month % 12;
            $year = $arr['year'] - $beishu;
            $month = $month + 12;
        }
        return strtotime($year.'-'.$month.'-01');
    }

    public static function monthFormat($time){
        $currentDate = getdate(time());
        $timeDate = getdate($time);
        if($timeDate['year'] == $currentDate['year']){
            return $timeDate['mon'] == $currentDate['mon'] ? '本月' : $timeDate['mon'].'月';
        }
        return $timeDate['year'].'年'.$timeDate['mon'].'月';
    }


}