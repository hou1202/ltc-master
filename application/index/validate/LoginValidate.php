<?php
namespace app\index\validate;


use think\Db;
use think\Validate;

class LoginValidate extends Validate
{

    protected $rule = [
        ['action', 'checkAction'],
    ];

    protected $message = [
    ];

    protected $scene = [
        'index' => ['action'],
    ];

    public function checkAction($value){
        if($value == 'reg' ||$value='login'){
            return true;
        }
        return false;
    }



}