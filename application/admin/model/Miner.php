<?php
namespace app\admin\model;

use app\common\model\BaseAdminModel;
use think\Db;
use think\Model;

class Miner extends BaseAdminModel
{


    public function getTitle()
    {
        return '矿机记录';
    }

    public function index($where, $page, $limit, $order)
    {
        $offset = ($page-1)*$limit;
        return $this->alias('m')
            ->field('m.id,m.number,m.c_time,m.e_time,m.status,u.mobile,u.real_name')
            ->where($where)
            ->join('p_user u', 'u.user_id=m.user_id')
            ->limit($offset, $limit)
            ->order($order)
            ->select();
    }

    public function totalCount($where)
    {
        return $this->alias('m')->join('p_user u', 'u.user_id=m.user_id')->where($where)->count();
    }

    public function add($data)
    {

        return true;
    }

    public function edit($data)
    {
       return true;
    }

    public function del()
    {
        $this->save(['is_del'=>time()]);
        return true;
    }

}