<?php
namespace app\index\validate;


use think\Db;
use think\Validate;

class MsgValidate extends Validate
{

    protected $rule = [
        ['msg_id', 'require|gt:0'],
        ['content|描述信息', 'require|length:10,255'],
        ['images|图片', 'length:0,255'],
    ];

    protected $message = [

    ];

    protected $scene = [
        'commit'=>['content', 'images']
    ];



}