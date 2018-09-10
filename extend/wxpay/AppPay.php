<?php

namespace wxpay;

use think\Db;
use think\Loader;
use WxPayDataBase;

Loader::import('wxpay.lib.WxPayJsPay');

class AppPay extends WxPayBase
{
    /**
     * 获取预支付信息
     *
     * @param array  $params 订单信息
     * @param string $params['body'] 商品简单描述
     * @param string $params['out_trade_no'] 商户订单号, 要保证唯一性
     * @param string $params['total_fee'] 标价金额, 请注意, 单位为分!!!!!
     *
     * @param string $openId 用户身份标识
     *
     * @return array 预支付信息
     */
    public static function getParams($params)
    {
        // 1.校检参数
        $that = new self();
        $that->checkParams($params);

        // 2.组装参数
        $input = $that->getPostData($params);

        // 3.获取预支付信息
        $order = \WxPayApi::unifiedOrder($input);
        // 4.进行结果检验
        $that->checkResult($order);
        $wxPayData = new WxPayDataBase();
        $result = [
            'appid' => \WxPayConfig::APPID,
            'partnerid' => \WxPayConfig::MCHID,
            'prepayid' => $order['prepay_id'],
            'package' => 'Sign=WXPay',
            'noncestr' => md5(time().rand(1000, 9999)),
            'timestamp' => (string)time(),
        ];
        $wxPayData->setValues($result);
        $wxPayData->SetSign();
        return $wxPayData->GetValues();
    }


    // 组装请求参数
    private function getPostData($params)
    {
        $input  = new \WxPayUnifiedOrder();
        //$input->SetOpenid($openId);
        $input->SetTrade_type("APP");
        // $input->SetGoods_tag("test");
        $input->SetBody($params['body']);
        $input->SetTotal_fee($params['total_fee']);
        $input->SetNotify_url(\WxPayConfig::NOTIFY_URL);
        $input->SetOut_trade_no($params['out_trade_no']);
        return $input;
    }
}
