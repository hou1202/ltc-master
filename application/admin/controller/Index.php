<?php
namespace app\admin\controller;

use app\admin\model\SystemRole;
use think\Config;
use app\admin\model\SystemNode;
use app\common\controller\AdminCheckLoginController;
use think\Db;
use think\Response;

class Index extends AdminCheckLoginController
{

    protected static $sModelClass = 'SystemManager';

    public function index()
    {
        $this->model = $this->model->get($this->systemManagerId);
        if(Config::get('app_debug')) {
            $systemNode = new SystemNode();
            $nodeData = $systemNode->getUserNodes($this->model->role_id);
            Db::table('p_system_role')->where('id=1')
                ->update(['menu' => json_encode($nodeData['menus'], true), 'rules' => json_encode($nodeData['rules'], true)]);
            $menus = $nodeData['menus'];
        }else {
            $menus = SystemRole::getMenus($this->model->role_id);
        }
        return $this->fetch('/index', ['menus'=>$menus]);
    }

    public function main()
    {
        $data = [];
        $data['newUser'] = (int)Db::table('p_user')->where('c_time>=\''.date('Y-m-d').'\'')->count();
        $data['memberCount'] = (int)Db::table('p_user_sign')->where('c_time>=\''.date('Y-m-d').'\'')->count();
        $data['todayCount'] = (int)Db::table('p_money_log')->where('c_time>=\''.date('Y-m-d').'\' AND type=1')->sum('money');
        $data['totalPrice'] = Db::table('p_money_price')->order('id desc')->value('price');
        return $this->fetch('/main', $data);
    }
}