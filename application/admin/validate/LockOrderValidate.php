<?php
namespace app\admin\validate;

use app\admin\model\LockOrder;
use app\common\validate\BaseValidate;
use think\Db;

class LockOrderValidate extends BaseValidate
{
    /**
     * @var LockOrder
     */
    protected $model;


    protected $rule = [
        ['id', 'require|gt:0|checkId'],
        ['status', 'in:0,1']
    ];

    protected $scene = [
        'edit' => ['id', 'status'],
    ];

    protected $message = [

    ];

    public function checkId($value){
        $this->model = LockOrder::get($value);
        return $this->model != null;
    }

}