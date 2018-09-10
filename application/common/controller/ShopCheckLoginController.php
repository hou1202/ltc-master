<?php
namespace app\common\controller;

use app\shop\model\ShopEmployee;
use app\shop\model\ShopSystemRole;
use think\Db;
use think\exception\HttpResponseException;
use think\Response;
use think\response\Redirect;
use think\Config as ThinkConfig;
use think\Session;

class ShopCheckLoginController extends AdminController
{


    /**
     * 初始操作
     */
    protected function init(){
        parent::init();
        $this->checkSessionInfo();
        $this->request->isPost() && $this->modelFactory->setManager(['manager_id'=>$this->shopSystemManagerId, 'manager_name'=>Session::get('shopSystemManagerName')]);
        if($this->request->isGet()) {
            $primaryKey = $this->model->getPk();
            $id = (int)$this->request->param($primaryKey, 0);
            $assignData = ['loadUrl' => '/' . $this->request->path(), 'title' => $this->model->getTitle(), 'primaryKey' => $primaryKey];
            if($id>0){
                $assignData['model'] = $this->model->get($id);
                $this->model = $assignData['model'];
            }
            $assignData['imageSize'] = $this->model->getImageSize();
            $assignData['tableName'] = $this->model->getQuery()->getTable();
            $this->assign($assignData);
        }
    }

    public function checkSessionInfo(){
        $systemManagerId = Session::get('shopSystemManagerId');
        if($systemManagerId<=0){

            $returnUrl = ThinkConfig::get('nologin_redirect');
            $response = $this->request->isAjax() || $this->request->isPost() ?
                Response::create(['code' => self::SESSION_CODE, 'msg' => '用户信息失效，请重新登录', ['url'=>$returnUrl]], 'json')
                : new Redirect($returnUrl);
            throw new HttpResponseException($response);
        }

        //检查权限
        $path = $this->request->path();
        
        //dump($path);
        if($path == '/'){
            $path = 'shop/index/index';
        }
        $permissionKey = '/'.$path;
        //$rules = Session::get('rules');
        $rules = Db::table('p_shop_system_node')->column('id', 'href');
        //dump($rules);  dump($permissionKey);die;

        if (!(!empty($rules) && isset($rules[$permissionKey]))) {
            $response = $this->request->isAjax() || $this->request->isPost() ?
                Response::create(['code' => self::FAIL_CODE, 'msg' => '你没有权限'], 'json') :
                new Redirect(ThinkConfig::get('nopermission_redirect'));
            throw new HttpResponseException($response);
        }
        //dump($this);die;
        $this->shopSystemManagerId = $systemManagerId;

        $shopId=Session::get('shopId');
        $this->shopId=$shopId;
    }

}