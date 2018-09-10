<?php
namespace app\common\controller;



use think\exception\HttpResponseException;
use think\Response;
use think\response\Redirect;

class ViewController extends BaseController
{
    const SUCCESS_CODE = 1;
    const FAIL_CODE = 0;
    const ILL_CODE = -1;


    /**
     * 成功返回的json
     * @param $data array
     * @return Response|\think\response\Json|\think\response\Jsonp|\think\response\Redirect|\think\response\View|\think\response\Xml
     */
    public function jsonSuccess($msg, $data=[])
    {
        return $this->json(['code'=>self::SUCCESS_CODE, 'msg'=>$msg, 'data'=>$data]);
    }

    /**
     * 失败返回的json
     * @param $data array
     * @return Response|\think\response\Json|\think\response\Jsonp|\think\response\Redirect|\think\response\View|\think\response\Xml
     */
    public function jsonFail($msg, $data=[])
    {
        return $this->json(['code'=>self::FAIL_CODE, 'msg'=>$msg, 'data'=>$data]);
    }

    public function success($msg, $url){
        return $this->fetch('/success', ['msg'=>$msg, 'url'=>$url]);
    }

    public function fail($msg){
        return $this->fetch('/fail', ['msg'=>$msg]);
    }

    public function redirectError($url, $msg, $tzMsg){
        return $this->fetch('/error', ['msg'=>$msg, 'tzMsg'=>$tzMsg, 'url'=>$url]);
    }

    public function error($url='/index/index/login', $msg='用户信息失效，请重新登录'){
        $response = $this->request->isAjax() || $this->request->isPost() ?
                Response::create(['code' => self::ILL_CODE, 'msg' => $msg, ['url'=>$url]], 'json')
                : new Redirect($url);
        throw new HttpResponseException($response);
    }

}