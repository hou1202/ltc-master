<?php
namespace app\index\controller;



use app\admin\model\Config;
use app\common\controller\IndexController;

class Question extends IndexController
{

    protected static $sPermissionArr = [
        'index'=>5
    ];

    protected static $sParamsArr =[
    ];

    public function index(){
        $this->assign(['question'=>Config::getQuestion()]);
        return $this->fetch();
    }

}