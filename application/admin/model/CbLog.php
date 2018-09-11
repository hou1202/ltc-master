<?php
namespace app\admin\model;

use app\common\model\BaseAdminModel;
use think\Db;
use think\Model;

class CbLog extends BaseAdminModel
{


    public function getTitle()
    {
        return '充币记录';
    }

    public function index($where, $page, $limit, $order)
    {
        $offset = ($page-1)*$limit;
        return $this->alias('m')
            ->field('m.id,m.jyid,m.count,m.status,m.remark,m.is_kuang,m.c_time,u.mobile,u.real_name')
            ->where($where)
            ->join('p_user u', 'u.user_id=m.user_id')
            ->limit($offset, $limit)
            ->order($order)
            ->select();
    }

    public function totalCount($where)
    {
        return $this->alias('m')->join('p_user u', 'u.user_id=m.user_id')->where($where)->count();
    }

    public function add($data)
    {

        return true;
    }

    public function edit($data)
    {
        if($this->status == 2){
            return false;
        }
        if($data['status'] == 2) {
            //判断是否为购买矿机
            if($data['is_kuang'] == 1){
                //获取用户父级树
                $user = Db::name('user')->field('user_id,parent_ids')->where('user_id='.$this->user_id)->find();
                //判断父级树是否为空
                if($user['parent_ids'] == ''){
                    $parentIds = explode('|', substr($user['parent_ids'], 1, count($user['parent_ids'])-2));
                    //获取等级收益利率
                    $rate = Db::name('config')->field('content')->where('id','in',[17,18,19,20,23,24,25,26,27,28])->order('id asc')->select();
                    foreach($parentIds as $key =>$parent){
                        //计算收益
                        $parentIncome = bcmul($this->count,bcdiv($rate[$key]['content'],100,4),4);
                        if(bccomp($parentIncome, 0, 4)>0) {
                            Db::name('user')->where('user_id=' . $parent)->update([
                                'share_income' => ['exp', 'share_income+' .$parentIncome],
                                'to_share_income' => ['exp', 'to_share_income+' . $parentIncome],
                                'ky_money'=>['exp', 'ky_money+'.$parentIncome],
                            ]);
                            Db::name('money_log')
                                ->insert(['user_id'=> $parent,'order_id'=>$this->id, 'money'=>$parentIncome, 'sign'=>'+', 'remark'=>'好友收益', 'type'=>6]);

                        }
                    }
                }

                //更新矿机数量
                $miner = intval($this->count/500);

                var_dump($miner);die;

                $this->save($data);

                return true;
            }else{
                $this->save($data);

                //增加可用资产
                Db::name('user')->where('user_id='.$this->user_id)
                    ->update([
                        'ky_money'=>['exp', 'ky_money+'.$this->count],
                    ]);
                Db::name('money_log')->insert(['user_id'=>$this->user_id, 'cb_log_id'=>$this->id, 'money'=>$this->count, 'sign'=>'+', 'remark'=>'系统充值', 'type'=>12]);

                return true;

            }



        }elseif($data['status'] == 3){
            $this->save($data);
            return true;
        }

        return false;
    }

    public function del()
    {
        $this->save(['is_del'=>time()]);
        return true;
    }

}