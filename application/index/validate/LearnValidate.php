<?php
namespace app\index\validate;


use think\Db;
use think\Validate;

class LearnValidate extends Validate
{

    protected $rule = [
        ['page', 'egt:0'],
        ['item_id', 'require|gt:0'],
    ];

    protected $message = [
    ];

    protected $scene = [
        'index' => ['page'],
        'detail' => ['item_id'],
    ];



}