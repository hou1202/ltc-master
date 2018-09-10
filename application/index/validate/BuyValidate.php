<?php
namespace app\index\validate;


use app\common\model\VerifyModel;
use think\Db;
use think\Validate;

class BuyValidate extends Validate
{

    protected $rule = [
        ['count|数量', 'require|gt:0'],
        ['password|交易密码', 'require|length:6,16'],
        ['verify|验证码', 'require|checkEditPasswordVerify'],
        ['id', 'require|gt:0'],
    ];

    protected $message = [
        'verify.checkEditPasswordVerify'=>'验证码不正确',
    ];

    protected $scene = [
        'commit' => ['count', 'password'],
        'cancel' => ['id'],
        'huikuan' => ['id', 'password'],
        'shoukuan' => ['id', 'password'],
        'cuohe' => ['id'],
    ];

    public function checkEditPasswordVerify($value, $rule, $data){
        return VerifyModel::check($value, VerifyModel::TYPE_EDIT_PASSWD, $data['mobile']);
    }


}