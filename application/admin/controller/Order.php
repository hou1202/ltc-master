<?php
namespace app\admin\controller;


use app\common\controller\AdminCheckLoginController;
use app\common\model\CommonUtils;
use think\Db;

class Order extends AdminCheckLoginController
{

    public function init()
    {
        parent::init();
    }

    public function index()
    {
        if ($this->request->isPost()) {
            $order = $this->request->post('sortField').' '.$this->request->post('sortType','desc');
            $page = (int)$this->request->post('page');
            $limit = (int)$this->request->post('limit');
            $name = $this->request->post('searchName', '');
            $searhDate = $this->request->post('searchDate', '');
            $where = '';
            !empty($name) && $where = CommonUtils::concatWhere($where, ' (u.mobile like \'%'.$name.'%\' OR u.real_name like \'%'.$name.'%\' OR u.invitation_code like \'%'.$name.'%\')');
            if(!empty($searhDate)){
                $dates = explode(' - ', $searhDate);
                $where =  CommonUtils::concatWhere($where, ' m.c_time>=\''.$dates[0].'\' AND m.c_time<=\''.$dates[1].'\'');
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
            $userId = $this->request->get('userId', 0);
            $users = Db::name('user')->field('user_id,mobile,real_name,invitation_code')->select();
            $this->assign(['users'=>$users, 'userId'=>$userId]);
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
            $user =  Db::name('user')->where('user_id='.$this->model->user_id)->find();
            if($this->model->sell_id > 0) {
                $sell = Db::name('user')->where('user_id=' . $this->model->sell_id)->find();
            }else{
                $sell = ['real_name'=>'', 'mobile'=>''];
            }
            $status = [1=>'撮合中', 2=>'待付款', 3=>'已付款', 4=>'已完成', 9=>'已失效'];
            $this->assign('user',$user);
            $this->assign('sell',$sell);
            $this->assign('status', $status[$this->model->status]);
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