<?php
namespace app\index\controller;


use app\common\controller\IndexController;
use app\common\model\FileCheck;
use think\Config;
use think\Db;

class File extends IndexController
{

    protected static $sPermissionArr = [
        'uploadimg'=>3,
        'delimg'=>3,
        'base64'=>3,
    ];

    protected static $sParamsArr = [
        'uploadimg'=>['from'=>2, 'action'=>2, 'typeid'=>2],
        'delimg'=>['id'=>2],
        'base64'=>['img'=>2],
    ];


    public function uploadImg()
    {
        if($this->requestData['from'] == 'p_patient_file' && $this->requestData['action'] == 'other_files'){
            $result = FileCheck::saveAllFiles(['img' => 1], 1048576, $ext='gif,jpg,jpeg,bmp,png,doc,docx,pdf');
        }else {
            $result = FileCheck::saveAllFiles(['img' => 1]);
        }
        if (is_string($result)) {
            return $this->jsonFail($result);
        }
        $data = $this->requestData;
        $result['img'] = str_replace('\\', '/', $result['img']);
        $data['url'] = $result['img'];
        $data['src'] = substr($result['img'], strpos($result['img'], '/uploads'));
        $file = new \app\admin\model\File();
        if ($file->add($data)) {
            return $this->jsonSuccess('上传成功', ['url' => $result['img'], 'id' => $file->id]);
        }
        return $this->jsonFail('上传失败');
    }


    public function delImg(){
        $file = $this->validate->getModel();
        if($file->del()){
            return $this->jsonSuccess('删除成功');
        }
        return $this->jsonFail('删除失败');
    }

    public function base64(){
        $filePath = ROOT_PATH . 'public' . DS . 'uploads' . DS . date('Ymd');
        $fileName = md5(microtime(true)) . '.'.$this->validate->getData('type');
        if (!file_exists($filePath)) {
            mkdir($filePath, 0777, true);
        }
        $filePath = $filePath . DS . $fileName;
        $savePath = 'uploads/' . date('Ymd') . '/' . $fileName;
        $domain = Config::get('upload_file_domain');
        if(file_put_contents($filePath, base64_decode($this->validate->getData('content')))){
            $data['url'] = $domain.'/'.$savePath;
            $data['src'] = '/'.$savePath;
            //$data['typeid'] = $this->userId;
            $data['from'] = 'p_patient_file';
            $data['action'] = 'scan_imgs';
            $file = new \app\admin\model\File();
            if ($file->add($data)) {
                return $this->jsonSuccess('保存成功', ['src'=>$data['url'], 'id'=>$file->id]);
            }
        }
        return $this->jsonFail('保存失败');
    }

}