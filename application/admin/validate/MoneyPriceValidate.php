<?php
namespace app\admin\validate;

use app\admin\model\MoneyPrice;
use app\common\validate\BaseValidate;
use think\Db;

class MoneyPriceValidate extends BaseValidate
{
    /**
     * @var MoneyPrice
     */
    protected $model;


    protected $rule = [
        ['id', 'require|gt:0|checkId'],
        ['price', 'egt:0'],
    ];

    protected $scene = [
        'edit' => ['id', 'price'],
        'del' => ['id'],
        'add' => ['price'],
    ];


    public function checkId($value){
        $this->model = MoneyPrice::get($value);
        return $this->model != null;
    }


}