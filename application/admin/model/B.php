<?php
namespace app\admin\model;

use app\common\model\BaseAdminModel;
use think\Db;
use think\Model;

class B extends BaseAdminModel
{

    public function getTitle()
    {
        return '提币平台';
    }

    public function index($where, $page, $limit, $order)
    {
        $offset = ($page-1)*$limit;
        return $this->field('id,name,sort,c_time')
            ->where($where)->limit($offset, $limit)->order($order)->select();
    }

    public function totalCount($where)
    {
        return $this->where($where)->count();
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
        $this->delete();
        return true;
    }

}