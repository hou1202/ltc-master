<?php
// +----------------------------------------------------------------------
// | snake
// +----------------------------------------------------------------------
// | Copyright (c) 2016~2022 http://baiyf.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: NickBai <1902822973@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\validate;

use app\admin\model\SystemManager;
use app\common\validate\BaseValidate;
use think\Db;

class SystemManagerValidate extends BaseValidate
{
    /**
     * @var SystemManager
     */
    protected $model;


    protected $rule = [
        ['username|登录名', 'require|alphaNum|length:4,12|unique:system_manager'],
        ['role_id|角色', 'require|integer|gt:0'],
        ['password|密码', 'require|length:6,16'],
        ['password1|密码', 'require|length:6,16'],
        ['password2|密码', 'require|length:6,16|checkPassword2'],
        ['id', 'require|gt:0|checkId'],
    ];

    protected $scene = [
        'add' => ['username', 'role_id', 'password'],
        'del' => ['id'],
        'edit' => ['id', 'username'=>'require|alphaNum|length:4,12|checkUserName', 'password'=>'length:6,16'],
        'login' => ['username'=>'require|alphaNum|length:4,12|checkLogin|checkStatus', 'password'=>'require|length:6,16|checkPassword'],
        'logout' => ['time'=>'number'],
        'resetpwd' => ['password1', 'password2'],
    ];

    protected $message = [
        'password.checkPassword' => '用户名或者密码不正确',
        'username.checkLogin' => '该用户不存在',
        'username.checkStatus' => '该用户已被禁用',
    ];

    public function checkUserName($value, $rule, $data){
        $manager = new SystemManager();
        return $manager->where('username like :username AND id !=:id')->bind(['username'=>$value,'id'=>[$data['id'], \PDO::PARAM_INT]])->count() < 1;
    }

    public function checkId($value){
        $this->model = SystemManager::get($value);
        return $this->model != null;
    }

    public function checkLogin($value){
        $this->model = SystemManager::get(['username'=>$value, 'is_del'=>0]);
        return $this->model != null;
    }

    public function checkStatus(){
        return $this->model->status == 1;
    }

    public function checkPassword($value, $rule, $data){
        return $this->model->password == $this->model->setPasswordAttr($value);
    }

    public function checkPassword2($value, $rule, $data){
        return $value == $data['password1'];
    }


}