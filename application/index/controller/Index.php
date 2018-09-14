<?php
namespace app\index\controller;



use app\admin\model\Config;
use app\api\model\Hospital;
use app\admin\model\IndexBanner;
use app\admin\model\LearnExchange;
use app\admin\model\News;
use app\admin\model\OpenArea;
use app\admin\model\ServiceItem;
use app\common\controller\IndexController;
use think\Db;
use think\Session;

class Index extends IndexController
{

    protected static $sPermissionArr = [
        'index'=>5,
        'register'=>1,
        'login'=>1,
        'about'=>1,
        'ios'=>1,
        'android'=>1,
        'xieyi'=>1,
    ];

    protected static $sParamsArr =[
    ];

    public function register()
    {
        $this->assign(['invitationCode'=>Config::getInvitationCode()]);
        $this->assign('regCode', $this->request->get('invitation_code', ''));
        return $this->fetch('/register');
    }

    public function login()
    {
        return $this->fetch('/login');
    }

    public function index(){
        $today = strtotime(date('Y-m-d'));
        /*$sign = strtotime($this->userInfo['c_time'])+30*86400;*/
        $news = Db::name('news')->field('news_id,title,date_format(c_time, \'%Y-%m-%d\') as c_time')->limit(3)
            ->where('is_del=0')->order('news_id desc')->select();
        $price = Db::name('money_price')->where('is_del=0')->order('id desc')->value('price');
        $banner = Db::name('banner')->where('is_del=0 and status=1')->order('id desc')->select();
        /*$isSign =  Db::name('user_sign')->where(['user_id'=>$this->userId, 'sign_date'=>date('Y-m-d')])->count();
        $hasSign = $sign<$today ? 0 : 1;*/
        /*$orders = Db::name('lock_order')->alias('o')
            ->field('u.vip_number,o.money')
            ->join('p_user u', 'u.user_id=o.user_id')
            ->order('o.id desc')->limit(10)->select();
        foreach($orders as $k=>$v){
            $orders[$k]['vip_number'] = substr($v['vip_number'], 0, 8).'*****';
        }*/
        //$this->assign(['hasSign'=>$hasSign, 'price'=>$price, 'news'=>$news, 'isSign'=>$isSign, 'orders'=>$orders,'banner'=>$banner]);
        $this->assign(['price'=>$price,'news'=>$news,'banner'=>$banner]);
        return $this->fetch('/index');
    }

    public function about(){
        $this->assign('content', Config::getAboutUs());
        return $this->fetch();
    }

    public function ios(){
        $this->assign('ios', Config::getIos());
        return $this->fetch();
    }

    public function android(){
        $this->assign('android', Config::getAndroid());
        return $this->fetch();
    }

    public function xieyi(){
        $this->assign('content', Config::getAgreementContent());
        return $this->fetch();
    }

}