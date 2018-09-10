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
use app\admin\model\Order;
use app\api\model\Hospital;
use app\api\model\User;
use app\common\controller\AdminCheckLoginController;
use app\common\model\CommonUtils;

class OrderZd extends AdminCheckLoginController
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
            $hospitalId = $this->request->post('hospital_id', '');
            $where = '';
            !empty($name) && $where = CommonUtils::concatWhere($where, 'u.real_name like \'%'.$name.'%\'');
            if(!empty($searhDate)){
                $where = CommonUtils::concatWhere($where, 'z.zd_month like \''.$searhDate.'\'');
            }
            if($hospitalId!==''){
                $where = CommonUtils::concatWhere($where, 'u.hospital_id='.$hospitalId);
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
            $assign['province_id'] = $this->request->get('province_id','');
            $assign['city_id'] = $this->request->get('city_id','');
            $assign['district_id'] = $this->request->get('district_id','');
            $assign['hospital_id'] = $this->request->get('hospital_id', '');
            $assign['citys'] = $assign['province_id'] !== '' ? OpenArea::getOpenCitys($assign['province_id']) : [];
            $districts = $assign['city_id'] !== '' ? OpenArea::getOpenDistricts($assign['city_id']) : [];
            $assign['hospitals'] = $assign['district_id'] !== '' ? Hospital::getHospitalsByCityIdAndDistrictId($assign['city_id'], $assign['district_id']) : [];
            if(!empty($districts))
                array_unshift($districts, ['area_id'=>0, 'name'=>'市区']);
            $assign['districts'] = $districts;
            $assign['provinces'] = OpenArea::getOpenProvinces();
            $this->assign($assign);
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
            //获取该账单的医生信息
            $assign['user'] = User::getUserBaseInfo($this->model->user_id);
            $assign['orders'] = Order::getOrdersByZdMonthAndUserId($this->model->zd_month, $this->model->user_id);
            $this->assign($assign);
            return $this->fetch();
        }
    }

}