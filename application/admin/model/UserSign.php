<?php
namespace app\admin\model;

use app\common\model\BaseAdminModel;
use think\Db;
use think\Model;

class UserSign extends BaseAdminModel
{


    public function getTitle()
    {
        return '用户签到';
    }

    public function index($where, $page, $limit, $order)
    {
        $offset = ($page-1)*$limit;
        return $this->alias('s')->field('s.id,s.sign_date,s.c_time,u.real_name,u.mobile,u.invitation_code')
            ->join('p_user u', 'u.user_id=s.user_id')
            ->where($where)->limit($offset, $limit)->order($order)->select();
    }

    public function totalCount($where)
    {
        return $this->alias('s')->join('p_user u', 'u.user_id=s.user_id')->where($where)->count();
    }

    public function add($data)
    {
        $this->save($data);
        return true;
    }


    public function edit($data)
    {
        $this->save($data);
        return true;
    }

    public function del()
    {
        $this->save(['is_del'=>time()]);
        return true;
    }

    /**
     * 获取服务项目
     * @return array
     */
    public static function getServices(){
        $services = Db::table(static::getTable())->field('item_id,title,banner')->where('is_del=0')->order('sort ASC,item_id DESC')->select();
        foreach($services as $k=>$v){
            $services[$k]['banner'] = $v['banner']==null ? [] : explode(',', $v['banner']);
        }
        return $services;
    }

    /**
     * 服务项目详情
     * @param $itemId int
     * @return array|null
     * @throws \think\Exception
     */
    public static function getServiceItemDetail($itemId){
        return Db::table(static::getTable())->where('item_id='.$itemId)->field('title,c_time,content')->find();
    }

}