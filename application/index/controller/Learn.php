<?php
namespace app\index\controller;



use app\admin\model\LearnExchange;
use app\admin\model\NewsCategory;
use app\common\controller\IndexController;

class Learn extends IndexController
{

    protected static $sPermissionArr = [
        'index'=>3,
        'detail'=>3,
    ];

    protected static $sParamsArr = [
        'index'=>['page'=>1],
        'detail'=>['item_id'=>2]
    ];

    public function index(){
        $assign['totalCount'] = LearnExchange::getIndexTotalCount();
        $assign['totalPage'] = ceil($assign['totalCount']/5);
        $assign['page'] = isset($this->requestData['page']) ? ($this->requestData['page']>0 ? ($this->requestData['page']>$assign['totalPage'] ? $assign['totalPage']: $this->requestData['page']) : 1) : 1;
        $assign['learns'] = $assign['totalCount']>0 ? LearnExchange::getIndexLearns($assign['page']) : [];
        $this->assign($assign);
        return $this->fetch();
    }

    public function detail(){
        $learn = LearnExchange::getLearnDetail($this->requestData['item_id']);
        $learn == null && $learn = ['title'=>'学术交流不存在了', 'c_time'=>date('Y-m-d H:i:s'), 'content'=>'未知错误'];
        $this->assign(['learn'=>$learn]);
        return $this->fetch();
    }


}