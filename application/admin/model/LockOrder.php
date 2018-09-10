<?php
namespace app\admin\model;

use app\common\model\BaseAdminModel;
use think\Db;
use think\Model;

class LockOrder extends BaseAdminModel
{


    public function getTitle()
    {
        return '锁仓订单';
    }

    public function index($where, $page, $limit, $order)
    {
        $offset = ($page-1)*$limit;
        return $this->alias('m')
            ->field('m.id,m.rate,m.days,m.money,m.income,m.start_date,m.end_date,m.status,m.total_income,m.c_time,u.mobile,u.real_name')
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
        if($data['status'] == 1 && $this->status == 0) {
            $this->save($data);
            //推出资金
                Db::name('user')->where('user_id='.$this->user_id)
                    ->update([
                        'ky_money'=>['exp', 'ky_money+'.$this->money],
                        'gd_money'=>['exp', 'gd_money-'.$this->money]
                    ]);
            Db::name('money_log')->insert(['user_id'=>$this->user_id, 'order_id'=>$this->id, 'money'=>$this->money, 'sign'=>'+', 'remark'=>'系统退出', 'type'=>11]);

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