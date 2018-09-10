<?php
namespace app\admin\model;

use app\common\model\BaseAdminModel;
use think\Db;
use think\Model;

class SystemManager extends BaseAdminModel
{
    const COMMENT = 1;
    const BAN = 0;

    private $roles;

    public static $statusInfo = [self::COMMENT=>'正常', self::BAN=>'禁用'];

    protected  $imageSize = ['poster'=>['name'=>'头像','width'=>200, 'height'=>200, 'limit'=>1]];

    public function getTitle()
    {
        return '系统管理员';
    }

    public function setPasswordAttr($passWord){
        return md5(md5($passWord));
    }


    public function index($where, $page, $limit, $order)
    {
        $offset = ($page-1)*$limit;
        return $this->field('id,username,real_name,status as status_text,role_id as role_text,last_login_time,last_login_ip,loginnum')
            ->where($where)->limit($offset, $limit)->order($order)->select();
    }

    public function totalCount($where)
    {
        return $this->where($where)->count();
    }

    public function add($data)
    {
        $this->save($data);
        if($this->id<=0){
            return false;
        }
        return true;
    }


    public function edit($data)
    {
        if(empty($data['password'])){
            unset($data['password']);
        }
        $this->save($data);
        return true;
    }

    public function del()
    {
        $this->save(['is_del'=>time()]);
        return true;
    }

    public function getStatusTextAttr($status)
    {
        return static::$statusInfo[$status];
    }

    public function getRoleTextAttr($value){
        if($this->roles == null){
            $this->makeRoles();
        }
        return $this->roles[$value];
    }

    public function makeRoles(){
        $this->roles = SystemRole::getRoles();
    }

    public function getRoles(){
        return SystemRole::getRules($this->role_id);
    }

}