<?php
namespace app\common\controller;

use think\Db;
use think\exception\HttpResponseException;
use think\Response;

class WxCheckLoginController extends ViewController
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
        //验证session
        $userId = 0;
        $session = $this->request->post('session');
        if($session!=null){
            $userId = Db::name('user_session')->where('session like \''.$session.'\'')->value('user_id', 0);
        }
        if($userId<=0){
            $response = Response::create(['code' => self::ILL_CODE, 'msg' => '请重新登录'], 'json');
            throw new HttpResponseException($response);
        }
        $this->userId = $userId;
    }

}