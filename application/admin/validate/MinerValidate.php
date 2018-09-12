<?php
namespace app\admin\validate;

use app\admin\model\Miner;
use app\common\validate\BaseValidate;
use think\Db;

class MinerValidate extends BaseValidate
{
    /**
     * @var Miner
     */
    protected $model;


    protected $rule = [
        ['id', 'require|gt:0|checkId'],
        ['status', 'in:0,1'],
    ];

    protected $scene = [
        'edit' => ['id', 'status'],
    ];

    protected $message = [

    ];

    public function checkId($value){
        $this->model = Miner::get($value);
        return $this->model != null;
    }

}