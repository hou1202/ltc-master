<?php
namespace app\admin\validate;

use app\admin\model\B;
use app\common\validate\BaseValidate;
use think\Db;

class BValidate extends BaseValidate
{
    /**
     * @var B
     */
    protected $model;


    protected $rule = [
        ['id', 'require|gt:0|checkId'],
        ['sort', 'egt:0'],
        ['name', 'length:1,255'],
    ];

    protected $scene = [
        'edit' => ['id', 'sort'],
        'del' => ['id'],
        'add' => ['name', 'sort'],
    ];

    protected $message = [
        'status.checkStatus' => '订单状态不正确！'
    ];

    public function checkId($value){
        $this->model = B::get($value);
        return $this->model != null;
    }


}