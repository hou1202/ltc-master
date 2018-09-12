<?php
namespace app\admin\controller;


use app\common\controller\AdminCheckLoginController;
use app\common\model\CommonUtils;
use think\Db;

class Banner extends AdminCheckLoginController
{

    public function init()
    {
        parent::init();
    }

    public function index()
    {
        $assign['page'] = $this->request->get('page', 1);
        $assign['sortType'] = $this->request->get('sortType', 'desc');
        $assign['sortField'] = $this->request->get('sortField', $this->view->primaryKey);
        $assign['searchName'] = $this->request->get('searchName','');
        $assign['searchDate'] = $this->request->get('searchDate','');
        $this->assign($assign);
        return $this->fetch();

    }

    public function add()
    {
        if($this->request->isPost()){
            $data = $this->request->post();
            if($this->modelFactory->add($data)){
                return $this->jsonSuccess('添加成功');
            }else{
                return $this->jsonFail('添加失败');
            }
        }else{

            return $this->fetch();
        }
    }

    public function edit()
    {
        if($this->request->isPost()){
            $data = $this->request->post();
            //$userId = $this->request->get('userId', 0);

            if($this->modelFactory->edit($data)){
                return $this->jsonSuccess('修改成功');
            }else{
                return $this->jsonFail('修改失败');
            }
        }else{
            $user =  Db::name('user')->where('user_id='.$this->model->user_id)->find();
            $this->assign('user',$user);
            $this->assign('address', Db::name('address')->where('id='.$user['address_id'])->find());
            return $this->fetch();
        }
    }

    public function del()
    {
        if($this->modelFactory->del()){
            return $this->jsonSuccess('删除成功');
        }
        return $this->jsonFail('删除失败');
    }

}