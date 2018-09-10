<?php
namespace app\api\validate;


use app\admin\model\PatientFile;
use think\Db;
use think\Validate;

class PatientFileValidate extends Validate
{

    protected $rule = [
        ['hospital_name|原送检单位', 'require|length:1,100'],
        ['patient_name|病人姓名', 'require|length:1,50'],
        ['sex|性别', 'require|in:男,女'],
        ['age|年龄', 'require|number|gt:0|lt:200'],
        ['unit|单位', 'require|in:岁,月,周'],
        ['section_number|原始档案号', 'require|length:1,20'],
        ['mobile|手机号', 'number|length:1,20'],
        ['section_count|数目', 'require|gt:0|lt:1000'],
        ['medical_history|简单病史', 'require|length:1,250'],
        ['pathology|最初病理', 'require|length:1,250'],
        ['first_doctor|初诊医生', 'require|length:1,50'],
        ['status', 'require|in:-1,0,1'],
        ['page', 'require|egt:0'],
        ['search_name|搜索名', 'require|length:1,50'],
        ['file_id', 'require|gt:0'],
    ];

    protected $message = [
        'file_id.checkStatus'=>'该档案已经被修改',
        'mobile.number'=>'手机号码必须为数字',
    ];

    protected $scene = [
        'add' => ['hospital_name', 'patient_name', 'sex', 'age', 'unit', 'mobile', 'section_number', 'section_count',
            'medical_history', 'pathology', 'first_doctor'],
        'files' => ['status', 'page'],
        'search' => ['search_name'],
        'detail' => ['file_id'],
        'pushdetail' => ['file_id'],
        'del' => ['file_id'=>'require|gt:0|checkStatus'],
    ];

    public function checkStatus($fileId){
        $status = PatientFile::getStatusByFileId($fileId);
       if($status===null || $status != PatientFile::STATUS_NOT_PUSH){
           return false;
       }
        return true;
    }



}