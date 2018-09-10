<?php
namespace app\index\validate;


use think\Db;
use think\Validate;

class ServiceValidate extends Validate
{

    protected $rule = [
        ['item_id', 'require|gt:0'],
    ];

    protected $message = [
    ];

    protected $scene = [
        'detail' => ['item_id'],
    ];



}