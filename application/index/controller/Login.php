<?php
namespace app\index\controller;



use app\admin\model\Config;
use app\common\controller\IndexController;
use think\exception\HttpResponseException;
use think\response\Redirect;
use think\Session;

class Login extends IndexController
{

    protected static $sPermissionArr = [
        'index'=>3
    ];

    protected static $sParamsArr = [
        'index'=>['action'=>1]
    ];

    public function index(){
        $userId = (int)Session::get('userId');
        if($userId>0){
            $response = new Redirect('/index/index/index');
            throw new HttpResponseException($response);
        }
        $this->assign(Config::getAndroidAndIosQrcode());
        $this->assign(['action'=>isset($this->requestData['action']) ? $this->requestData['action'] : 'login']);
        return $this->fetch('/login');
    }


}