<?php
namespace app\index\validate;


use think\Db;
use think\Validate;

class ZdValidate extends Validate
{

    protected $rule = [
        ['page', 'gt:0'],
    ];

    protected $message = [
    ];

    protected $scene = [
        'index' => ['page'],
        'zds' => ['page'],
    ];



}