<?php
// +----------------------------------------------------------------------
// | KingTP
// +----------------------------------------------------------------------
// | 2018/1/12 14:55
// +----------------------------------------------------------------------
// | **基于ThinkPHP 5.0.14 + LayUI2.2.5封装**
// +----------------------------------------------------------------------
// | Author: 晓晓攻城师 <邮箱：1228676735@qq.com><博客：http://blog.csdn.net/qq_26245325>
// +----------------------------------------------------------------------

namespace app\admin\controller;


use app\admin\model\OpenArea;
use app\api\model\HospitalDepartment;
use app\api\model\Hospital;
use app\common\controller\AdminCheckLoginController;
use app\common\model\CommonUtils;

class User extends AdminCheckLoginController
{

    public function init()
    {
        parent::init();
    }


    public function index()
    {
        if ($this->request->isPost()) {
            $order = $this->request->post('sortField').' '.$this->request->post('sortType');
            $page = (int)$this->request->post('page');
            $limit = (int)$this->request->post('limit');
            $name = $this->request->post('searchName', '');
            $searhDate = $this->request->post('searchDate', '');
//            $hospitalId = $this->request->post('hospital_id', ' ');
//            $status = $this->request->post('status', ' ');
            $where = '';
            !empty($name) && $where = CommonUtils::concatWhere($where, '(u.real_name like \'%'.$name.'%\' OR u.mobile like \'%'.$name.'%\' OR u.invitation_code like \'%'.$name.'%\')');
            if(!empty($searhDate)){
                $dates = explode(' - ', $searhDate);
                $where = CommonUtils::concatWhere($where, 'u.c_time>=\''.$dates[0].'\' AND u.c_time<=\''.$dates[1].'\'');
            }
            //$where = CommonUtils::concatWhere($where,'u.is_del=0');
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
            $assign['hospital_id'] = $this->request->get('hospital_id',' ');
            $assign['status'] = $this->request->get('status',' ');
            $this->assign($assign);
            return $this->fetch();
        }
    }

    public function add(){}

    public function edit()
    {
        if($this->request->isPost()){
            $data = $this->request->post();
            $data['bank_name'] = $this->validate->getData('bank_name');
            if($this->modelFactory->edit($data)){
                return $this->jsonSuccess('修改成功');
            }else{
                return $this->jsonFail('修改失败');
            }
        }else{
//            $districts = OpenArea::getOpenDistricts($this->model->city_id);
//            array_unshift($districts, ['area_id'=>0, 'name'=>'市区']);
//            $where = $this->model->district_id == 0 ? ['city_id'=>$this->model->city_id, 'district_id'=>0] : ['district_id'=>$this->model->district_id];
//            $this->assign(['provinces'=>OpenArea::getOpenProvinces(),
//                'citys'=>OpenArea::getOpenCitys($this->model->province_id),
//                'districts'=>$districts,
//                'hospitals'=>Hospital::getHospitals($where),
//                'departments'=>HospitalDepartment::getDepartments(['hospital_id'=>$this->model->hospital_id])
//            ]);
            return $this->fetch();
        }
    }

    public function del()
    {
        if($this->modelFactory->del()){
            return $this->jsonSuccess('更新成功');
        }
        return $this->jsonFail('删除失败');
    }

}