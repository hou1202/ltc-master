<?php
namespace app\admin\validate;

use app\admin\model\File;
use app\common\validate\BaseValidate;

class FileValidate extends BaseValidate
{
    /**
     * @var File
     */
    protected $model;


    protected $rule = [
        ['from', 'require'],
        ['action', 'require'],
        ['typeid', 'require'],
        ['id', 'require|checkId'],
    ];

    protected $scene = [
        'uploadimg' => ['from', 'action', 'typeid'],
        'delimg' => ['id'],
    ];

    public function checkId($value){
        $this->model = File::get($value);
        if($this->model == null){
            return false;
        }
        return true;
    }

}