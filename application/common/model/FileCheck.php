<?php
/**
 * Project: 文件保存
 * User: Zhu Ziqiang
 * Date: 2017/3/30
 * Time: 11:08
 */

namespace app\common\model;


use app\admin\model\File;
use think\Config;
use think\Log;
use think\Request;

class FileCheck
{
    /**
     * 检查上传文件
     * @param array $limit 限制多张上传的个数 以字段为key 数目为value
     * @param int $size 限制的文件大小 目前全部统一设置
     * @param string $ext 限制的格式  目前全部统一设置
     * @return array|bool|string  false=>说明没有文件  字符串=>错误信息  数组=>已经上传成功的信息
     */
    public static function saveAllFiles($limit=[], $size=1048576, $ext='gif,jpg,jpeg,bmp,png'){
        $domain = Config::get('upload_file_domain');
        $files = Request::instance()->file();
        if(empty($files)){
            return false;
        }
        $data = [];
        foreach ($files as $k => $v) {
            if(is_array($v)) {
                $count = count($v);
                if ($count>0 && $count <= $limit[$k]) {
                    foreach ($v as $f) {
                        $imgFile = $f->validate(['size' => $size, 'ext' => $ext])->move(ROOT_PATH . 'public' . DS . 'uploads');
                        if ($imgFile == false) {
                            return $f->getError();

                        }
                        $data[$k][] = $domain . '/uploads/' . $imgFile->getSaveName();
                    }
                } else {
                    return '文件不能超过' . $limit[$k] . '个';
                }
            }else{
                $imgFile = $v->validate(['size' => $size, 'ext' => $ext])->move(ROOT_PATH . 'public' . DS . 'uploads');
                if ($imgFile == false) {
                    return $v->getError();
                }
                $data[$k] = $domain . '/uploads/' . $imgFile->getSaveName();
            }
        }
        return $data;
    }

    /**
     * 保存图片缩略图
     * @return array
     */
    public static function saveFileThump(){
        $domain = Config::get('upload_file_domain');
        $img = Request::instance()->file('img');
        $imgFile = $img->validate(['ext'=>'gif,jpg,jpeg,bmp,png', 'size'=>10485760])->move(ROOT_PATH . 'public' . DS . 'uploads');
        if($imgFile==false){
            return ['code'=>0, 'msg'=>$img->getError()];
        }
        try {
            $image = \think\Image::open($imgFile->getPathname());
            $fileUrl = DS . 'uploads' . DS . date('Ymd') . DS . md5($imgFile->getPathname()).'.jpg';
            $image->thumb(500, 500)->save(ROOT_PATH . 'public' . $fileUrl);
            return ['code'=>1,'img'=>$domain . $fileUrl];
        }catch(\Exception $e){
            return ['code'=>0, 'msg'=>$e->getMessage()];
        }
    }

    /**
     *
     * @param $limit array  ['poster'=>['count'=>1,'size'=>1048576,'type'=>1]]
     * @return array|bool|string
     */
    public static function saveFiles($limit){
        $domain = Config::get('upload_file_domain');
        $files = Request::instance()->file();

        if(empty($files)){
            return false;
        }
        $data = ['files'=>[], 'all_files'=>[]];
        $path = ROOT_PATH . 'public' . DS . 'uploads';
        foreach ($files as $k => $v) {
            if(!isset($limit[$k])){
                return '非法上传文件';
            }
            if(is_array($v)) {
                $count = count($v);
                if ($count>0 && $count <= $limit[$k]['count']) {
                    $types = explode('|', $limit[$k]['type']);
                    $exts = [];
                    foreach($types as $type){
                        $exts[] = File::$sExts[$type];
                    }
                    $ext = implode(',', $exts);
                    $urls = [];
                    foreach ($v as $f) {
                        $imgFile = $f->validate(['size' => $limit[$k]['size'], 'ext' => $ext])->move($path);
                        if ($imgFile == false) {
                            return $f->getError();
                        }
                        $src = '/uploads/' . $imgFile->getSaveName();
                        $url = $domain . $src;
                        $urls[] = $url;
                        $data['all_files'][] = ['src'=>$src, 'url'=>$url, 'action'=>$k];
                    }
                    $data['files'][$k] = implode(',', $urls);
                } else {
                    return '文件不能超过' . $limit[$k]['count'] . '个';
                }
            }else{
                $types = explode('|', $limit[$k]['type']);
                $exts = [];
                foreach($types as $type){
                    $exts[] = File::$sExts[$type];
                }
                $ext = implode(',', $exts);
                $imgFile = $v->validate(['size' => $limit[$k]['size'], 'ext' => $ext])->move(ROOT_PATH . 'public' . DS . 'uploads');
                if ($imgFile == false) {
                    return $v->getError();
                }
                $src = '/uploads/' . $imgFile->getSaveName();
                $url = $domain . $src;
                $data['files'][$k] = $url;
                $data['all_files'][] = ['src'=>$src, 'url'=>$url, 'action'=>$k];
            }
        }
        return $data;
    }

}