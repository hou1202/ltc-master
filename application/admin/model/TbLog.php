<?php
namespace app\admin\model;

use app\common\model\BaseAdminModel;
use think\Db;
use think\Model;

class TbLog extends BaseAdminModel
{

    //0签到 1系统添加 2系统赠送 3锁仓计划 4锁仓收益5锁仓退回 6好友收益 7出售锁定 8订单购买9提币 10提币取消 11锁仓系统退出 12充值

    public function getTitle()
    {
        return '提币记录';
    }

    public function index($where, $page, $limit, $order)
    {
        $offset = ($page-1)*$limit;
        return $this->alias('m')
            ->field('m.id,m.count,m.sxf_money,m.sj_money,m.address,m.status,m.c_time,u.mobile,u.real_name,b.name as b_name')
            ->where($where)
            ->join('p_user u', 'u.user_id=m.user_id')
            ->join('p_b b', 'b.id=m.b_id')
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
        if($this->status == 3 || $this->status == 4){
            return false;
        }
        if($data['status'] == 3) {
            $this->save($data);
            //增加可用资产
                Db::name('user')->where('user_id='.$this->user_id)
                    ->update([
                        'ky_money'=>['exp', 'ky_money+'.$this->count],
                    ]);
            Db::name('money_log')->insert(['user_id'=>$this->user_id, 'tb_log_id'=>$this->id, 'money'=>$this->count, 'sign'=>'+', 'remark'=>'提币-系统驳回', 'type'=>10]);

            return true;
        }elseif($data['status'] == 2){
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