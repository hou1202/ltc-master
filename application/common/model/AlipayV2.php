<?php
/**
 * Project: 基础库-支付宝 V2.0
 * User: Zhu Ziqiang
 * Date: 2017/10/12
 * Time: 15:53
 */

namespace app\common\model;


use think\Config;
use think\Loader;

class AlipayV2
{

    /**
     * 统一退款接口
     * @return string
     * @throws \Exception
     */
    public static function tuikuan(){
        $alipayPath = EXTEND_PATH.'alipay'.DS.'aop'.DS;
        Loader::import('AopClient', $alipayPath);
        Loader::import('AlipayTradeRefundRequest', $alipayPath.'request'.DS);
        $aop = new \AopClient ();
        $aop->gatewayUrl = 'https://openapi.alipay.com/gateway.do';
        $aliConfig = Config::get('alipay_config');
        $aop->appId = '2017092808974696';
        $aop->rsaPrivateKey = 'MIICXQIBAAKBgQDqEOILRHyJsRvZO837C8MLpIuzU35F4NUx5w/UkPWKsCOqqZbpqXHJzkXAEZg8ElXtTJATLrzCOXuEw5Rr2NZIkkh7VYOkZnbIy5mqwd2Vl9E9J1J6DuPbMk3znsq7vU1YpkCILIEjjJAVd9wqYsjvb34tp6NlUU5m7WOPeaSZ6wIDAQABAoGAZ05ORgTTJn5puSYhEkUtr6zPD7WxDKxfzCecIAhepvh4tXEmLzjfBN+qf0wEsbayAAsDp8PAAcUXFBCyKCtK32K90pxZ30IUE7Hk9XpM5dS0YMDloTLESlKiYhDEN8YYN0riIb7A2anXnr8oYqxdCXkAevKFPu1QRTLpTMyLEJkCQQD4q5yKCoEnmkSggaJxeaeIhLFLeC7PqG8WNPurtn9kaiNgsiBVVAScKbwmjiNnzF7cZ4rPqqGzV045AlGJaAiPAkEA8PcSO3Q4Y8hZGig5vdvKbmeEvZbsW4gX2pkHW1yXqeVhwx085iejg1d71h9pzpqgeDyLC3HDjNZ6W/EFPmDu5QJBANn8RgtUTgfTWhmByk7DIDOybmEEB7UNp+PFqmDKaD40NLMNMv7Z2fizNTZvH2ZcZ0O6mJqWr40xGWcsOyHCys0CQQCkT8YF9qUxHX/svztIhKSQDlTMtypq6+1gKXOD0Cq3NmwokTpisurj9/bAtuD+eiAsfRRPdH7k/aeoJDzwIUclAkAFplCoJWY0S05YTp06sBPwFB6q+HZqUMLkN7cSE6LlUpX9U3/+RW8oTyDsZtuVlITPiRsT4tFuKB7C/BBbANLM';
        $aop->format = "json";
        $aop->charset = "UTF-8";
        $aop->signType = "RSA";
        $aop->alipayrsaPublicKey = 'MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDDI6d306Q8fIfCOaTXyiUeJHkrIvYISRcc73s3vF1ZT7XN8RNPwJxo8pWaJMmvyTn9N4HQ632qJBVHf8sxHi/fEsraprwCtzvzQETrNRwVxLO5jVmRGi60j8Ue1efIlzPXV9je9mkjzOmdssymZkh2QhUrCmZYI/FCEa3/cNMW0QIDAQAB';
        $aop->format='json';
        $request = new \AlipayTradeRefundRequest ();
        $request->setBizContent("{" .
            "\"out_trade_no\":\"2017101316330224525\"," .
            "\"trade_no\":\"2017101321001004930235169572\"," .
            "\"refund_amount\":0.01," .
            "\"refund_reason\":\"正常退款\"," .
            "\"out_request_no\":\"HZ01RF001\"," .
            "\"operator_id\":\"OP001\"," .
            "\"store_id\":\"NJ_S_001\"," .
            "\"terminal_id\":\"NJ_T_001\"" .
            "  }");
        $result = $aop->execute ( $request);

        $responseNode = str_replace(".", "_", $request->getApiMethodName()) . "_response";
        $resultCode = $result->$responseNode->code;
        if(!empty($resultCode)&&$resultCode == 10000){
            return "成功";
        } else {
            return "失败";
        }
    }

