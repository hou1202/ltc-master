<?php
namespace app\admin\controller;


use app\admin\model\OpenArea;
use app\admin\model\OrderZd;
use app\api\model\Hospital;
use app\api\model\HospitalDepartment;
use app\common\controller\BaseController;
use app\common\model\CommonUtils;
use think\Db;
use think\Response;

class JsonType extends BaseController
{
    const FAIL_CODE = 0;      //失败code
    const SUCCESS_CODE = 1;   //成功code
    const SESSION_CODE = -1;  //登录信息失效
    const ILLEGAL_CODE = -2;  //没有权限

    public function getCitys(){
        $provinceId = (int)$this->request->post('province_id');
        $citys = OpenArea::getOpenCitys($provinceId);
        return $this->jsonSuccess('', $citys);
    }

    public function getDistricts(){
        $cityId = (int)$this->request->post('city_id');
        $districts = OpenArea::getOpenDistricts($cityId);
        return $this->jsonSuccess('', $districts);
    }

    public function getHospitals(){
        $districtId = (int)$this->request->post('district_id');
        $cityId = (int)$this->request->post('city_id');
        $hospitals = Hospital::getHospitalsByCityIdAndDistrictId($cityId, $districtId);
        return $this->jsonSuccess('', $hospitals);
    }

    public function getDepartments(){
        $hospitalId = (int)$this->request->post('hospital_id');
        $departments = HospitalDepartment::getDepartmentsByHospitalId($hospitalId);
        return $this->jsonSuccess('', $departments);
    }

    public function getNotOpenCitys(){
        $provinceId = (int)$this->request->post('province_id');
        $citys = OpenArea::getNotOpenCitys($provinceId);
        return $this->jsonSuccess('', $citys);
    }

    public function getOrderZdPrice(){
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
        return $this->jsonSuccess('', OrderZd::getOrderZdPrice($where));
    }

    /**
     * 失败  json类型响应数据
     * @param $msg
     * @param array $data
     * @return Response|\think\response\Json|\think\response\Jsonp|\think\response\Redirect|\think\response\View|\think\response\Xml
     */
    public function jsonFail($msg, $data=[]){
        return $this->json(['code'=>self::FAIL_CODE, 'msg'=>$msg, 'data'=>$data]);
    }

    /**
     * 成功  json类型响应数据
     * @param $msg
     * @param array $data
     * @return Response|\think\response\Json|\think\response\Jsonp|\think\response\Redirect|\think\response\View|\think\response\Xml
     */
    public function jsonSuccess($msg, $data=[]){
        return $this->json(['code'=>self::SUCCESS_CODE, 'msg'=>$msg, 'data'=>$data]);
    }

}