<?php
namespace app\index\controller;



use app\admin\model\OpenArea;
use app\api\model\Collect;
use app\api\model\User as UserModel;
use app\api\model\Hospital;
use app\api\model\HospitalDepartment;
use app\api\model\User;
use app\common\controller\IndexController;

class Doctor extends IndexController
{

    protected static $sPermissionArr = [
        'index'=>15,
        'doctors'=>7,
        'detail'=>7,
        'pushdoctors'=>7,
    ];

    protected static $sParamsArr = [
        'index'=>['page'=>1, 'doctor_name'=>1, 'province_id'=>1, 'city_id'=>1, 'hospital_id'=>1, 'department_id'=>1],
        'doctors'=>['page'=>1, 'doctor_name'=>1, 'province_id'=>1, 'city_id'=>1, 'hospital_id'=>1, 'department_id'=>1],
        'detail'=>['shop_id'=>2, 'page'=>1, 'doctor_name'=>1, 'province_id'=>1, 'city_id'=>1, 'hospital_id'=>1, 'department_id'=>1],
        'pushdoctors'=>['hospital_id'=>2]
    ];


    public function index(){
        $assign['page'] = (int)$this->getRequestData('page', 1);
        $assign['doctor_name'] = $this->getRequestData('doctor_name', '');
        $assign['province_id'] = (int)$this->getRequestData('province_id', 0);
        $assign['city_id'] = (int)$this->getRequestData('city_id', 0);
        $assign['hospital_id'] = (int)$this->getRequestData('hospital_id', 0);
        $assign['department_id'] = (int)$this->getRequestData('department_id', 0);
        $assign['provinces'] = OpenArea::getOpenProvinces();
        if($assign['province_id'] == 0){
            $defaultCityInfo = Collect::getCollectHospitalId($this->userId);
        }
        $assign['province_id'] = $assign['province_id']>0? $assign['province_id'] :
            (isset($defaultCityInfo['province_id'])&&$defaultCityInfo['province_id']>0 ? $defaultCityInfo['province_id'] :
                (isset($assign['provinces'][0]['area_id'])?$assign['provinces'][0]['area_id']:0));
        $assign['citys'] = $assign['province_id']>0 ? OpenArea::getOpenCitys($assign['province_id']) : [];
        $assign['city_id'] = $assign['city_id']>0? $assign['city_id'] :
            (isset($defaultCityInfo['city_id'])&&$defaultCityInfo['city_id']>0 ? $defaultCityInfo['city_id'] :
                (isset($assign['citys'][0]['area_id'])?$assign['citys'][0]['area_id']:0));
        $assign['hospitals'] = $assign['city_id']>0 ? Hospital::getHospitalsByCityId($assign['city_id']) : [];
        //$assign['hospital_id'] = $assign['hospital_id']>0? $assign['hospital_id'] : (isset($assign['hospitals'][0]['hospital_id'])?$assign['hospitals'][0]['hospital_id']:0);
        $assign['departments'] = $assign['hospital_id']>0 ? HospitalDepartment::getDepartmentsByHospitalId($assign['hospital_id']) : [];
        array_unshift($assign['departments'], ['department_id'=>0, 'department_name'=>'全部']);
        $this->assign($assign);
        return $this->fetch();
    }

    public function doctors(){
        $where = [];
        $page = $this->getRequestData('page', 1);
        $doctorName = $this->getRequestData('doctor_name', '');
        $cityId = (int)$this->getRequestData('city_id', 0);
        $hospitalId = (int)$this->getRequestData('hospital_id', 0);
        $departmentId = (int)$this->getRequestData('department_id', 0);
        $cityId>0 && $where['u.city_id'] = $cityId;
        $hospitalId>0 && $where['u.hospital_id'] = $hospitalId;
        $departmentId>0 && $where['u.department_id'] = $departmentId;
        !empty($doctorName) && $where['u.real_name'] = ['like', '%'.$doctorName.'%'];
        $totalCount = UserModel::getIndexDoctorCount($where, $this->userId);
        $totalPage = ceil($totalCount/6);
        $page = $page>0 ? ($page>$totalPage ? $totalPage : $page) : 1;
        $files = $totalCount>0 ? UserModel::getIndexDoctors($where, $this->userId, $page) : [];
        return $this->jsonSuccess('获取成功', ['files'=>$files, 'pages'=>['totalPage'=>$totalPage, 'totalCount'=>$totalCount, 'page'=>$page]]);
    }

    public function pushDoctors(){
        return $this->jsonSuccess('获取成功', UserModel::getIndexPushDoctors($this->requestData['hospital_id'], $this->userId));
    }

    public function detail(){
        $shopId = $this->requestData['shop_id'];
        unset($this->requestData['shop_id']);
        $param = '';
        foreach($this->requestData as $k=>$v){
            $param .= $k.'='.$v.'&';
        }
        $shop = User::getDoctor($shopId, $this->userId);
        $this->assign(['param'=>$param, 'shop'=>$shop]);
        return $this->fetch();
    }



}