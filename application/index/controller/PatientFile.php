<?php
namespace app\index\controller;


use app\admin\model\File as FileModel;
use app\admin\model\PatientFile as PatientFileModel;
use app\common\controller\IndexController;
use app\common\utils\ToolUtils;

class PatientFile extends IndexController
{

    protected static $sPermissionArr = [
        'index'=>15,
        'files'=>7,
        'notpushs'=>7,
        'del'=>7,
        'completedetail'=>7,
        'add'=>15,
        'save'=>15,
        'edit'=>15,
        'update'=>15,
        'pdf'=>15,
        'updatebanner'=>15,
    ];

    protected static $sParamsArr = [
        'index'=>['page'=>1, 'patient_name'=>1, 'hospital_id'=>1, 'status'=>1],
        'files'=>['page'=>1, 'patient_name'=>1, 'hospital_id'=>1, 'status'=>1],
        'notpushs'=>['time'=>2],
        'del'=>['file_id'=>2],
        'pdf'=>['file_id'=>2],
        'completedetail'=>['file_id'=>2, 'param'=>2],
        'add'=>['param'=>2],
        'edit'=>['param'=>1, 'file_id'=>2],
        'save'=>['patient_name'=>2, 'sex'=>2, 'age'=>2, 'unit'=>2, 'hospital_name'=>2, 'section_number'=>2, 'section_count'=>2,
            'medical_history'=>2, 'pathology'=>2, 'banner'=>2, 'scan_imgs'=>2, 'other_files'=>2, 'first_doctor'=>2, 'mobile'=>2
        ],
        'update'=>['file_id'=>2, 'patient_name'=>2, 'sex'=>2, 'age'=>2, 'unit'=>2, 'hospital_name'=>2, 'section_number'=>2, 'section_count'=>2,
            'medical_history'=>2, 'pathology'=>2, 'banner'=>2, 'scan_imgs'=>2, 'other_files'=>2, 'first_doctor'=>2, 'mobile'=>2
        ],
        'updatebanner'=>['file_id'=>2, 'banner'=>2]
    ];

    public function index(){
        $assign['page'] = (int)$this->getRequestData('page', 1);
        $assign['patient_name'] = $this->getRequestData('patient_name', '');
        $assign['hospital_id'] = (int)$this->getRequestData('hospital_id', 0);
        $assign['status'] = (int)$this->getRequestData('status', -1);
        $assign['hospitals'] = PatientFileModel::getFZHospitals($this->userId);
        $this->assign($assign);
        return $this->fetch();
    }

    public function files(){
        $where = ['f.user_id'=>$this->userId, 'f.is_del'=>0];
        $page = $this->getRequestData('page', 1);
        $patientName = $this->getRequestData('patient_name', '');
        $hospitalId = (int)$this->getRequestData('hospital_id', 0);
        $status = (int)$this->getRequestData('status', -1);
        $status>-1 && $where['f.status'] = $status;
        !empty($patientName) && $where['f.patient_name'] = ['like', '%'.$patientName.'%'];
        $hospitalId>0 && $where['u.hospital_id'] = $hospitalId;
        $totalCount = PatientFileModel::getIndexCount($where);
        $totalPage = ceil($totalCount/8);
        $page = $page>0 ? ($totalPage==0? 1 : ($page>$totalPage ? $totalPage : $page)) : 1;
        $files = $totalCount>0 ? PatientFileModel::getIndexFiles($where, $page) : [];
        return $this->jsonSuccess('获取成功', ['files'=>$files, 'pages'=>['totalPage'=>$totalPage, 'totalCount'=>$totalCount, 'page'=>$page]]);
    }

    public function notPushs(){
        return $this->jsonSuccess('获取成功', ['files'=>PatientFileModel::getIndexNotPushFiles($this->userId)]);
    }

    public function del(){
        PatientFileModel::apiDel($this->requestData['file_id'], $this->userId);
        return $this->jsonSuccess('删除成功');
    }

    public function completeDetail(){
        $assign['file'] = PatientFileModel::getIndexDetail($this->requestData['file_id'], $this->userId);
        $assign['param'] = $this->requestData['param'];
        $this->assign($assign);
        return $this->fetch();
    }

    public function add(){
        $this->assign(['param'=>$this->requestData['param']]);
        return $this->fetch();
    }


    public function save(){
        $this->requestData['user_id'] = $this->userId;
        $this->requestData['hospital_id'] = $this->userInfo['hospital_id'];
        $fileId = PatientFileModel::indexAdd($this->requestData);
        return $this->jsonSuccess('添加成功', ['file_id'=>$fileId]);
    }

    public function edit(){
        $param = $this->getRequestData('param', '');
        $model = PatientFileModel::getDetail($this->requestData['file_id'], $this->userId);
        if($model == null){
            $this->debugInfo('未知错误');
        }
        $file = new FileModel();
        $banners = empty($model['banner']) ? [] : $file->where(['url'=>['in', $model['banner']]])->select();
        $otherFiles = empty($model['other_files']) ? [] : $file->where(['url'=>['in', $model['other_files']]])->select();
        $scanImgs = empty($model['scan_imgs']) ? [] : $file->where(['url'=>['in', $model['scan_imgs']]])->select();
        $this->assign(['param'=>$param, 'model'=>$model, 'banners'=>$banners, 'scan_imgs'=>$scanImgs, 'other_files'=>$otherFiles]);
        return $this->fetch();
    }

    public function update(){
        if(PatientFileModel::indexUpdate($this->requestData, $this->userId)){
            return $this->jsonSuccess('修改成功');
        }else{
            return $this->jsonFail('该档案状态已经变化');
        }
    }

    public function updateBanner(){
        if(PatientFileModel::indexPushUpdate($this->requestData, $this->userId)){
            return $this->jsonSuccess('修改成功');
        }else{
            return $this->jsonFail('该档案状态已经变化');
        }
    }

    public function pdf(){
        $file = PatientFileModel::get($this->requestData['file_id']);
        if($file==null){
            $this->debugInfo('非法请求');
        }
        if($file['user_id']!=$this->userId && $file['shop_id']!=$this->userId){
            $this->debugInfo('非法请求');
        }
        return ToolUtils::makePdfPatientFile($file);
    }





}