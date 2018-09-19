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
        $config = Db::name('config')->field('content')->where('id in(4,17,18)')->order('id asc')->select();
        $this->assign('config',$config);
        $this->assign('qrcode', '/uploads/qrcode/'.$fileName);
        return $this->fetch();
    }

    public function members(){
        $friends = Db::name('user')->field('user_id,nick_name,c_time,group_count,hy_count,zt_count,vip_number')
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
            ->field('l.money as income,u.user_id,u.nick_name,o.number,o.c_time,u.vip_number')
            ->join('p_miner o', 'o.id=l.order_id')
            ->join('p_user u', 'u.user_id=o.user_id')
            ->where('l.user_id='.$this->userId.' AND type=6')
            ->select();
        $this->assign(['logs'=>$logs]);
        return $this->fetch();
    }

}