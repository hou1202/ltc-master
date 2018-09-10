<?php
namespace app\admin\controller;


use app\admin\model\SystemManager;
use app\common\controller\AdminCheckLoginController;
use think\Db;

class ManagerAction extends AdminCheckLoginController
{

    public function index()
    {
        if ($this->request->isPost()) {
            $page = (int)$this->request->post('page');
            $limit = (int)$this->request->post('limit');
            $name = $this->request->post('searchName', '');
            $searhDate = $this->request->post('searchDate', '');
            $where = 'dls_id=0';
            if(!empty($name)) {
                $where .= ' AND manager_id='.$name;
            }
            if(!empty($searhDate)){
                $dates = explode(' - ', $searhDate);
                $where .= ' AND c_time>=\''.$dates[0].'\' AND c_time<=\''.$dates[1].'\'';
            }
            $totalCount = $this->model->totalCount($where);
            $list = $totalCount > 0 ? $this->model->index($where, $page, $limit, 'id desc') : [];
            $data = ['code' => 0, 'msg' => 'Success', 'data' => $list, 'count' => $totalCount];
            return $this->json($data);
        } else {
            $this->assign(['managers'=>SystemManager::all(['is_del'=>0])]);
            return $this->fetch();
        }
    }


}