<?php
namespace app\index\controller;



use app\common\controller\IndexController;
use think\Db;

class News extends IndexController
{

    protected static $sPermissionArr = [
        'index'=>1,
        'detail'=>1,
    ];

    protected static $sParamsArr = [
        'category'=>['category_id'=>1, 'page'=>1],
        'detail'=>['news_id'=>2]
    ];

    public function index(){
        $assign['newses'] = Db::name('news')->field('news_id,title,date_format(c_time,\'%Y-%m-%d\') as c_time')
            ->where(['is_del'=>0])
            ->order('is_zd desc,news_id desc')
            ->select();
        $this->assign($assign);
        return $this->fetch();
    }

    public function detail(){
        $news = \app\admin\model\News::getNewsDetail($this->request->param('id'));
        if($news == null){
            abort(404);
        }
        $this->assign(['news'=>$news]);
        return $this->fetch();
    }


}