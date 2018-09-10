<?php
namespace app\index\validate;


use think\Db;
use think\Validate;

class NewsValidate extends Validate
{

    protected $rule = [
        ['category_id', 'egt:0'],
        ['page', 'egt:0'],
        ['news_id', 'require|gt:0'],
    ];

    protected $message = [
    ];

    protected $scene = [
        'category' => ['page', 'category_id'],
        'detail' => ['news_id'],
    ];



}