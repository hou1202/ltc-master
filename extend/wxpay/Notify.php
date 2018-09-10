<?php

namespace wxpay;

use think\Db;
use think\Loader;
use think\Log;

require_once 'lib/WxPayException.php';
Loader::import('wxpay.lib.WxPayApi');
Loader::import('wxpay.lib.WxPayNotify');

/**
 * 异步通知处理类
*
*
* ----------------- 求职 ------------------
* 姓名: zhangchaojie      邮箱: zhangchaojie_php@qq.com  应届生
* 期望职位: PHP初级工程师   地点: 深圳(其他城市亦可)
* 能力:
*     1.熟悉小程序开发, 前后端皆可
*     2.后端, PHP基础知识扎实, 熟悉ThinkPHP5框架, 用TP5做过CMS, 商城, API接口
*     3.MySQL, Linux都在进行进一步学习
*/
class Notify extends \WxPayNotify
{
    /**
     * 此为主函数, 即处理自己业务的函数, 重写后, 框架会自动调用
     *
     * @param array $data 微信传递过来的参数数组
     * @param string $msg 错误信息, 用于记录日志
     */
    public function NotifyProcess($data, &$msg)
    {
        // 一下均为实例代码
        // 1.校检参数
        if(!array_key_exists("transaction_id", $data)){
            $msg = "输入参数不正确";
            return false;
        }

        // 2.微信服务器查询订单，判断订单真实性(可不需要)
       /* if(!$this->Queryorder($data["transaction_id"])){
            $msg = "订单查询失败";
            return false;
        }*/

        // 3.去本地服务器检查订单状态(强烈建议需要)
        $order = $this->getOrder($data);
        if(empty($order)) {
            $msg = '本地订单不存在';
            return false;
        }

        // 4.检查订单状态
        if(!$this->checkOrderStatus($order)) {
            // 如果订单处理过, 则直接返回true
            return true;
        }

        // 订单状态未修改情况下, 进行处理业务
        $result = $this->processOrder($order, $data);
        if(!$result) {
            $msg = '订单处理失败';
            return false;
        }

        return true;
    }

    /**
     * 处理核心业务
     * @param  array $order 订单信息
     * @param  array $data  通知数组
     * @return Bollean
     */
    public function processOrder($order, $data)
    {
        // 进行核心业务处理, 如更新状态, 发送通知等等
        // 处理成功, 返回true, 处理失败, 返回false
        // 例如:
//        $result = Db::name('order')->where('order_id', $order['order_id'])->update(['status'=>1, 'wx_sn'=>$data['transaction_id']]);
//        $goodsIds = Db::name('order_goods')->where('order_id='.$order['order_id'])->column('goods_id');
//        Db::name('goods')->where(['goods_id'=>['in', $goodsIds]])->update(['order_count'=>['exp', 'order_count+1']]);
        $order['wx_sn'] = $data['transaction_id'];
        Db::startTrans();
        try {
            //新增一条记录
            unset($order['order_id']);
            Db::name('order')->insert($order);
            //更新店铺
            $shopUpdate = ['balance' => ['exp', 'balance+' . $order['shop_price']], 'hot_count'=>['exp', 'hot_count+1']];
            //判断是否使用了积分
            if ($order['is_jf'] == 1) {
                //更新用户的积分
                Db::name('user')->where('user_id=' . $order['user_id'])->update(['jf' => ['exp', 'jf-' . $order['jf']]]);
                Db::name('user_jf_log')->insert(['user_id'=>$order['user_id'],'type'=>1, 'log'=>'支付订单'.$order['order_sn'].'使用', 'sign'=>'-', 'count'=>$order['jf'], 'status'=>1]);
                Db::name('shop')->where('shop_id=' . $order['shop_id'])->update($shopUpdate);
            } else {
                //更新用户的待奖励金额
                Db::name('user')->where('user_id=' . $order['user_id'])->update(['djl' => ['exp', 'djl+' . $order['rt_price']]]);
                $shopUpdate['j_total_price'] = ['exp', 'j_total_price+' . $order['jlj_price']];
                Db::name('shop')->where('shop_id=' . $order['shop_id'])->update($shopUpdate);
                $shop = Db::name('shop')->where('shop_id='.$order['shop_id'])->field('j_total_price,j_price')->find();
                $this->updateJLJ($order['shop_id'], bcsub($shop['j_total_price'], $shop['j_price'], 2));
            }
            Db::name('shop_balance_log')->insert(['shop_id'=>$order['shop_id'], 'status'=>1, 'log'=>'用户付款'.$order['price'].',实得'.$order['shop_price'], 'money'=>$order['shop_price']]);
            Db::commit();
        }catch(\Exception $e){
            Db::rollback();
            Log::error($e->getMessage());
        }
        return true;
        //添加商品的的数量
    }

    private function updateJLJ($shopId, $syJLJ){
        //返现给用户
        $order = Db::name('order')->field('order_id,user_id,rt_price,shop_id')->where('shop_id=' .$shopId. ' AND status=0 AND is_jf=0')->order('order_id asc')->find();
        //判断奖金池的金额 是否够发
        if ($order!==null && $order['rt_price']<=$syJLJ){
            Db::name('order')->where('order_id='.$order['order_id'])->update(['status'=>1, 'f_price'=>$order['rt_price'],'u_time'=>date('Y-m-d H:i:s')]);
            Db::name('user')->where('user_id='.$order['user_id'])->update(['djl'=>['exp', 'djl-'.$order['rt_price']], 'yjl'=>['exp','yjl+'.$order['rt_price']], 'balance'=>['exp', 'balance+'.$order['rt_price']]]);
            Db::name('user_balance_log')->insert(['user_id'=>$order['user_id'], 'status'=>1, 'log'=>'奖励金返回'.$order['rt_price'].'元','money'=>$order['rt_price']]);
            Db::name('shop')->where('shop_id='.$order['shop_id'])->update(['j_price'=>['exp', 'j_price+'.$order['rt_price']]]);
            $syJLJ = bcsub($syJLJ, $order['rt_price'], 2);

            return $this->updateJLJ($shopId, $syJLJ);
        }
        return true;
    }


    // 去微信服务器查询是否有此订单
    public function Queryorder($transaction_id)
    {
        $input = new \WxPayOrderQuery();
        $input->SetTransaction_id($transaction_id);
        $result = \WxPayApi::orderQuery($input);
        if(array_key_exists("return_code", $result)
            && array_key_exists("result_code", $result)
            && $result["return_code"] == "SUCCESS"
            && $result["result_code"] == "SUCCESS")
        {
            return true;
        }
        return false;
    }

    // 去本地服务器查询订单信息
    public function getOrder($data)
    {
        // 可根据商户订单号进行查询
        // 例如:
        $order = Db::name('order_log')->where('order_sn', $data['out_trade_no'])->find();
        return $order;
    }

    /**
     * 检查order状态, 是否已经做过修改, 避免重复修改
     * 原因: 可能由于业务处理较慢, 还未等回复微信服务器, 同一订单的另一个通知已到达,
     *      为了避免重复修改订单, 需要对状态进行检查
     *
     * @return Bollean
     */
    public function checkOrderStatus($order)
    {
        // 检查还未修改, 则返回true, 检查已经修改过了, 则返回false
        // 例如:
        return true;
    }

}

