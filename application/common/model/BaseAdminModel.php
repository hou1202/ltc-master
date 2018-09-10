<?php
/**
 * Project: 基础Model类
 * User: Zhu Ziqiang
 * Date: 2017/8/31
 * Time: 11:10
 */

namespace app\common\model;


use app\admin\model\File;
use app\common\service\IAction;
use think\Model;

abstract class BaseAdminModel extends Model implements IAction
{

    protected  $imageSize = [];

    public function getImageSize(){
        return $this->imageSize;
    }

    public function updateFile(){
        $urls = [];
        foreach($this->imageSize as $k=>$v){
            if(!empty($this->$k)){
                $urls = array_merge($urls, explode(',', $this->$k));
            }
        }
        $file = new File();
        $file->where(['url'=>['in', $urls]])->update(['typeid'=>$this[$this->getPk()]]);
    }

    public function getFiles($field){
        $where = $this->$field;
        if(empty($where)){
            return [];
        }
        $file = new File();
        return $file->where(['url'=>['in', $where]])->select();
    }

}