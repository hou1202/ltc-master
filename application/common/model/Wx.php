<?php
/**
 * Project: 微信相关
 * User: Zhu Ziqiang
 * Date: 2017/11/27
 * Time: 19:59
 */

namespace app\common\model;


use think\Config;
use think\Db;
use think\Loader;
use wxpay\AppPay;

class Wx
{

    /**
     * 公众号 js支付
     * @param $orderSn
     * @param $totalPrice
     * @param $body
     * @param $openId
     * @return array
     */
    public static function gzhPay($orderSn, $totalPrice, $body, $openId){
        Loader::import('wxpay.JsapiPay');
        if(Config::get('app_debug')){$totalPrice=0.01;}
        $params = [
            'body' => $body,
            'out_trade_no' => $orderSn,
            'total_fee' => $totalPrice*100,
            'notify_url' => Config::get('wx_gz_config.notify_url'),
        ];
        return \wxpay\JsapiPay::getParams($params, $openId);
    }

    /**
     * 微信小程序支付
     * @param $orderSn
     * @param $openId
     * @return array
     */
    public static function xcxPay($orderSn, $openId, $totalPrice, $body){
        Loader::import('wxpay.JsapiPay');
        if(Config::get('app_debug')){$totalPrice=0.01;}
        $params = [
            'body' => $body,
            'out_trade_no' => $orderSn,
            'total_fee' => $totalPrice*100,
            'notify_url' => Config::get('wx_config.notify_url'),
        ];
        return \wxpay\JsapiPay::getParams($params, $openId);
    }

    /**
     * APP支付参数
     * @param $totalPrice
     * @param $orderNumber
     * @param string $body
     * @return array
     */
    public static function appPay($totalPrice, $orderNumber, $body=''){
        if(Config::get('app_debug')){$totalPrice=0.01;}
        $params = [
            'body'         => $body,
            'out_trade_no' => $orderNumber,
            'total_fee' => $totalPrice*100
        ];
        return AppPay::getParams($params);
    }


}