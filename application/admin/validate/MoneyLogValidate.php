<?php
namespace app\admin\validate;

use app\admin\model\MoneyLog;
use app\common\validate\BaseValidate;
use think\Db;

class MoneyLogValidate extends BaseValidate
{
    /**
     * @var MoneyLog
     */
    protected $model;


    protected $rule = [
        ['id', 'require|gt:0|checkId'],
        ['remark', 'length:1,255'],
        ['money', 'require'],
        ['user_id|用户ID', 'require|gt:0'],
    ];

    protected $scene = [
        'add' => ['money', 'remark', 'user_id'],
        'edit' => ['item_id',  'title', 'sort', 'sub_content'],
        'del'=>['item_id']
    ];

    protected $message = [

    ];

    public function checkId($value){
        $this->model = MoneyLog::get($value);
        return $this->model != null;
    }

}