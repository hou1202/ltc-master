<?php
namespace app\api\validate;


use think\Db;
use think\Validate;

class NewsValidate extends Validate
{

    protected $rule = [
        ['page', 'require|egt:0'],
        ['category_id', 'require|egt:0'],
        ['search_name', 'require|length:1,50'],
    ];

    protected $message = [
    ];

    protected $scene = [
        'newses' => ['page', 'category_id'],
        'search' => ['search_name'],
    ];



}