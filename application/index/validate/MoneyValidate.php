<?php
namespace app\index\validate;


use app\common\model\VerifyModel;
use think\Db;
use think\Validate;

class MoneyValidate extends Validate
{

    protected $rule = [
        ['count|数量', 'require|egt:200'],
        ['jyid|交易ID', 'require|length:1,255'],
        ['address|提币地址', 'require|length:1,255'],
        ['payment_id|PaymentID', 'length:0,255'],
        ['verify|验证码', 'require|checkEditPasswordVerify'],
        ['b_id', 'require|gt:0'],
        ['id', 'require|gt:0'],
        ['mobile', 'require'],
    ];

    protected $message = [
        'verify.checkEditPasswordVerify'=>'验证码不正确',
    ];

    protected $scene = [
        'addcb' => ['count', 'jyid'],
        'cancel' => ['id'],
        'addapply' => ['count', 'mobile', 'b_id', 'address', 'payment_id', 'verify'],
    ];

    public function checkEditPasswordVerify($value, $rule, $data){
        return VerifyModel::check($value, VerifyModel::TYPE_EDIT_PASSWD, $data['mobile']);
    }


}