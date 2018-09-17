<?php
namespace app\admin\controller;


use app\common\controller\AdminCheckLoginController;
use think\Db;

class LockPlan extends AdminCheckLoginController
{

    public function index()
    {
        if ($this->request->isPost()) {
            $order = $this->request->post('sortField').' '.$this->request->post('sortType','desc');
            $page = (int)$this->request->post('page');
            $limit = (int)$this->request->post('limit');
            $name = $this->request->post('searchName', '');
            $searhDate = $this->request->post('searchDate', '');
            $where = 'is_del=0';
            !empty($name) && $where .= ' AND count like \'%'.$name.'%\'';
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
            //判断rate是否重复
            //$date = date('Y-m-d');
            $rate = number_format($data['rate'], 2);
            if (Db::name('lock_plan')->where('is_del=0 AND rate='.$rate)->count() >0 ){
                return $this->jsonFail('利率不能和其他理财计划利率一样');
            }
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
            //$date = date('Y-m-d');
            $rate = number_format($data['rate'], 2);
            if (Db::name('lock_plan')->where('is_del=0 AND rate='.$rate.' AND plan_id!='.$data['plan_id'])->count() >0 ){
                return $this->jsonFail('利率不能和其他理财计划利率一样');
            }
            //更新rate lock_order
            Db::name('lock_order')->where('rate='.$this->model->rate.' AND status=0')->update(['rate'=>$rate, 'income'=>['exp', 'money*'.bcdiv($rate,100,4)]]);
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
        if($this->modelFactory->del()){
            return $this->jsonSuccess('删除成功');
        }
        return $this->jsonFail('删除失败');
    }

}