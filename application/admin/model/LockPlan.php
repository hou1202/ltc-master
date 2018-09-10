<?php
namespace app\admin\model;

use app\common\model\BaseAdminModel;
use think\Db;
use think\Model;

class LockPlan extends BaseAdminModel
{

    public function getTitle()
    {
        return '锁仓计划';
    }

    public function index($where, $page, $limit, $order)
    {
        $offset = ($page-1)*$limit;
        return $this->field('plan_id,days,rate,count,sy_count,start_time,end_time,c_time')
            ->where($where)->limit($offset, $limit)->order($order)->select();
    }

    public function totalCount($where)
    {
        return $this->where($where)->count();
    }

    public function add($data)
    {
        //$data['sy_count'] = $data['count'];
        $this->save($data);
        return true;
    }


    public function edit($data)
    {
        //$data['sy_count'] = $data['count'];
        $this->save($data);
        return true;
    }

    public function del()
    {
        $this->save(['is_del'=>time()]);
        return true;
    }

}