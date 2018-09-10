<?php
namespace app\api\controller;

use app\admin\model\File;
use app\admin\model\OrderZd;
use app\common\controller\ApiController;
use app\api\model\User as UserModel;
use app\common\model\FileCheck;
use think\Db;

class User extends ApiController
{

    protected static $sPermissionArr = [
        'reg' => 3,
        'login' => 3,
        'islogin' => 7,
        'edit' => 7,
        'editpass' => 3,
        'info' => 7,
        'setsign' => 7,
        'hxinfo' => 3,
        'bill' => 7,
    ];

    protected static $sParamsArr = [
        'reg' => ['mobile'=>2, 'verify'=>2, 'password'=>2, 'real_name'=>2, 'hospital_id'=>2, 'department_id'=>2, 'intro'=>1, 'profession'=>1, 'bank_number'=>1],
        'edit' => ['token'=>2, 'intro'=>1, 'profession'=>1, 'bank_number'=>1, 'no_del_cards'=>1],
        'login' => ['mobile'=>2, 'password'=>2],
        'islogin' => ['token'=>2],
        'editpass' => ['mobile'=>2, 'verify'=>2, 'password'=>2],
        'info' => ['token'=>2],
        'setsign' => ['token'=>2],
        'hxinfo' => ['mobile'=>2],
        'bill' => ['token'=>2],
    ];

    public function reg(){
        //Log::error(json_encode($_FILES));
        //上传图片
        $files = FileCheck::saveFiles(UserModel::$sFiles);
        if($files === false){
            return $this->jsonFail('您没有上传文件');
        }else if(is_string($files)){
            return $this->jsonFail($files);
        }else{
            if(isset($files['files']['poster'])) $this->requestPostData['poster'] = $files['files']['poster'];
            if(isset($files['files']['cards'])){
                $this->requestPostData['cards'] = $files['files']['cards'];
            } else{
                return $this->jsonFail('请上传资格职业证件');
            }
        }
        if(!empty($this->validate->getData()))
            $this->requestPostData = array_merge($this->validate->getData(), $this->requestPostData);
        $userId = UserModel::reg($this->requestPostData);
        if($userId>0){
            //更新图片路径
            if(isset($files['all_files'])){
                File::saveAllFiles($files['all_files'], 'p_user', $userId);
            }
            return $this->jsonSuccess('注册成功', UserModel::format(UserModel::getUserInfoByUserId($userId)));
        }else{
            return $this->jsonFail('注册失败');
        }
    }

    public function login(){

        $user = $this->validate->getData();
        UserModel::log(['user_id'=>$user['user_id'], 'action_type'=>1, 'log'=>'登录', 'important_info'=>json_encode($user)]);
        return $this->jsonSuccess('获取成功', UserModel::format($user));
    }

    public function isLogin(){
        return $this->jsonSuccess('信息有效');
    }

    public function edit(){
        //上传图片
        $files = FileCheck::saveFiles(UserModel::$sFiles);
        if($files === false){

        }else if(is_string($files)){
            return $this->jsonFail($files);
        }else{
            if(isset($files['files']['poster'])) $this->requestPostData['poster'] = $files['files']['poster'];
            if(isset($files['files']['cards'])){
                $this->requestPostData['cards'] = $files['files']['cards'];
            }
        }
        //重组cards_url
        if(isset($this->requestPostData['no_del_cards'])){
            !empty($this->requestPostData['no_del_cards']) && ($this->requestPostData['cards'] = isset($this->requestPostData['cards']) ? $this->requestPostData['cards'].','.$this->requestPostData['no_del_cards'] : $this->requestPostData['no_del_cards']);
            unset($this->requestPostData['no_del_cards']);
        }
        if(!empty($this->validate->getData()))
            $this->requestPostData = array_merge($this->validate->getData(), $this->requestPostData);
        foreach($this->requestPostData as $k=>$v){
            if($v === '')
                unset($this->requestPostData[$k]);
        }
        if(empty($this->requestPostData)){
            return $this->jsonFail('请输入您要修改的资料');
        }else{
            UserModel::edit($this->userId, $this->requestPostData);
            if(isset($files['all_files'])){
                File::saveAllFiles($files['all_files'], 'p_user', $this->userId);
            }
        }
        return $this->jsonSuccess('修改成功', UserModel::format(UserModel::getUserInfoByUserId($this->userId), false));
    }

    public function editPass(){
        UserModel::editPass($this->requestPostData['mobile'], $this->requestPostData['password']);
        return $this->jsonSuccess('修改成功');
    }

    public function info(){
        return $this->jsonSuccess('获取成功', UserModel::format(UserModel::getUserInfoByUserId($this->userId), false));
    }

    public function setSign(){
        //判断是否设置过签名
        if(UserModel::getSignByUserId($this->userId) != ''){
            return $this->jsonFail('您已经设置过签名了！');
        }
        $files = FileCheck::saveFiles(['dz_sign'=>['count'=>1, 'type'=>0, 'size'=>1048576]]);
        if($files === false){
            return $this->jsonFail('请上传签名');
        }else if(is_string($files)){
            return $this->jsonFail($files);
        }else{
            if(isset($files['files']['dz_sign'])){
                $this->requestPostData['dz_sign'] = $files['files']['dz_sign'];
            }else{
                return $this->jsonFail('请上传签名');
            }
        }
        UserModel::edit($this->userId, $this->requestPostData);
        if(isset($files['all_files'])){
            File::saveAllFiles($files['all_files'], 'p_user', $this->userId);
        }
        return $this->jsonSuccess('设置签名成功', UserModel::format(UserModel::getUserInfoByUserId($this->userId), false));
    }

    public function hxInfo(){
        return $this->jsonSuccess('Success', UserModel::getHxInfo($this->requestPostData['mobile']));
    }

    public function bill(){
        return $this->jsonSuccess('获取成功', ['money'=>OrderZd::getUserTotalPrice($this->userId), 'bills'=>OrderZd::getUserBills($this->userId)]);
    }

}
