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
namespace app\admin\validate;

use app\admin\model\SystemManager;
use app\admin\model\SystemRole;
use app\common\validate\BaseValidate;
use think\Db;

class SystemRoleValidate extends BaseValidate
{
    /**
     * @var SystemRole
     */
    protected $model;


    protected $rule = [
        ['rolename|角色名', 'require|length:1,12'],
        ['id', 'require|gt:0|checkId'],
    ];

    protected $scene = [
        'giveaccess' => ['id'],
        'add' => ['rolename'],
        'edit' => ['id','rolename'],
        'del' => ['id'=>'require|gt:0|checkId|checkDel'],
    ];

    protected $message = [
        'id.checkDel' => '该角色下有管理员，不能删除',
    ];


    public function checkId($value){
        $this->model = SystemRole::get($value);
        return $this->model != null;
    }

    public function checkDel($value){
        return SystemManager::where('role_id='.$value)->count()<=0;
    }



}