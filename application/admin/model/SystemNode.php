<?php
// +----------------------------------------------------------------------
// | snake
// +----------------------------------------------------------------------
// | Copyright (c) 2016~2022 http://baiyf.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: NickBai <1902822973@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\model;

use think\Db;
use think\Model;

class SystemNode extends Model
{
    public static $tableName = 'p_system_node';

    public static function getRoleNodes($rule){
        $nodeData = Db::table(static::$tableName)->field('id,title,href,is_menu,typeid,icon')->where(['id'=>['in', $rule]])->order('id ASC')->select();
        $menusOne = [];
        $menusTwo = [];
        $rules = [];
        foreach($nodeData as $v){
            if($v['is_menu'] == 2){
                if($v['typeid'] == 0){
                    $menusOne[] = ['id'=>$v['id'], 'title'=>$v['title'], 'icon'=>$v['icon'], 'children'=>[]];
                }else{
                    $menusTwo[$v['typeid']][] = ['id'=>$v['id'], 'title'=>$v['title'], 'icon'=>$v['icon'], 'href'=>$v['href']];
                }
            }
            if($v['typeid'] != 0){
                $rules[$v['href']] = 1;
            }
        }
        foreach($menusOne as $k => $v){
            if(isset($menusTwo[$v['id']])){
                $menusOne[$k]['children'] = $menusTwo[$v['id']];
            }
        }
        return ['rules'=>$rules, 'menus'=>$menusOne];
    }

    public function getUserNodes($ruleId){
        $rule = SystemRole::get(['id'=>$ruleId]);
        $where = empty($rule) || empty($rule->rule) ? [] : ['id'=>['in', $rule->rule]];
        $nodeData = $this->getNodesByWhere($where);
        $menusOne = [];
        $menusTwo = [];
        $rules = [];
        foreach($nodeData as $v){
            if($v['is_menu'] == 2){
                if($v['typeid'] == 0){
                    $menusOne[] = ['id'=>$v['id'], 'title'=>$v['title'], 'icon'=>$v['icon'], 'children'=>[]];
                }else{
                    $menusTwo[$v['typeid']][] = ['id'=>$v['id'], 'title'=>$v['title'], 'icon'=>$v['icon'], 'href'=>$v['href']];
                }
            }
            if($v['typeid'] != 0){
                $rules[$v['href']] = 1;
            }
        }
        foreach($menusOne as $k => $v){
            if(isset($menusTwo[$v['id']])){
                $menusOne[$k]['children'] = $menusTwo[$v['id']];
            }
        }
        return ['rules'=>$rules, 'menus'=>$menusOne];
    }

    public function getNodesByWhere($where=[]){
        return Db::table(static::$tableName)->field('id,title,href,is_menu,typeid,icon')->where($where)->order('id ASC')->select();
    }

    /**
     * 获取节点数据
     */
    public function getNodeInfo($rule)
    {
        $result = $this->field('id,title,typeid')->select();
        $str = "";
        if(!empty($rule)){
            $rule = explode(',', $rule);
        }
        foreach($result as $key=>$vo){
            $str .= '{ "id": "' . $vo['id'] . '", "pId":"' . $vo['typeid'] . '", "name":"' . $vo['title'].'"';
            if(!empty($rule) && in_array($vo['id'], $rule)){
                $str .= ' ,"checked":1';
            }
            $str .= '},';
        }
        return "[" . substr($str, 0, -1) . "]";
    }

    public function getNodes(){
        $nodeData = $this->getNodesByWhere();
        $menusOne = [];
        $menusTwo = [];
        foreach($nodeData as $v){
            if ($v['typeid'] == 0) {
                $menusOne[] = ['id' => $v['id'], 'title' => $v['title'], 'icon' => $v['icon'], 'href'=>'#', 'is_menu'=>$v['is_menu'], 'children' => []];
            } else {
                $menusTwo[$v['typeid']][] = ['id' => $v['id'], 'title' => $v['title'], 'icon' => $v['icon'], 'href' => $v['href'], 'is_menu'=>$v['is_menu'], 'children' => []];
            }
        }
        foreach($menusOne as $k => $v){
            if(isset($menusTwo[$v['id']])){
                foreach($menusTwo[$v['id']] as $k1=>$v1){
                    isset($menusTwo[$v1['id']]) && $menusTwo[$v['id']][$k1]['children']=$menusTwo[$v1['id']];
                }
                $menusOne[$k]['children'] = $menusTwo[$v['id']];
            }
        }
        return $menusOne;
    }


}