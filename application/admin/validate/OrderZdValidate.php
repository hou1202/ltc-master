<?php
namespace app\admin\validate;


use app\admin\model\OrderZd;
use app\common\validate\BaseValidate;

class OrderZdValidate extends BaseValidate
{

    protected $rule = [
        ['zd_id', 'require|gt:0|checkId'],
        ['status', 'require|in:0,1'],
        ['remark|备注', 'require|length:0,250']
    ];

    protected $scene = [
        'edit'=>['zd_id', 'status', 'remark'],
    ];

    public function checkId($value){
        $this->model = OrderZd::get($value);
        return $this->model != null;
    }

}