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


use app\admin\model\NewsCategory;
use app\admin\model\News as NewsModel;
use app\common\controller\ApiController;

class News extends ApiController
{

    protected static $sPermissionArr = [
        'categorys' => 1,
        'newses' => 3,
        'search' => 3,
    ];

    protected static $sParamsArr = [
        'newses' => ['page'=>2, 'category_id'=>2],
        'search' => ['search_name'=>2],
    ];

    public function categorys(){
        return $this->jsonSuccess('获取成功', NewsCategory::getApiCategorys());
    }

    public function newses(){
        return $this->jsonSuccess('获取成功', NewsModel::getApiNews($this->requestPostData['category_id'], $this->requestPostData['page']));
    }

    public function search(){
        return $this->jsonSuccess('获取成功', NewsModel::getNewses(['title'=>['like', '%'.$this->requestPostData['search_name'].'%']], 0, 20));
    }



}