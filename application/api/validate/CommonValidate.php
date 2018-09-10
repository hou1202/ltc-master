<?php
namespace app\api\validate;


use think\Db;
use think\Validate;

class CommonValidate extends Validate
{

    protected $rule = [
        ['city_id', 'require|egt:0'],
        ['hospital_id', 'require|gt:0'],
    ];

    protected $message = [
    ];

    protected $scene = [
        'hospitals' => ['city_id'],
        'departments' => ['hospital_id'],
    ];



}