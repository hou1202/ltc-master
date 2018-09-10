<?php
/**
 * Project: wxShare
 * User: Zhu Ziqiang
 * Date: 2017/8/31
 * Time: 14:02
 */

namespace app\common\utils;


use app\common\model\BaseAdminModel;
use think\Db;

class ModelFactory
{
    /**
     * @var BaseAdminModel
     */
    private $model;

    /**
     * @var array
     */
    private $manager;

    /**
     * @var ModelFactory
     */
    private static $instance;

    private function __construct($model){
        $this->model = $model;
    }

    private function __clone(){}

    private function insertLog(){
        Db::table('p_manager_action')->insert($this->manager);
    }

    public static function newInstance($model){
        if(static::$instance == null){
            static::$instance = new ModelFactory($model);
        }
        return static::$instance;
    }

    public function recordLog($logData){
        $this->manager = $logData;
        $this->insertLog();
    }

    public function setManager($manager){
        $this->manager = $manager;
    }

    public function getModel(){
        return $this->model;
    }

    public function setModel($model){
        return $this->model = $model;
    }

    public function add($data){
        $imagesSize = $this->model->getImageSize();
        if(!empty($imagesSize)){
            foreach($imagesSize as $k=>$v){
                unset($data[$k.'File']);
            }
        }
        if($this->model->add($data)){
            !empty($imagesSize) && $this->model->updateFile();
            //添加记录
            $this->manager['log'] = '增加了'.$this->model->getTitle();
            $this->manager['content'] = $this->model->toJson();
            $this->insertLog();
            return true;
        }
        return false;
    }

    public function edit($data){
        $imagesSize = $this->model->getImageSize();
        if(!empty($imagesSize)){
            foreach($imagesSize as $k=>$v){
                unset($data[$k.'File']);
            }
        }
        if($this->model->edit($data)){
            !empty($imagesSize) && $this->model->updateFile();
            //添加记录
            $this->manager['log'] = '修改了'.$this->model->getTitle();
            $this->manager['content'] = $this->model->toJson();
            $this->insertLog();
            return true;
        }
        return false;
    }

    public function del(){
        if($this->model->del()){
            //添加记录
            $this->manager['log'] = '删除了'.$this->model->getTitle();
            $this->manager['content'] = $this->model->toJson();
            $this->insertLog();
            return true;
        }
        return false;
    }

}