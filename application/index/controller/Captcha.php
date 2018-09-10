<?php
namespace app\index\controller;


use app\common\controller\IndexController;
use think\captcha\Captcha as ThinkCaptcha;

class Captcha extends IndexController
{

    protected static $sPermissionArr = [
        'index'=>1,
    ];

    protected static $sParamsArr = [
    ];

    public function index(){
        $config =    [
            'fontSize'    =>    16,
            'length'      =>    4,
            'useNoise'    =>    false,
            'useCurve'    =>    false,
            'fontttf'=>'1.ttf',
            'imageW'=>108
        ];
        $captcha = new ThinkCaptcha($config);
        return $captcha->entry();
    }

}