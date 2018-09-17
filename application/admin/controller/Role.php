<?php
namespace app\admin\controller;


use app\admin\model\SystemNode;
use app\common\controller\AdminCheckLoginController;

class Role extends AdminCheckLoginController
{

    public static $sModelClass = 'SystemRole';

    public function index()
    {
        if ($this->request->isPost()) {
            $order = $this->request->post('sortField').' '.$this->request->post('sortType','desc');
            $page = (int)$this->request->post('page');
            $limit = (int)$this->request->post('limit');
            $name = $this->request->post('searchName', '');
            $searhDate = $this->request->post('searchDate', '');
            $where = 'is_del=0';
            !empty($name) && $where .= ' AND rolename like \'%'.$name.'%\'';
            if(!empty($searhDate)){
                $dates = explode(' - ', $searhDate);
                $where .= ' AND c_time>=\''.$dates[0].'\' AND c_time<=\''.$dates[1].'\'';
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
            return $this->fetch();
        }
    }

    public function edit()
    {
        if($this->request->isPost()){
            if($this->model->id == 1){
                return $this->jsonFail('超级管理员不能修改');
            }
            $data = $this->request->post();
            if($this->modelFactory->edit($data)){
                //修改
                return $this->jsonSuccess('修改成功');
            }else{
                return $this->jsonFail('修改失败');
            }
        }else{
            return $this->fetch();
        }
    }

    public function del()
    {
        if($this->model->id == 1){
            return $this->jsonFail('该角色不能删除');
        }
        if($this->modelFactory->del()){
            return $this->jsonSuccess('删除成功');
        }
        return $this->jsonFail('删除失败');
    }

    public function giveAccess(){
        $param = $this->request->post();
        $node = new SystemNode();
        if($this->model->id == 1){
            return $this->jsonFail('超级管理员不能被修改');
        }
        //获取现在的权限
        if('get' == $param['type']){
            $nodeStr = $node->getNodeInfo($this->model->rule);
            return $this->jsonSuccess('success',$nodeStr);
        }
        //分配新权限
        if('give' == $param['type']){
            if(empty($param['rule'])){
                return $this->jsonFail('权限不能为空');
            }
            $data = SystemNode::getRoleNodes($param['rule']);
            $doparam = [
                'rule' => $param['rule'],
                'menu' => json_encode($data['menus']),
                'rules' => json_encode($data['rules']),
            ];

            if($this->modelFactory->edit($doparam)){
                return $this->jsonSuccess('修改成功');
            }
            return $this->jsonFail('修改失败');
        }
    }


}