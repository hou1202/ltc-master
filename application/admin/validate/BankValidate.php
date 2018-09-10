<?php
namespace app\admin\validate;

use app\admin\model\Bank;
use app\common\validate\BaseValidate;

class BankValidate extends BaseValidate
{
    /**
     * @var Bank
     */
    protected $model;


    protected $rule = [
        ['id', 'require|gt:0|checkId'],
        ['sort', 'egt:0'],
        ['name', 'length:1,255'],
    ];

    protected $scene = [
        'edit' => ['id'],
        'del' => ['id'],
        'add' => ['name'],
    ];

    protected $message = [
        'status.checkStatus' => '订单状态不正确！'
    ];

    public function checkId($value){
        $this->model = Bank::get($value);
        return $this->model != null;
    }


}