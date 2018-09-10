<?php
namespace app\index\controller;



use app\admin\model\OpenArea;
use app\api\model\Hospital;
use app\api\model\HospitalDepartment;
use app\common\controller\IndexController;
use think\Config;

class Common extends IndexController
{

    protected static $sPermissionArr = [
        'location'=>3,
        'area'=>3,
        'scan'=>1,
        'xieyi'=>1,
        'hospitals'=>3,
        'indexhospitals'=>3,
        'departments'=>3,
        'citys'=>3,
    ];

    protected static $sParamsArr = [
        'location'=>['time'=>2],
        'hospitals'=>['city_id'=>2],
        'indexhospitals'=>['city_id'=>2],
        'departments'=>['hospital_id'=>2],
        'citys'=>['province_id'=>2],
        'area'=>['type'=>2],
    ];


    public function location(){
        $key = '229c91c056ee6c6dc48bf13b183e0dfa';
        $ip = Config::get('app_debug') ? '36.5.145.236' : $this->request->ip();
        try {
            $content = file_get_contents('http://restapi.amap.com/v3/ip?ip=' . $ip . '&output=json&key=' . $key);
        }catch(\Exception $e){
            $content = '{province:\'\',city:\'\',adcode:10000,}';
        }
        return $this->jsonSuccess('获取成功', json_decode($content));
    }

    public function area(){
        $this->assign(OpenArea::getApiAreas());
        $this->assign(['type'=>$this->requestData['type']]);
        return $this->fetch();
    }

    public function hospitals(){
        if($this->requestData['city_id'] != 0) {
            $hospitals = Hospital::getHospitalsByAreaId($this->requestData['city_id']);
        }else{
            $hospitals = Hospital::getHospitals();
        }
        return $this->jsonSuccess('获取成功', $hospitals);
    }

    public function departments(){
        $hospitals = HospitalDepartment::getDepartmentsByHospitalId($this->requestData['hospital_id']);
        return $this->jsonSuccess('获取成功', $hospitals);
    }

    public function citys(){
        return $this->jsonSuccess('获取成功', OpenArea::getOpenCitys($this->requestData['province_id']));
    }

    public function indexHospitals(){
        return $this->jsonSuccess('获取成功', Hospital::getHospitalsByCityId($this->requestData['city_id']));
    }

    public function scan(){
        return $this->fetch();
    }

    public function xieyi(){
        $this->assign(['xieyi'=>\app\admin\model\Config::getAgreementContent()]);
        return $this->fetch();
    }

}