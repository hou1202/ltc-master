<?php
// +----------------------------------------------------------------------
// | KingTP
// +----------------------------------------------------------------------
// | 2018/1/17 15:12
// +----------------------------------------------------------------------
// | **基于ThinkPHP 5.0.14 + LayUI2.2.5封装**
// +----------------------------------------------------------------------
// | Author: 晓晓攻城师 <邮箱：1228676735@qq.com><博客：http://blog.csdn.net/qq_26245325>
// +----------------------------------------------------------------------

namespace app\api\controller;


use app\api\model\Collect;
use app\api\model\Hospital;
use app\api\model\HospitalDepartment;
use app\api\model\User as UserModel;
use app\common\controller\ApiController;

class Doctor extends ApiController
{

    protected static $sPermissionArr = [
        'param' => 3,
        'doctors' => 7,
        'detail' => 7,
        'collect' => 7,
        'cancelcollect' => 7,
        'search' => 7,
        'collects' => 7,
    ];

    protected static $sParamsArr = [
        'param' => ['city_id'=>2],
        'doctors' => ['department_id'=>2, 'token'=>2, 'page'=>2],
        'detail' => ['shop_id'=>2, 'token'=>2],
        'collect' => ['shop_id'=>2, 'token'=>2],
        'cancelcollect' => ['shop_id'=>2, 'token'=>2],
        'search' => ['search_name'=>2, 'token'=>2],
        'collects' => ['token'=>2, 'page'=>2],
    ];

    public function param(){
        $param = ['hospital'=>['hospital_name'=>'无医院', 'hospital_id'=>0], 'departments'=>[]];
        $hospital = Hospital::getHospitalByAreaId($this->requestPostData['city_id']);
        if($hospital!=null) {
            $param['hospital'] = $hospital;
            $param['departments'] = HospitalDepartment::getDepartments(['hospital_id' => $hospital['hospital_id']]);
        }
        return $this->jsonSuccess('获取成功', $param);
    }

    public function doctors(){
        return $this->jsonSuccess('获取成功', UserModel::getDoctors(['u.department_id'=>$this->requestPostData['department_id']], $this->userId, $this->requestPostData['page']));
    }

    public function search(){
        return $this->jsonSuccess('获取成功', UserModel::getDoctorsBySearchName($this->requestPostData['search_name'], $this->userId));
    }

    public function detail(){
        return $this->jsonSuccess('获取成功', UserModel::getDoctor($this->requestPostData['shop_id'], $this->userId));
    }

    public function collect(){
        if($this->userId == $this->requestPostData['shop_id']){
            return $this->jsonFail('不能收藏自己');
        }
        if(Collect::isCollect($this->userId, $this->requestPostData['shop_id']) == 1){
            return $this->jsonFail('你已经收藏过了');
        }
        Collect::collect($this->userId, $this->requestPostData['shop_id']);
        return $this->jsonSuccess('收藏成功');
    }

    public function cancelCollect(){
        Collect::cancleCollect($this->userId, $this->requestPostData['shop_id']);
        return $this->jsonSuccess('取消收藏成功');
    }

    public function collects(){
        return $this->jsonSuccess('获取成功', Collect::collects($this->userId, $this->requestPostData['page']));
    }

}