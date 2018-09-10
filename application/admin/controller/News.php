<?php
namespace app\admin\controller;


use app\admin\model\NewsCategory;
use app\common\controller\AdminCheckLoginController;

class News extends AdminCheckLoginController
{

    public function init()
    {
        parent::init();
        if($this->request->isGet()){
           $this->assign(['categorys'=>NewsCategory::all(['is_del'=>0])]);
        }
    }

    public function index()
    {
        if ($this->request->isPost()) {
            $order = $this->request->post('sortField').' '.$this->request->post('sortType');
            $page = (int)$this->request->post('page');
            $limit = (int)$this->request->post('limit');
            $name = $this->request->post('searchName', '');
            $searhDate = $this->request->post('searchDate', '');
            $categoryId = $this->request->post('category_id', ' ');
            $where = 'is_del=0';
            !empty($name) && $where .= ' AND title like \'%'.$name.'%\'';
            if(!empty($searhDate)){
                $dates = explode(' - ', $searhDate);
                $where .= ' AND c_time>=\''.$dates[0].'\' AND c_time<=\''.$dates[1].'\'';
            }
            $categoryId !== ' ' && $where .= ' AND category_id='.$categoryId;
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
            $assign['category_id'] = $this->request->get('category_id',' ');
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
        if($this->modelFactory->del()){
            return $this->jsonSuccess('删除成功');
        }
        return $this->jsonFail('删除失败');
    }

}