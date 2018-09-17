<?php
namespace app\admin\controller;


use app\common\controller\AdminCheckLoginController;
use app\common\model\CommonUtils;
use think\Db;

class B extends AdminCheckLoginController
{

    public function index()
    {
        if ($this->request->isPost()) {
            $order = $this->request->post('sortField').' '.$this->request->post('sortType','desc');
            $page = (int)$this->request->post('page');
            $limit = (int)$this->request->post('limit');
            $name = $this->request->post('searchName', '');
            $searhDate = $this->request->post('searchDate', '');
            $where = '';
            !empty($name) && $where = CommonUtils::concatWhere($where, ' name like \'%'.$name.'%\'');
            if(!empty($searhDate)){
                $dates = explode(' - ', $searhDate);
                $where = CommonUtils::concatWhere($where, ' c_time>=\''.$dates[0].'\' AND c_time<=\''.$dates[1].'\'');
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
            $data = $this->request->post();
            if($this->modelFactory->edit($data)){
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
       $count = Db::name('tb_log')->where('b_id='.$this->model->id)->count();
        if($count>0){
            return $this->jsonFail('该提币平台有提币记录不能删除');
        }
        if($this->modelFactory->del()){
            return $this->jsonSuccess('删除成功');
        }
        return $this->jsonFail('删除失败');
    }

}