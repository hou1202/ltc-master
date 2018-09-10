<?php
namespace app\admin\validate;

use app\admin\model\NewsCategory;
use app\common\validate\BaseValidate;
use think\Db;

class NewsCategoryValidate extends BaseValidate
{
    /**
     * @var NewsCategory
     */
    protected $model;


    protected $rule = [
        ['category_id', 'require|gt:0|checkId'],
        ['sort', 'egt:0'],
        ['category_name', 'length:1,50'],
    ];

    protected $scene = [
        'edit' => ['category_id', 'sort'],
        'del' => ['category_id'],
        'add' => ['category_name', 'sort'],
    ];

    protected $message = [
        'status.checkStatus' => '订单状态不正确！'
    ];

    public function checkId($value){
        $this->model = NewsCategory::get($value);
        return $this->model != null;
    }


}