<?php
namespace app\admin\controller;


use app\common\controller\AdminCheckLoginController;

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

}