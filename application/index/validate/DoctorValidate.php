<?php
namespace app\index\validate;


use think\Db;
use think\Validate;

class DoctorValidate extends Validate
{

    protected $rule = [
        ['page', 'gt:0'],
        ['province_id', 'gt:0'],
        ['city_id', 'gt:0'],
        ['hospital_id', 'egt:0'],
        ['department_id', 'egt:0'],
        ['doctor_name', 'length:1,50'],
        ['shop_id', 'require|gt:0'],
    ];

    protected $message = [
        'province_id.gt'=>'请选择省份',
        'city_id.gt'=>'请选择城市',
        'hospital_id.egt'=>'请选择医院',
        'department_id.egt'=>'请选择科室',
    ];

    protected $scene = [
        'index'=>['page', 'province_id', 'city_id', 'hospital_id', 'department_id', 'department_id', 'doctor_name'],
        'doctors'=>['page', 'province_id', 'city_id', 'hospital_id', 'department_id', 'department_id', 'doctor_name'],
        'detail'=>['shop_id', 'page', 'province_id', 'city_id', 'hospital_id', 'department_id', 'department_id', 'doctor_name'],
        'pushdoctors'=>['hospital_id']
    ];



}