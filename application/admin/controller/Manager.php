<?php
namespace app\admin\controller;


use app\admin\model\SystemManager;
use app\admin\model\SystemRole;
use app\common\controller\AdminCheckLoginController;
use think\Session;

class Manager extends AdminCheckLoginController
{

    public static $sModelClass = 'SystemManager';


    public function index()
    {
        if ($this->request->isPost()) {
            $order = $this->request->post('sortField').' '.$this->request->post('sortType','desc');
            $page = (int)$this->request->post('page');
            $limit = (int)$this->request->post('limit');
            $name = $this->request->post('searchName', '');
            $searhDate = $this->request->post('searchDate', '');
            $where = 'is_del=0';
            !empty($name) && $where .= ' AND username like \'%'.$name.'%\'';
            if(!empty($searhDate)){
                $dates = explode(' - ', $searhDate);
                $where .= ' AND last_login_time>=\''.$dates[0].'\' AND last_login_time<=\''.$dates[1].'\'';
            }
            $totalCount = $this->model->totalCount($where);
            $list = $totalCount > 0 ? $this->model->index($where, $page, $limit, $order) : [];
            $data = ['code' => 0, 'msg' => 'Success', 'data' => $list, 'count' => $totalCount];
            return $this->json($data);
        } else {
            $assign['page'] = $this->request->get('page', 1);
            $assign['sortType'] = $this->request->get('sortType', 'desc');
            $assign['sortField'] = $this->request->get('sortField', $this->view->primaryKey);
            $assign['searchName'] = $this->request->get('searchName','');
            $assign['searchDate'] = $this->request->get('searchDate','');
            $this->assign($assign);
            return $this->fetch();
        }
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
            $this->assign(['roles'=>SystemRole::getAllRoles()]);
            return $this->fetch();
        }
    }

    public function edit()
    {
        if($this->request->isPost()){
            if($this->model->id == 1){
                return $this->jsonFail('该管理员不能修改');
            }
            $data = $this->request->post();
            if($this->modelFactory->edit($data)){
                return $this->jsonSuccess('修改成功');
            }else{
                return $this->jsonFail('修改失败');
            }
        }else{
            $this->assign(['roles'=>SystemRole::getAllRoles()]);
            return $this->fetch();
        }
    }

    public function del()
    {
        if($this->model->id == 1){
            return $this->jsonFail('该管理员不能删除');
        }
        if($this->modelFactory->del()){
            return $this->jsonSuccess('删除成功');
        }
        return $this->jsonFail('删除失败');
    }

    public function resetPwd(){
        $this->model = SystemManager::get($this->systemManagerId);
        $this->modelFactory->setModel($this->model);
        $this->modelFactory->edit(['password'=>$this->request->post('password1')]);
        Session::delete('systemManagerId', null);
        Session::delete('systemManagerName', null);
        Session::delete('systemManagerPoster', null);
        Session::delete('rules', null);
        return $this->jsonSuccess('修改成功', ['url'=>'/admin/login/index']);
    }


}