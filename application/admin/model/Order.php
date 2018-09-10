<?php
namespace app\admin\model;

use app\common\model\BaseAdminModel;
use think\Db;
use think\Model;

class Order extends BaseAdminModel
{

    //9失效 1求购 2交易中(撮合)3待确认4已完成

//0签到 1系统添加 2系统赠送 3锁仓计划 4锁仓收益5锁仓退回 6好友收益 7出售锁定 8订单购买9提币 10提币取消 11锁仓系统退出 12充值 13交易-系统判买家 14交易-系统判卖家
    public function getTitle()
    {
        return '用户交易';
    }

    public function index($where, $page, $limit, $order)
    {
        $offset = ($page-1)*$limit;
        return $this->alias('m')
            ->field('m.id,m.count,m.price,m.sh_status,m.other_count,m.total_count,m.total_price,m.status,m.c_time,u.mobile,u.real_name')
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
        if($this->sh_status != 1){
            return false;
        }
        if($data['sh_status'] == 3) {
            $this->save($data);
            //买家
                Db::name('user')->where('user_id='.$this->user_id)
                    ->update([
                        'ky_money'=>['exp', 'ky_money+'.$this->count],
                    ]);
            Db::name('money_log')->insert(['user_id'=>$this->user_id, 'o_id'=>$this->id, 'money'=>$this->count, 'sign'=>'+', 'remark'=>'交易-系统判买家', 'type'=>13]);

            return true;
        }elseif($data['sh_status'] == 2){
            //卖家
            $this->save($data);
            Db::name('user')->where('user_id='.$this->sell_id)
                ->update([
                    'ky_money'=>['exp', 'ky_money+'.$this->total_count],
                ]);
            Db::name('money_log')->insert(['user_id'=>$this->sell_id, 'o_id'=>$this->id, 'money'=>$this->total_count, 'sign'=>'+', 'remark'=>'交易-系统判卖家', 'type'=>14]);

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