    /**
     * APP支付 获取参数接口
     * @param $totalAmount
     * @param $orderNumber
     * @param $msg
     * @return array
     */
    public static function get($totalAmount, $orderNumber, $msg){
        $alipayPath = EXTEND_PATH.'alipay'.DS.'aop'.DS;
        Loader::import('AopClient', $alipayPath);
        Loader::import('AlipayTradeAppPayRequest', $alipayPath.'request'.DS);
        if(Config::get('app_debug')){
            $totalAmount = 0.01;
        }
        $aliConfig = Config::get('alipay_config');
        $urlConfig = Config::get('alipay_url');
        $aop = new \AopClient();
        $aop->gatewayUrl = "https://openapi.alipay.com/gateway.do";
        $aop->appId = $aliConfig['app_id'];
        $aop->rsaPrivateKey = 'MIICXQIBAAKBgQDqEOILRHyJsRvZO837C8MLpIuzU35F4NUx5w/UkPWKsCOqqZbpqXHJzkXAEZg8ElXtTJATLrzCOXuEw5Rr2NZIkkh7VYOkZnbIy5mqwd2Vl9E9J1J6DuPbMk3znsq7vU1YpkCILIEjjJAVd9wqYsjvb34tp6NlUU5m7WOPeaSZ6wIDAQABAoGAZ05ORgTTJn5puSYhEkUtr6zPD7WxDKxfzCecIAhepvh4tXEmLzjfBN+qf0wEsbayAAsDp8PAAcUXFBCyKCtK32K90pxZ30IUE7Hk9XpM5dS0YMDloTLESlKiYhDEN8YYN0riIb7A2anXnr8oYqxdCXkAevKFPu1QRTLpTMyLEJkCQQD4q5yKCoEnmkSggaJxeaeIhLFLeC7PqG8WNPurtn9kaiNgsiBVVAScKbwmjiNnzF7cZ4rPqqGzV045AlGJaAiPAkEA8PcSO3Q4Y8hZGig5vdvKbmeEvZbsW4gX2pkHW1yXqeVhwx085iejg1d71h9pzpqgeDyLC3HDjNZ6W/EFPmDu5QJBANn8RgtUTgfTWhmByk7DIDOybmEEB7UNp+PFqmDKaD40NLMNMv7Z2fizNTZvH2ZcZ0O6mJqWr40xGWcsOyHCys0CQQCkT8YF9qUxHX/svztIhKSQDlTMtypq6+1gKXOD0Cq3NmwokTpisurj9/bAtuD+eiAsfRRPdH7k/aeoJDzwIUclAkAFplCoJWY0S05YTp06sBPwFB6q+HZqUMLkN7cSE6LlUpX9U3/+RW8oTyDsZtuVlITPiRsT4tFuKB7C/BBbANLM';
        $aop->format = "json";
        $aop->charset = "UTF-8";
        $aop->signType = "RSA";
        $aop->alipayrsaPublicKey = 'MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDDI6d306Q8fIfCOaTXyiUeJHkrIvYISRcc73s3vF1ZT7XN8RNPwJxo8pWaJMmvyTn9N4HQ632qJBVHf8sxHi/fEsraprwCtzvzQETrNRwVxLO5jVmRGi60j8Ue1efIlzPXV9je9mkjzOmdssymZkh2QhUrCmZYI/FCEa3/cNMW0QIDAQAB';
//实例化具体API对应的request类,类名称和接口名称对应,当前调用接口名称：alipay.trade.app.pay
        $request = new \AlipayTradeAppPayRequest();
//SDK已经封装掉了公共参数，这里只需要传入业务参数
        $bizcontent = "{\"body\":\"".$msg."\","
            . "\"subject\": \"".$msg."\","
            . "\"out_trade_no\": \"".$orderNumber."\","
            . "\"timeout_express\": \"30m\","
            . "\"total_amount\": \"".$totalAmount."\","
            . "\"product_code\":\"QUICK_MSECURITY_PAY\""
            . "}";
        $request->setNotifyUrl($urlConfig['notify_url']);
        $request->setBizContent($bizcontent);
//这里和普通的接口调用不同，使用的是sdkExecute
         $response = $aop->sdkExecute($request);

//htmlspecialchars是为了输出到页面时防止被浏览器将关键参数html转义，实际打印到日志以及http传输不会有这个问题
        return ['java'=>$response];//就是orderString 可以直接给客户端请求，无需再做处理。
    }

    /**
     * rsaCheck
     * @return bool
     */
    public static function rsaCheck(){
        $alipayPath = EXTEND_PATH.'alipay'.DS.'aop'.DS;
        Loader::import('AopClient', $alipayPath);
        $aop = new \AopClient();
        $aop->alipayrsaPublicKey = 'MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDDI6d306Q8fIfCOaTXyiUeJHkrIvYISRcc73s3vF1ZT7XN8RNPwJxo8pWaJMmvyTn9N4HQ632qJBVHf8sxHi/fEsraprwCtzvzQETrNRwVxLO5jVmRGi60j8Ue1efIlzPXV9je9mkjzOmdssymZkh2QhUrCmZYI/FCEa3/cNMW0QIDAQAB';
        return $aop->rsaCheckV1($_POST, NUll);
    }
}