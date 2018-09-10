<?php

namespace app\common\validate;
use think\Model;
use think\Validate;

class BaseValidate extends Validate
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * @var array
     */
    protected $data;

    /**
     * @return Model
     */
    public function getModel(){
        return $this->model;
    }

    /**
     * @param string $key
     * @return array|object
     */
    public function getData($key=''){
        if($key===''){
            return $this->data;
        }
        return $this->data[$key];
    }


}