<?php
namespace app\index\controller;


use app\common\controller\IndexController;
use think\Config;
use think\Db;

class Share extends IndexController
{

    protected static $sPermissionArr = [
        'index'=>5,
        'members'=>5,
        'income'=>5,
    ];

    protected static $sParamsArr =[
    ];

    public function index()
    {
        $domain = Config::get('upload_file_domain').'/index/index/register?invitation_code='.$this->userInfo['invitation_code'];
        $fileName = md5($domain).'.png';
        $this->assign('qrcode', '/uploads/qrcode/'.$fileName);
        return $this->fetch();
    }

    public function members(){
        $friends = Db::name('user')->field('user_id,c_time,group_count,hy_count,zt_count,vip_number')
            ->where('parent_id='.$this->userId)
            ->order('user_id asc')
            ->select();
        foreach($friends as $k=>$v){
            $friends[$k]['c_time'] = date('Y-m-d', strtotime($v['c_time']));
        }
        $this->assign(['friends'=>$friends]);
        return $this->fetch();
    }

    public function income()
    {
        $logs = Db::name('money_log')->alias('l')
            ->field('l.money as income,u.user_id,u.c_time,o.money,o.days,u.vip_number')
            ->join('p_lock_order o', 'o.id=l.order_id')
            ->join('p_user u', 'u.user_id=o.user_id')
            ->where('l.user_id='.$this->userId.' AND type=6')
            ->select();
        $this->assign(['logs'=>$logs]);
        return $this->fetch();
    }

}