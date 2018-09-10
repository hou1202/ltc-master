<?php
namespace app\api\validate;


use think\Db;
use think\Validate;

class OrderValidate extends Validate
{

    protected $rule = [
        ['file_id', 'require|gt:0'],
        ['shop_id', 'require|gt:0'],
        ['page', 'require|egt:0'],
        ['opinion|会诊意见', 'require|length:10,250'],
        ['doctor_sign', 'require|length:1,255'],
        ['search_name', 'require|length:1,50'],
        ['order_type', 'require|in:0,1'],
    ];

    protected $message = [
    ];

    protected $scene = [
        'commitdetail' => ['file_id', 'shop_id'],
        'add' => ['file_id', 'shop_id'],
        'sends' => ['page'],
        'receives' => ['page'],
        'diagnose' => ['doctor_sign', 'opinion', 'file_id'],
        'completes' => ['page'],
        'search' => ['search_name', 'order_type'],
    ];



}