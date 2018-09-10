<?php
namespace app\index\controller;



use app\admin\model\Config;
use app\admin\model\OrderZd;
use app\common\controller\IndexController;

class Zd extends IndexController
{

    protected static $sPermissionArr = [
        'index'=>13,
        'zds'=>7,
    ];

    protected static $sParamsArr = [
        'index'=>['page'=>1],
        'zds'=>['page'=>1],
    ];


    public function index(){
        $assign['page'] = (int)$this->getRequestData('page', 1);
        $assign['totalPrice'] = OrderZd::getUserTotalPrice($this->userId);
        $assign['jiesuan'] = Config::getWebBillRule();
        $this->assign($assign);
        return $this->fetch();
    }

    public function zds(){
        $page = $this->getRequestData('page', 1);
        $totalCount = OrderZd::getIndexCount($this->userId);
        $totalPage = ceil($totalCount/6);
        $page = $page>0 ? ($page>$totalPage ? $totalPage : $page) : 1;
        $files = $totalCount>0 ? OrderZd::getIndexUserBills($this->userId, $page) : [];
        return $this->jsonSuccess('获取成功', ['files'=>$files, 'pages'=>['totalPage'=>$totalPage, 'totalCount'=>$totalCount, 'page'=>$page]]);
    }



}