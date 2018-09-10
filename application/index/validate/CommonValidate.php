<?php
namespace app\index\validate;


use think\Db;
use think\Validate;

class CommonValidate extends Validate
{

    protected $rule = [
        ['city_id', 'require|egt:0'],
        ['hospital_id', 'require|gt:0'],
        ['time', 'require|gt:0'],
        ['province_id', 'require|gt:0'],
        ['type', 'require'],
    ];

    protected $message = [
    ];

    protected $scene = [
        'hospitals' => ['city_id'],
        'departments' => ['hospital_id'],
        'location' => ['time'],
        'citys' => ['province_id'],
        'indexhospitals' => ['city_id'],
        'area' => ['type'],
    ];



}