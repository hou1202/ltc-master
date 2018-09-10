<?php
namespace app\admin\validate;

use app\admin\model\LockPlan;
use app\common\validate\BaseValidate;
use think\Db;

class LockPlanValidate extends BaseValidate
{
    /**
     * @var LockPlan
     */
    protected $model;


    protected $rule = [
        ['plan_id', 'require|gt:0|checkId'],
        ['count|数量', 'gt:0'],
        ['rate|利率', 'gt:0'],
    ];

    protected $scene = [
        'edit' => ['plan_id', 'count', 'rate'],
        'del' => ['plan_id'],
        'add' => ['count', 'rate'],
    ];


    public function checkId($value){
        $this->model = LockPlan::get($value);
        return $this->model != null;
    }


}