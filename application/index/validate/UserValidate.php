<?php
namespace app\index\validate;


use app\admin\model\Config;
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
        ['password1|确认密码', 'require|length:6,16|checkPassword1'],
        ['verify|验证码', 'require|checkRegVerify'],
        ['real_name|真实姓名' , 'require|length:1,50'],
        ['hospital_id|医院' , 'require|gt:0|checkHospital'],
        ['department_id|科室' , 'require|gt:0'],
        ['intro|个人简介' , 'length:1,1500'],
        ['profession|专业擅长' , 'length:1,250'],
        ['bank_number|开户账号' , 'require'],
        ['cards' , 'require|checkCards'],
        ['token', 'require'],
        ['trade_password|交易密码', 'require|length:6,16'],
        ['trade_password1|确认交易密码', 'require|length:6,16|checkPassword2'],
        ['invitation_code|邀请码', 'require|checkInvitationCode'],
        ['bank_id|开户行', 'require|gt:0'],
        ['real_name|开户姓名', 'require|length:1,50'],
        ['bank_zh_name|开户支行', 'require|length:1,50'],
        ['alipay_number|支付宝账号', 'length:1,50'],
        ['nick_name|昵称', 'length:1,50'],
    ];

    protected $scene = [
        'reg' => ['mobile'=>'require|checkMobile|checkUnionMobile', 'verify', 'password', 'password1', 'trade_password', 'trade_password1', 'invitation_code'],
        'login' => ['mobile', 'password'=>'require|length:6,16|checkPassword'],
        'islogin' => ['token'],
        'info' => ['token'],
        'setsign' => ['sign'],
        'bill' => ['token'],
        'hxinfo' => ['mobile'],
        'edit' => ['token', 'intro', 'profession', 'bank_number'],
        'editpass' => ['mobile'=>'require|checkMobile|checkExistMobile', 'verify'=>'require|checkEditPasswordVerify', 'password'=>'require|length:6,16', 'password1'],
        'tradepass' => ['mobile'=>'require|checkMobile|checkExistMobile', 'verify'=>'require|checkEditPasswordVerify', 'trade_password'=>'require|length:6,16', 'trade_password1'],
        'updatepass' => ['new_password'=>'require', 'password'=>'require|length:6,16|checkNewPassword'],
        'update' => ['mobile','bank_id','nick_name','real_name','bank_number','alipay_number','verify'=>'require|checkEditPasswordVerify'],
    ];

    protected $message = [
        'mobile.checkMobile'=>'手机号格式不正确',
        'cards.require'=>'请上传医师执业资格证件',
        'cards.checkCards'=>'请上传医师执业资格证件',
        'mobile.checkUnionMobile'=>'该手机号已被注册',
        'verify.checkLoginVerify'=>'验证码不正确',
        'verify.checkRegVerify'=>'验证码不正确',
        'verify.checkEditpassVerify'=>'验证码不正确',
        'verify.checkEditPasswordVerify'=>'验证码不正确',
        'password.checkPassword'=>'手机号或密码不正确',
        'password.checkNewPassword'=>'原密码不正确',
        'password1.checkPassword1'=>'两次密码输入不一致',
        'card_number.checkCardNum'=>'身份证号码不正确',
        'bank_number.checkBankNumber'=>'银行卡号不正确',
        'invitation_code.checkInvitationCode'=>'邀请码不正确'
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

    public function checkPassword1($value, $rule, $data){
        return $data['password']==$value;
    }

    public function checkPassword2($value, $rule, $data){
        return $data['trade_password']==$value;
    }

    public function checkCards($value){
        return !empty($value);
    }

    public function checkInvitationCode($value){
        $default = Config::getInvitationCode();
        if(empty($default) || empty($value)) {
            return false;
        }
        if($value == $default) {
            $this->data['parent_id'] = 0;
            $this->data['parent_ids'] = '';
            return true;
        }

        $user = Db::name('user')->field('user_id,parent_ids')->where('invitation_code like \''.$value.'\'')->find();
        if($user == null) {
            return false;
        }
        $this->data['parent_id'] = $user['user_id'];
        $this->data['parent_ids'] = $user['parent_ids'];
        return true;

    }

}