<?php
namespace app\index\validate;


use think\Db;
use think\Validate;

class PatientFileValidate extends Validate
{

    protected $rule = [
        ['patient_name|病人姓名', 'require|length:1,50'],
        ['sex', 'require|in:男,女'],
        ['age|年龄', 'require|number|gt:0|lt:200'],
        ['unit', 'require|in:岁,月,周'],
        ['hospital_name|原送检单位', 'require|length:1,100'],
        ['section_number|原始档案号', 'require|length:1,20'],
        ['mobile|联系电话', 'number|length:1,20'],
        ['section_count|数目', 'require|number|gt:0|lt:1000'],
        ['scan_imgs|切片扫描', 'require'],
        ['medical_history|病史简介', 'require|length:1,250'],
        ['pathology|病理诊断', 'require|length:1,250'],
        ['first_doctor|初诊医生', 'require|length:1,50'],
        ['status', 'in:-1,0,1,2'],
        ['page', 'gt:0'],
        ['hospital_id', 'egt:0'],
        ['search_name|搜索名', 'require|length:1,50'],
        ['file_id', 'require|gt:0'],
        ['time', 'require|gt:0'],
        ['param', 'length:0,1000'],
    ];

    protected $message = [
        'mobile.number'=>'手机号码必须为数字',
    ];

    protected $scene = [
        'save' => ['patient_name', 'sex', 'age', 'unit', 'hospital_name', 'mobile', 'section_number', 'section_count', 'scan_imgs',
            'medical_history', 'pathology', 'first_doctor'],
        'update' => ['file_id', 'hospital_name', 'patient_name', 'sex', 'age', 'unit', 'mobile', 'section_number', 'section_count', 'scan_imgs',
            'medical_history', 'pathology', 'first_doctor'],
        'updatebanner' => ['file_id'],
        'files' => ['status', 'page', 'hospital_id', 'patient_name'=>'length:1,50'],
        'detail' => ['file_id'],
        'del' => ['file_id'],
        'pdf' => ['file_id'],
        'notpushs' => ['time'],
        'index'=>['page', 'patient_name'=>'length:1,50', 'hospital_id', 'status'],
        'completedetail'=>['file_id', 'param'],
        'add'=>['param'],
        'edit'=>['param', 'file_id'],
    ];



}