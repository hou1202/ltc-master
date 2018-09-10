<?php
namespace app\index\controller;



use app\admin\model\ServiceItem;
use app\common\controller\IndexController;

class Service extends IndexController
{

    protected static $sPermissionArr = [
        'detail'=>3,
    ];

    protected static $sParamsArr = [
        'detail'=>['item_id'=>2]
    ];

    public function detail(){
        $service = ServiceItem::getServiceItemDetail($this->requestData['item_id']);
        $service == null && $service = ['title'=>'该服务项目不存在了', 'c_time'=>date('Y-m-d H:i:s'), 'content'=>'未知错误'];
        $this->assign(['service'=>$service]);
        return $this->fetch();
    }


}