<?php
namespace app\api\validate;


use think\Db;
use think\Validate;

class DoctorValidate extends Validate
{

    protected $rule = [
        ['city_id', 'require|gt:0'],
        ['department_id', 'require|gt:0'],
        ['page', 'require|egt:0'],
        ['shop_id', 'require|gt:0'],
        ['search_name', 'require|length:1,50'],
    ];

    protected $message = [
    ];

    protected $scene = [
        'param' => ['city_id'],
        'doctors' => ['department_id', 'page'],
        'search' => ['search_name'],
        'detail' => ['shop_id'],
        'collect' => ['shop_id'],
        'cancelcollect' => ['shop_id'],
        'collects' => ['page'],
    ];



}