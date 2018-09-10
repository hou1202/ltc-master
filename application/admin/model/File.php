<?php
namespace app\admin\model;

use app\common\model\BaseAdminModel;
use app\common\model\CommonUtils;
use think\Db;
use think\Loader;

class File extends BaseAdminModel
{
    const TYPE_IMG = 0;
    const TYPE_WORD = 1;
    const TYPE_EXCEL = 2;
    const TYPE_PDF = 3;

    public static $sExts = [
        self::TYPE_IMG =>'gif,jpg,jpeg,bmp,png',
        self::TYPE_WORD =>'doc,docx',
        self::TYPE_EXCEL =>'xls',
        self::TYPE_PDF =>'pdf',
    ];

    function index($where, $page, $limit, $order)
    {
    }

    function totalCount($where)
    {
    }

    function add($data)
    {
        return $this->save($data)>0;
    }

    function edit($data)
    {
        return $this->save($data);
    }

    function del()
    {
        if($this->typeid>0){
            $model = Loader::model(CommonUtils::parseTableName($this->from),'model',false,'admin');
            $instance = $model->get($this->typeid);
            if($instance!=null) {
                $data = $instance[$this->action];
                if (!empty($data)) {
                    $datas = explode(',', $data);
                    foreach ($datas as $k => $v) {
                        if ($v == $this->url) {
                            unset($datas[$k]);
                        }
                    }
                    $newData = empty($datas) ? '' : implode(',', $datas);
                    $instance->save([$this->action => $newData]);
                }
            }
        }
        $this->save(['is_del'=>time()]);
        return true;
    }

    function getTitle()
    {
        return '文件';
    }

    /**
     * 添加文件，保存其数据
     * @param $allFiles
     * @param $from
     * @param $typeId
     * @return bool
     */
    public static function saveAllFiles($allFiles, $from, $typeId){
        if(empty($allFiles)) return false;
        foreach ($allFiles as $k => $v) {
            $allFiles[$k]['typeid'] = $typeId;
            $allFiles[$k]['from'] = $from;
        }
        Db::table(static::getTable())->insertAll($allFiles);
        return true;
    }

    public static function updateAllFiles($allFiles, $typeId){
        Db::table(static::getTable())->where(['url'=>['in', $allFiles]])->update(['typeid'=>$typeId]);
    }

}