<?php
namespace app\admin\validate;

use app\admin\model\Banner;
use app\common\validate\BaseValidate;
use think\Db;

class BannerValidate extends BaseValidate
{
    /**
     * @var CbLog
     */
    protected $model;


    protected $rule = [
        ['id', 'require|gt:0|checkId'],
        ['title', 'require|length:1,50'],
        ['poster|展示图', 'require'],
        ['status|状态', 'require|in:0,1'],
    ];

    protected $scene = [
        'add' => ['title', 'poster','status'],
        'edit' => ['id', 'title', 'poster','status'],
    ];

    protected $message = [

    ];

    public function checkId($value){
        $this->model = Banner::get($value);
        return $this->model != null;
    }

}