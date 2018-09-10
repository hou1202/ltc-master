<?php
namespace app\admin\controller;



use app\common\controller\AdminController;
use think\Response;
use think\exception\HttpResponseException;
use think\response\Redirect;
use think\Session;

class Login extends AdminController
{
    public static $sModelClass = 'SystemManager';

    public function index(){
        $userId = (int)Session::get('systemManagerId');
        if($userId>0){
            $response = new Redirect('/admin/index/index');
            throw new HttpResponseException($response);
        }
        return $this->fetch('/login',['backImg'=>(date('z')%3)]);
    }

    public function login(){
        Session::set('systemManagerId', $this->model->id);
        Session::set('systemManagerName', $this->model->real_name);
        Session::set('systemManagerPoster', empty($this->model->poster) ? '/logo.png': $this->model->poster);
        $this->model->id;
        Session::set('rules', $this->model->getRoles());
        //更新管理员
        $this->model->last_login_ip = $this->request->ip();
        $this->model->last_login_time = date('Y-m-d H:i:s');
        $this->model->loginnum++;
        $this->model->save();
        return $this->jsonSuccess('登录成功,页面跳转中...',['url'=>'/admin/index/index']);
    }

    public function logout(){
        Session::delete('systemManagerId', null);
        Session::delete('systemManagerName', null);
        Session::delete('systemManagerPoster', null);
        Session::delete('rules', null);
        return $this->jsonSuccess('退出登录成功',['url'=>'/admin/login/index']);
    }

}