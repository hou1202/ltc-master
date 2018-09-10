<?php
namespace app\api\controller;


use app\admin\model\Config;
use app\common\controller\ViewController;
use app\admin\model\News as NewsModel;
use think\Db;

class Page extends ViewController
{

    public function newsDetail(){
        $id = (int)$this->request->param('news_id');
        $news = ['title'=>'新闻已失效', 'content'=>'该条新闻已不存在', 'c_time'=>date('Y.m.d')];
        if($id>0){
            $newsDetail = NewsModel::getNewsDetail($id);
            if($newsDetail!= null){
                $newsDetail['c_time'] = date('Y.m.d', strtotime($newsDetail['c_time']));
                $news = $newsDetail;
            }
        }
        return $this->fetch('', ['news'=>$news]);
    }

    public function aboutUs(){
        $this->assign(['version'=>$this->request->get('version', '1.0')]);
        return $this->fetch();
    }

    public function billRule(){
        //$this->assign(['tel'=>Config::getServiceTel()]);
        $this->assign(['content'=>Config::getAppBillRule()]);
        return $this->fetch();
    }

    public function agreement(){
        $this->assign(['content'=>Config::getAgreementContent()]);
        return $this->fetch();
    }

    public function xiaoyu(){
        return $this->fetch();
    }

}