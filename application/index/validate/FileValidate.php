<?php
namespace app\index\validate;

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
        ['img', 'require|checkImg'],
    ];

    protected $scene = [
        'uploadimg' => ['from', 'action', 'typeid'],
        'delimg' => ['id'],
        'base64' => ['img'],
    ];

    public function checkId($value){
        $this->model = File::get($value);
        if($this->model == null){
            return false;
        }
        return true;
    }

    public function checkImg($value){
        /*if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $value, $result)){
            $this->data['type'] = $result[2];
            $this->data['content'] = $result[1];
            return true;
        }
        return false;*/
        $this->data['type'] = 'jpg';
        $this->data['content'] = $value;
        return true;
    }

}