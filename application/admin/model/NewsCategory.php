<?php
namespace app\admin\model;

use app\common\model\BaseAdminModel;
use think\Db;
use think\Model;

class NewsCategory extends BaseAdminModel
{

    public function getTitle()
    {
        return '文章分类';
    }

    public function index($where, $page, $limit, $order)
    {
        $offset = ($page-1)*$limit;
        return $this->field('category_id,category_name,sort,c_time')
            ->where($where)->limit($offset, $limit)->order($order)->select();
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

    /**
     * 获取所有分类条目
     * @return array
     */
    public static function getApiCategorys(){
        return Db::table(static::getTable())->field('category_id,category_name')->where('is_del=0')->order('sort asc,category_id desc')->select();
    }

    /**
     * 通过新闻分类名查找分类ID
     * @param $categoryName
     * @return int
     */
    public static function getCategoryIdByCategoryName($categoryName){
        return (int)Db::table(static::getTable())->where('category_name like :category_name')->bind(['category_name'=>$categoryName])->value('category_id');
    }

}