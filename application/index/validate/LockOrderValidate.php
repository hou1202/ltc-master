<?php
namespace app\index\validate;


use think\Db;
use think\Validate;

class LockOrderValidate extends Validate
{

    protected $rule = [
        ['id', 'require|gt:0'],
        ['money|理财金额', 'require|gt:0'],
        ['plan_id|理财计划', 'require|gt:0'],
        ['password|交易密码', 'require|length:6,16'],
    ];

    protected $message = [
    ];

    protected $scene = [
        'detail' => ['item_id'],
        'commit' => ['money','plan_id', 'password'],
    ];



}