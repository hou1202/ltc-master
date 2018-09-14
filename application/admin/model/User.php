<?php
// +----------------------------------------------------------------------
// | snake
// +----------------------------------------------------------------------
// | Copyright (c) 2016~2022 http://baiyf.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: NickBai <1902822973@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\model;

use app\common\model\ApiUserToken;
use app\common\model\BaseAdminModel;
use app\common\model\Easemob;
use think\Config;


class User extends BaseAdminModel
{

//    protected $imageSize = [
//        'poster'=>['name'=>'头像','limit'=>1, 'height'=>200, 'width'=>200],
//        'cards'=>['name'=>'证件照', 'limit'=>3, 'height'=>200, 'width'=>200],
//    ];

    public function getTitle()
    {
        return '用户';
    }

    public function index($where, $page, $limit, $order)
    {
        $offset = ($page-1)*$limit;
        return $this->alias('u')
            ->field('u.user_id,u.real_name,u.mobile,u.c_time,u.ky_money,u.gd_money,u.invitation_code,u.identity_number,u.is_del,u.miner_num,u.grade')
            ->where($where)
            ->limit($offset, $limit)
            ->order($order)
            ->select();
    }

    public function totalCount($where)
    {
        return $this->alias('u')->where($where)->count();
    }

    public function add($data)
    {

    }


    public function edit($data)
    {
        $this->save($data);
        return true;
    }

    public function del()
    {
        //删除环信
//        $easemob = new Easemob();
//        $easemob->deleteUser($this->mobile);
//        Config::set('cache_prifix', 'KingTP_api_cache');
//        ApiUserToken::newInstance()->delTokenKey($this->mobile);
//        $this->delete();
        $time = $this->is_del > 0 ? 0 : time();
        $this->save(['is_del'=>$time]);
        return true;
    }

}