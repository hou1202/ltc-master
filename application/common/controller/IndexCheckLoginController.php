<?php
namespace app\common\controller;

use think\Db;
use think\exception\HttpResponseException;
use think\Response;
use think\response\Redirect;
use think\Config as ThinkConfig;
use think\Session;

class IndexCheckLoginController extends ViewController
{

    public $userId;

    /**
     * 初始操作
     */
    protected function init(){
        parent::init();
        $this->checkSessionInfo();
    }

    public function checkSessionInfo(){
        $userId = Session::get('userId');
        if($userId<=0){
            if($this->request->isAjax() || $this->request->isPost()){
                $returnUrl = ThinkConfig::get('nologin_redirect') . '?successUrl='.urlencode($this->request->post('redirect'));
                $response = Response::create(['code' => self::ILL_CODE, 'msg' => '用户信息失效，请重新登录', 'data' => ['url' => $returnUrl]], 'json');
            }else {
                $returnUrl = ThinkConfig::get('nologin_redirect') . '?successUrl='.urlencode($this->request->url());
                $response = new Redirect($returnUrl);
            }
            throw new HttpResponseException($response);
        }
        $this->userId = $userId;
    }

}