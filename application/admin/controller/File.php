<?php
namespace app\admin\controller;


use app\common\controller\AdminCheckLoginController;
use app\common\model\FileCheck;
use think\Db;

class File extends AdminCheckLoginController
{


    public function uploadImg()
    {
        /*$type = $this->request->post('type', 0);
        if($type==1){
            $result = FileCheck::saveAllFiles(['file' => 1], 20*1024*1024, 'mp4');
            if (is_string($result)) {
                return $this->jsonFail($result);
            }
            Db::table('p_config')->where('id=24')->update(['content'=>$result['file']]);
            return $this->jsonSuccess('上传成功', ['res'=>$result['file']]);
        }else {*/
            $result = FileCheck::saveAllFiles(['img' => 1]);
            if (is_string($result)) {
                return $this->jsonFail($result);
            }
            $data = $this->request->post();
            $result['img'] = 'http://'.str_replace('\\', '/', $result['img']);
            $data['url'] = $result['img'];
            $data['src'] = substr($result['img'], strpos($result['img'], '/uploads'));
            if ($this->modelFactory->add($data)) {
                return $this->jsonSuccess('上传成功', ['url' => $result['img'], 'id' => $this->model->id]);
            }
            return $this->jsonFail('上传失败');
       /* }*/
    }


    public function delImg(){
        if($this->modelFactory->del()){
            return $this->jsonSuccess('删除成功');
        }
        return $this->jsonFail('删除失败');
    }



}