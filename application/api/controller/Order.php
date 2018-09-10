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
use app\admin\model\Order as OrderModel;
use app\admin\model\PatientFile;
use app\api\model\User;
use app\common\controller\ApiController;
use app\common\model\FileCheck;

class Order extends ApiController
{

    public static $sAddFailMsgs = [-1=>'该病人档案已经推送过', 0=>'推送失败'];

    protected static $sPermissionArr = [
        'commitdetail' => 15,
        'add' => 15,
        'sends' => 15,
        'receives' => 15,
        'diagnose' => 15,
        'completes' => 15,
        'search' => 15,
    ];

    protected static $sParamsArr = [
        'commitdetail' => ['token'=>2, 'file_id'=>2, 'shop_id'=>2],
        'add' => ['token'=>2, 'file_id'=>2, 'shop_id'=>2],
        'sends' => ['token'=>2, 'page'=>2],
        'receives' => ['token'=>2, 'page'=>2],
        'diagnose' => ['token'=>2, 'opinion'=>2, 'doctor_sign'=>2, 'file_id'=>2, 'no_del_banner'=>1],
        'completes' => ['token'=>2, 'page'=>2],
        'search' => ['token'=>2, 'search_name'=>2, 'order_type'=>2],
    ];

    public function commitDetail(){
        if($this->requestPostData['shop_id'] == $this->userId){
            return $this->jsonFail('自己不能给自己推送');
        }
        $order = ['order_number'=>OrderModel::makeOrderNumber(), 'c_time'=>date('Y-m-d H:i:s')];
        $doctor = User::getHZInfo($this->requestPostData['shop_id']);
        $file = PatientFile::getMakeOrderDetail($this->requestPostData['file_id']);
        return $this->jsonSuccess('获取成功', array_merge($order, $doctor, $file));
    }

    public function add(){
        $otherFiles = FileCheck::saveFiles(['other_files'=>['count'=>8,'size'=>1048576*5,'type'=>File::TYPE_IMG.'|'.File::TYPE_PDF.'|'.File::TYPE_WORD]]);
        if(is_string($otherFiles)){
            return $this->jsonFail($otherFiles);
        }
        $code = OrderModel::pushOrder($this->userId, $this->requestPostData['shop_id'], $this->requestPostData['file_id'], $otherFiles);
        if($code == 1){
            return $this->jsonSuccess('推送成功', ['file_id'=>$this->requestPostData['file_id']]);
        }else{
            return $this->jsonFail(static::$sAddFailMsgs[$code]);
        }
    }

    public function sends(){
        return $this->jsonSuccess('获取成功', OrderModel::getSends($this->userId, $this->requestPostData['page']));
    }

    public function receives(){
        return $this->jsonSuccess('获取成功', OrderModel::getReceives($this->userId, $this->requestPostData['page']));
    }

    public function diagnose(){
        //检查电子签名
        //重组cards_url
        $files = FileCheck::saveFiles(['banner'=>['count'=>4, 'type'=>0, 'size'=>1048576]]);
        if($files === false){

        }else if(is_string($files)){
            return $this->jsonFail($files);
        }else{
            isset($files['files']['banner']) && ($this->requestPostData['banner'] = $files['files']['banner']);
        }
        if(isset($this->requestPostData['no_del_banner'])){
            !empty($this->requestPostData['no_del_banner']) && ($this->requestPostData['banner'] = isset($this->requestPostData['banner']) ? $this->requestPostData['banner'].','.$this->requestPostData['no_del_banner'] : $this->requestPostData['no_del_banner']);
            unset($this->requestPostData['no_del_banner']);
        }
        if($this->requestPostData['doctor_sign'] != User::getSignByUserId($this->userId)){
            return $this->jsonFail('请检查您的电子签名');
        }
        $banner = isset($this->requestPostData['banner']) ? $this->requestPostData['banner'] : '';
        if(OrderModel::diagnoseV2($this->requestPostData['file_id'], $this->userId, $this->requestPostData['opinion'], $this->requestPostData['doctor_sign'], $banner)){
            if(isset($files['all_files'])){
                File::saveAllFiles($files['all_files'], 'p_patient_file', $this->requestPostData['file_id']);
            }
            return $this->jsonSuccess('提交成功');
        }else{
            return $this->jsonFail('该档案有变动，请刷新再试');
        }
    }

    public function completes(){
        return $this->jsonSuccess('获取成功', OrderModel::getCompletes($this->userId, $this->requestPostData['page']));
    }

    public function search(){
        return $this->jsonSuccess('获取成功', OrderModel::getSearchs($this->requestPostData['search_name'], $this->userId, $this->requestPostData['order_type']));
    }

}