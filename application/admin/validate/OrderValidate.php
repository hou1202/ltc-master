<?php
namespace app\admin\validate;

use app\admin\model\Order;
use app\common\validate\BaseValidate;
use think\Db;

class OrderValidate extends BaseValidate
{
    /**
     * @var Order
     */
    protected $model;


    protected $rule = [
        ['id', 'require|gt:0|checkId'],
        ['status', 'in:2,3'],
        ['remark|备注', 'length:0,255'],
    ];

    protected $scene = [
        'edit' => ['id', 'status', 'remark'],
    ];

    protected $message = [

    ];

    public function checkId($value){
        $this->model = Order::get($value);
        return $this->model != null;
    }

}