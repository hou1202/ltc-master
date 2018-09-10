<?php
namespace app\admin\validate;

use app\admin\model\Address;
use app\common\validate\BaseValidate;
use think\Db;

class AddressValidate extends BaseValidate
{
    /**
     * @var Address
     */
    protected $model;


    protected $rule = [
        ['id', 'require|gt:0|checkId'],
        ['sort', 'egt:0'],
        ['content', 'length:1,255'],
    ];

    protected $scene = [
        'edit' => ['id'],
        'del' => ['id'],
        'add' => ['content'],
    ];

    protected $message = [
        'status.checkStatus' => '订单状态不正确！'
    ];

    public function checkId($value){
        $this->model = Address::get($value);
        return $this->model != null;
    }


}