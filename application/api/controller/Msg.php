<?php
// +----------------------------------------------------------------------
// | KingTP
// +----------------------------------------------------------------------
// | 2018/1/17 15:12
// +----------------------------------------------------------------------
// | **基于ThinkPHP 5.0.14 + LayUI2.2.5封装**
// +----------------------------------------------------------------------
// | Author: 晓晓攻城师 <邮箱：1228676735@qq.com><博客：http://blog.csdn.net/qq_26245325>
// +----------------------------------------------------------------------

namespace app\api\controller;


use app\api\model\Push;
use app\common\controller\ApiController;

class Msg extends ApiController
{

    protected static $sPermissionArr = [
        'msgs' => 7,
        'detail' => 7,
        'del' => 7,
        'notreadcount' => 7,
    ];

    protected static $sParamsArr = [
        'msgs' => ['token'=>2, 'page'=>2, 'search_name'=>1],
        'detail' => ['token'=>2, 'msg_id'=>2],
        'del' => ['token'=>2, 'msg_id'=>2],
        'notreadcount' => ['token'=>2],
    ];

    public function msgs(){
        $searchName = isset($this->requestPostData['search_name']) ? $this->requestPostData['search_name'] : '';
        return $this->jsonSuccess('获取成功', Push::getApiList($this->userId, $this->requestPostData['page'], $searchName));
    }

    public function detail(){
        return $this->jsonSuccess('获取成功', Push::getApiDetail($this->requestPostData['msg_id'], $this->userId));
    }

    public function del(){
        Push::del($this->requestPostData['msg_id'], $this->userId);
        return $this->jsonSuccess('删除成功');
    }

    public function notReadCount(){
        return $this->jsonSuccess('获取成功', ['count'=>Push::getNotReadCount($this->userId)]);
    }



}