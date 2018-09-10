<?php
namespace app\admin\validate;

use app\admin\model\Kefu;
use app\common\validate\BaseValidate;
use think\Db;

class KefuValidate extends BaseValidate
{
    /**
     * @var Kefu
     */
    protected $model;


    protected $rule = [
        ['id', 'require|gt:0|checkId'],
        ['reply|反馈', 'length:1,255']
    ];

    protected $scene = [
        'edit' => ['id', 'reply'],
    ];

    protected $message = [

    ];

    public function checkId($value){
        $this->model = Kefu::get($value);
        return $this->model != null;
    }

}