<?php
/**
 * Project: translator_shop
 * User: Zhu Ziqiang
 * Date: 2017/11/24
 * Time: 13:59
 */

namespace app\common\model;

use think\Config;
use think\Db;

include('../extend/zmxy/zmop/ZmopClient.php');
include('../extend/zmxy/zmop/request/ZhimaAuthInfoAuthorizeRequest.php');
include('../extend/zmxy/zmop/request/ZhimaCreditScoreGetRequest.php');
class Zmxy {
    //芝麻信用网关地址
    public $gatewayUrl = "https://zmopenapi.zmxy.com.cn/openapi.do";
    //商户私钥文件
    public $privateKeyFile;
    //芝麻公钥文件
    public $zmPublicKeyFile;
    //数据编码格式
    public $charset = "UTF-8";
    //芝麻分配给商户的 appId
    public $appId;

    public function __construct()
    {
        $zmxy = Config::get('zmxy');
        $this->privateKeyFile = $zmxy['private_key'];
        $this->zmPublicKeyFile = $zmxy['public_key'];
        $this->appId = $zmxy['app_id'];
    }

    /**
     * 授权
     * @param $name
     * @param $identify
     * @return string
     */
    public function authInfo($name, $identify, $state){
        $client = new \ZmopClient($this->gatewayUrl,$this->appId,$this->charset,$this->privateKeyFile,$this->zmPublicKeyFile);
        $request = new \ZhimaAuthInfoAuthorizeRequest();
        $request->setChannel("apppc");
        $request->setPlatform("zmop");
        $request->setIdentityType("2");// 必要参数
        $request->setIdentityParam("{\"name\":\"".$name."\",\"certType\":\"IDENTITY_CARD\",\"certNo\":\"".$identify."\"}");// 必要参数
        $request->setBizParams("{\"auth_code\":\"M_H5\",\"channelType\":\"app\",\"state\":\"".$state."\"}");//
        $url = $client->generatePageRedirectInvokeUrl($request);
        return $url;
    }

    /**
     * 获取openid
     * @param $params
     * @param $sign
     * @return null|array
     * @throws \Exception
     */
    public function getOpenId($params, $sign){
        // 判断串中是否有%，有则需要decode
        $params = strstr ( $params, '%' ) ? urldecode ( $params ) : $params;
        $sign = strstr ( $sign, '%' ) ? urldecode ( $sign ) : $sign;

        $client = new \ZmopClient ( $this->gatewayUrl, $this->appId, $this->charset, $this->privateKeyFile, $this->zmPublicKeyFile );
        $result = $client->decryptAndVerifySign ($params,$sign );
        $result = urldecode($result);
        $resultArr = explode('&', $result);
        $res = [];
        foreach($resultArr as $v){
            $i = explode('=', $v);
            isset($i[0]) && isset($i[1]) && $res[$i[0]] = $i[1];
        }
        if(isset($res['error_code']) && $res['error_code'] == 'SUCCESS'){
            return $res;
        }
        return null;
    }

    /**
     * 获取芝麻信用分
     * @param $openId
     * @param $transactionId
     * @throws \Exception
     */
    public function zmxyScore($openId, $transactionId){
        $client = new \ZmopClient($this->gatewayUrl,$this->appId,$this->charset,$this->privateKeyFile,$this->zmPublicKeyFile);
        $request = new \ZhimaCreditScoreGetRequest();
        $request->setChannel("apppc");
        $request->setPlatform("zmop");
        $request->setTransactionId($transactionId);// 必要参数
        $request->setProductCode("w1010100100000000001");// 必要参数
        $request->setOpenId($openId);// 必要参数
        $response = $client->execute($request);
        if($response->success == true){
            return ['score'=>$response->zm_score, 'biz_no'=>$response->biz_no];
        }
        return null;
    }

}