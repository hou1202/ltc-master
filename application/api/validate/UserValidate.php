<?php
namespace app\api\validate;


use app\api\model\Hospital;
use app\api\model\User;
use app\common\model\BankSearch;
use app\common\model\ValidateModel;
use app\common\model\VerifyModel;
use app\common\validate\BaseValidate;
use think\Db;

class UserValidate extends BaseValidate{

    protected $rule = [
        ['mobile|手机号', 'require|checkMobile'],
        ['password|密码', 'require|length:6,16'],
        ['new_password|新密码', 'require|length:6,16'],
        ['verify|验证码', 'require|checkRegVerify'],
        ['real_name|真实姓名' , 'require|length:1,50'],
        ['hospital_id|医院' , 'require|gt:0|checkHospital'],
        ['department_id|科室' , 'require|gt:0'],
        ['intro|个人简介' , 'length:1,250'],
        ['profession|专业擅长' , 'length:1,250'],
        ['bank_number|银行卡号' , 'checkBankNumber'],
        ['token', 'require']
    ];

    protected $scene = [
        'reg' => ['mobile'=>'require|checkMobile|checkUnionMobile', 'verify', 'password', 'hospital_id', 'department_id', 'real_name', 'intro', 'profession', 'bank_number'],
        'login' => ['mobile', 'password'=>'require|length:6,16|checkPassword'],
        'islogin' => ['token'],
        'info' => ['token'],
        'setsign' => ['token'],
        'bill' => ['token'],
        'hxinfo' => ['mobile'],
        'edit' => ['token', 'intro', 'profession', 'bank_number'],
        'editpass' => ['mobile'=>'require|checkMobile|checkExistMobile', 'verify'=>'require|checkEditPasswordVerify', 'password'=>'require|length:6,16'],
        'updatepass' => ['new_password'=>'require', 'password'=>'require|length:6,16|checkNewPassword'],
    ];

    protected $message = [
        'mobile.checkMobile'=>'手机号格式不正确',
        'mobile.checkUnionMobile'=>'该手机号已被注册',
        'verify.checkLoginVerify'=>'验证码不正确',
        'verify.checkRegVerify'=>'验证码不正确',
        'verify.checkEditpassVerify'=>'验证码不正确',
        'verify.checkEditPasswordVerify'=>'验证码不正确',
        'password.checkPassword'=>'手机号或密码不正确',
        'password.checkNewPassword'=>'原密码不正确',
        'card_number.checkCardNum'=>'身份证号码不正确',
        'bank_number.checkBankNumber'=>'银行卡号不正确',
    ];

    public function checkMobile($value){
        return (boolean)preg_match('/^1[23465789]{1}\d{9}$/', $value);
    }

    public function checkUnionMobile($value){
        return User::getUserCountByMobile($value) < 1;
    }

    public function checkExistMobile($value){
        return User::getUserCountByMobile($value)>0;
    }

    public function checkRegVerify($value, $rule, $data){
        return VerifyModel::check($value, VerifyModel::TYPE_REG, $data['mobile']);
    }

    public function checkEditPasswordVerify($value, $rule, $data){
        return VerifyModel::check($value, VerifyModel::TYPE_EDIT_PASSWD, $data['mobile']);
    }

    public function checkPassword($value, $rule, $data){
        //获取用户信息
        $user = User::getUserInfoByMobile($data['mobile']);
        if($user==null){
            return false;
        }
        if($user['password'] == User::makePassword($value)){
            $this->data = $user;
            return true;
        }else {
            $logData = ['user_id'=>$user['user_id'], 'action_type'=>5001, 'log'=>'登录,密码错误'];
            User::log($logData);
            return false;
        }
    }

    public function checkNewPassword($value, $rule, $data){
        //获取用户信息
        $user = User::getUserInfoByUserId($data['user_id']);
        if($user==null){
            return false;
        }
        if($user['password'] == User::makePassword($value)){
            $this->data = $user;
            return true;
        }else {
            $logData = ['user_id'=>$data['user_id'], 'action_type'=>5001, 'log'=>'登录,密码错误'];
            User::log($logData);
            return false;
        }
    }

    public function checkCardNum($value){
        return ValidateModel::checkCardNu($value);
    }

    public function checkHospital($value){
        $hospital = Hospital::getHospital($value);
        if($hospital == null){
            return false;
        }
        $this->data['province_id'] = $hospital['province_id'];
        $this->data['city_id'] = $hospital['city_id'];
        $this->data['district_id'] = $hospital['district_id'];
        return true;
    }

    public function checkBankNumber($value){
        if(!ValidateModel::checkBankCard($value)){
            return false;
        }
        $name = BankSearch::getname($value);
        if($name == null){
            return false;
        }
        $this->data['bank_name'] = $name;
        return true;
    }

}