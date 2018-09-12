<?php
namespace app\admin\model;

use app\common\model\BaseAdminModel;
use think\Db;
use think\Model;

class Banner extends BaseAdminModel
{

    public static $sLogTableName = 'banner_log';

    protected $imageSize = [
        'poster'=>['name'=>'展示图', 'width'=>375, 'height'=>170, 'limit'=>1]
    ];

    public function getTitle()
    {
        return 'Banner图';
    }

    public function index($where, $page, $limit, $order)
    {
        $offset = ($page-1)*$limit;
        return $this->field('id,title,poster,status')
            ->where($where)
            ->limit($offset, $limit)
            ->order($order)
            ->select();
    }

    public function totalCount($where)
    {
        return $this->where($where)->count();
    }

    public function add($data)
    {
        $this->save($data);
        return true;
    }

    public function edit($data)
    {
        $this->save($data);
        return true;
    }

    public function del()
    {
        $this->save(['is_del'=>time()]);
        return true;
    }

}