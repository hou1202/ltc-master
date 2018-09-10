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


use app\admin\model\File;
use app\admin\model\PatientFile as PatientFileModel;
use app\common\controller\ApiController;
use app\common\model\FileCheck;

class PatientFile extends ApiController
{

    protected static $sPermissionArr = [
        'add' => 15,
        'files' => 15,
        'search' => 15,
        'detail' => 15,
        'pushdetail' => 15,
        'del' => 15,
    ];

    protected static $sParamsArr = [
        'add' => ['token'=>2, 'hospital_name'=>2, 'patient_name'=>2, 'sex'=>2, 'age'=>2, 'unit'=>2, 'mobile'=>2, 'section_number'=>2, 'section_count'=>2,
            'medical_history'=>2, 'pathology'=>2, 'first_doctor'=>2],
        'files' => ['token'=>2, 'status'=>2, 'page'=>2],
        'search' => ['token'=>2, 'search_name'=>2],
        'detail' => ['token'=>2, 'file_id'=>2],
        'pushdetail' => ['token'=>2, 'file_id'=>2],
        'del' => ['token'=>2, 'file_id'=>2],
    ];


    public function add(){
        $allFiles = [];
        $files = FileCheck::saveFiles(['banner'=>['count'=>4, 'size'=>1048576, 'type'=>File::TYPE_IMG]]);
        if(is_string($files)){
            return $this->jsonFail($files);
        }elseif(is_array($files)){
            if(isset($files['files']['banner'])) {
                $this->requestPostData['banner'] = $files['files']['banner'];
                $allFiles = $files['all_files'];
            }
        }
        $this->requestPostData['user_id'] = $this->userId;
        $this->requestPostData['hospital_id'] = $this->userInfo['hospital_id'];
        PatientFileModel::apiAdd($this->requestPostData, $allFiles);
        return $this->jsonSuccess('添加成功');
    }

    public function files(){
        $where['user_id'] = $this->userId;
        if($this->requestPostData['status'] == 0) {
            $where['status'] = 0;
        }elseif($this->requestPostData['status'] == 1){
            $where['status'] = ['>', 0];
        }
        return $this->jsonSuccess('获取成功', PatientFileModel::getApiFiles($where, $this->requestPostData['page'], 10));
    }

    public function search(){
        return $this->jsonSuccess('获取成功', PatientFileModel::getApiSearchFiles($this->userId, $this->requestPostData['search_name']));
    }

    public function detail(){
        return $this->jsonSuccess('获取成功', PatientFileModel::getApiDetail($this->requestPostData['file_id'], $this->userId));
    }

    public function pushDetail(){
        return $this->jsonSuccess('获取成功', PatientFileModel::getPushDetail($this->requestPostData['file_id']));
    }

    public function del(){
        PatientFileModel::apiDel($this->requestPostData['file_id'], $this->userId);
        return $this->jsonSuccess('删除成功');
    }


}