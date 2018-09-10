<?php
namespace app\api\controller;


use app\admin\model\OpenArea;
use app\api\model\Hospital;
use app\api\model\HospitalDepartment;
use app\common\controller\ApiController;

class Common extends ApiController
{

    protected static $sPermissionArr = [
        'areas' => 1,
        'hospitals' => 3,
        'departments' => 3,
    ];

    protected static $sParamsArr = [
        'hospitals' => ['city_id'=>2],
        'departments' => ['hospital_id'=>2],
    ];

    public function areas(){
        return $this->jsonSuccess('获取成功', OpenArea::getApiAreas());
    }

    public function hospitals(){
        if($this->requestPostData['city_id'] != 0) {
            $hospitals = Hospital::getHospitalsByAreaId($this->requestPostData['city_id']);
        }else{
            $hospitals = Hospital::getHospitals();
        }
        return $this->jsonSuccess('获取成功', $hospitals);
    }

    public function departments(){
        $hospitals = HospitalDepartment::getDepartmentsByHospitalId($this->requestPostData['hospital_id']);
        return $this->jsonSuccess('获取成功', $hospitals);
    }




}
