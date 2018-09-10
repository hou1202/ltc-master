<?php
namespace app\api\validate;


use think\Db;
use think\Validate;

class MsgValidate extends Validate
{

    protected $rule = [
        ['msg_id', 'require|gt:0'],
        ['page', 'require|egt:0'],
        ['search_name', 'length:1,50'],
        ['token', 'require'],
    ];

    protected $message = [
    ];

    protected $scene = [
        'msgs' => ['page', 'search_name'],
        'detail' => ['msg_id'],
        'del' => ['msg_id'],
        'notreadcount' => ['token'],
    ];



}