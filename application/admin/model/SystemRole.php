<?php

namespace app\admin\model;

use app\common\model\BaseAdminModel;

class SystemRole extends BaseAdminModel
{

    /**
     * 获取roles
     * @return array
     */
    public static function getAllRoles(){
        return static::where('is_del=0')->field('id,rolename')->select();
    }

    /**
     * 获取roles
     * @return array
     */
    public static function getRoles(){
        return static::where('is_del=0')->column('rolename', 'id');
    }


    public static function getRules($id){
        $rules = static::where('id='.$id)->value('rules');
        return empty($rules) ? [] : json_decode($rules, true);
    }

    public static function getMenus($id){
        $menu = static::where('id='.$id)->value('menu');
        return empty($menu) ? [] : json_decode($menu, true);
    }

    function index($where, $page, $limit, $order)
    {
        $offset = ($page-1)*$limit;
        return $this->where($where)->limit($offset, $limit)->order($order)->select();
    }

    function totalCount($where)
    {
        return $this->where($where)->count();
    }

    function add($data)
    {
        $this->save($data);
        return true;
    }

    function edit($data)
    {
        $this->save($data);
        return true;
    }

    function del()
    {
        $this->save(['is_del'=>time()]);
        return true;
    }

    function getTitle()
    {
        return '角色';
    }

}