<?php

namespace app\admin\model;

use app\common\model\BaseAdminModel;
use think\Db;
use think\Model;

class Kefu extends BaseAdminModel
{


    public function getTitle()
    {
        return '留言反馈';
    }

    protected $imageSize = [
        'images' => ['name' => '图片', 'limit' => 3, 'height' => 200, 'width' => 200],
    ];

    public function index($where, $page, $limit, $order)
    {
        $offset = ($page - 1) * $limit;
        return $this->alias('m')
            ->field('m.id,m.ask_type,m.content,m.c_time,u.mobile,u.real_name,u.invitation_code')
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
        $data['reply_time'] = date('Y-m-d H:i:s');
        $this->save($data);
        return true;
    }

    public function del()
    {
        $this->save(['is_del' => time()]);
        return true;
    }

}