<?php
namespace app\admin\controller;


use app\common\controller\AdminCheckLoginController;
use think\Request;

class Config extends AdminCheckLoginController
{

    public function index()
    {
        if($this->request->isPost()){
            $data = $this->request->post();
            $this->modelFactory->edit($data);
            return $this->jsonSuccess('修改成功');
        }else {
            $configs = $this->model->column('content', 'id');
            $this->assign(['configs' => $configs]);
            return $this->fetch('/config');
        }
    }


    public function rank()
    {
        $configs = $this->model->where('id in(30,31,32,33,34)')->column('content', 'id');
        $this->assign(['configs' => $configs]);
        return $this->fetch('');
    }

    public function rebate()
    {
        $configs = $this->model->where('id in(17,18,19,20,23,24,25,26,27,28)')->column('content', 'id');
        $this->assign(['configs' => $configs]);
        return $this->fetch('');
    }

    public function renew()
    {
        $configs = $this->model->where('id in(29)')->column('content', 'id');
        $this->assign(['configs' => $configs]);
        return $this->fetch('');
    }


}