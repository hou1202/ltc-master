<?php
/**
 * 无限极分类
 * User: zhuziqiang
 * Date: 2016/12/21
 * Time: 10:01
 */

namespace app\common\model;


class Category{
    public function unlimitedForLevel($cate_list,$pid=0,$level=0){
        $arr=array();
        foreach($cate_list as $v){
            if($v['parentid']==$pid){
                $v['level']=$level+1;
                //$v['html']='<e class="catelist_bg"></e>'.str_repeat($html,$level);
                $arr[]=$v;
                $arr=array_merge($arr,self::unlimitedForLevel($cate_list,$v['id'],$level+1));
            }
        }
        return $arr;
    }

    public static function unlimitedForLevel2($cate_list,$html='--',$pid=0){
        static $arr = [];
        foreach($cate_list as $v){
            if($v['typeid']==$pid){
                $v['name']='|'.str_repeat($html,$v['level']);
                $arr[]=$v;
                static::unlimitedForLevel2($cate_list, $html, $v['typeid']);
            }
        }
        return $arr;
    }

    //传递一个子分类的id获取所有的父级分类
    public function getParentCatelist($cate_list,$id=0){
        $arr=array();
        foreach($cate_list as $v){
            if($v['id']==$id){
                $arr[]=$v;
                $arr=array_merge(self::getParentCatelist($cate_list,$v['parentid']),$arr);
            }
        }
        return $arr;
    }
    //传递一个父级分类的id获取所有的子级分类id，查看某一分类及其子分类下的所有文章时很有用,where (cate_id=1 or cate_id in (2,4,5))
    public function getChildCatelist($cate_list,$pid=0){
        $arr=array();
        foreach($cate_list as $v){
            if($v['parentid']==$pid){
                $arr[]=$v['id'];
                $arr=array_merge($arr,self::getChildCatelist($cate_list,$v['id']));
            }
        }
        return $arr;
    }
}