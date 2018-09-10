<?php
namespace app\admin\model;

use app\common\model\BaseAdminModel;
use think\Db;
use think\Model;

class MoneyLog extends BaseAdminModel
{


    public function getTitle()
    {
        return '金币记录';
    }

    public function index($where, $page, $limit, $order)
    {
        $offset = ($page-1)*$limit;
        return $this->alias('m')
            ->field('m.id,m.money,m.sign,m.remark,m.c_time,u.mobile,u.real_name,u.invitation_code')
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
        $data['sign'] = $data['money'] < 0 ? '-' : '+';
        $data['money'] = abs($data['money']);
        $data['type'] = 1;
        $this->save($data);
        Db::name('user')->where('user_id='.$data['user_id'])
            ->update(['ky_money'=>['exp', 'ky_money'.$data['sign'].$data['money']]]);

//        $user = Db::name('user')->where('user_id='.$data['user_id'])->find();
//        //更新算力
//        if (bccomp($user['cz_money'],5200) >= 0) {
//            $suanli = 1000;
//            if (bccomp($user['cz_money'],78000) >= 0) {
//                $suanli = 6000;
//                if (bccomp($user['cz_money'],650000) >= 0) {
//                    $suanli = 30000;
//                }
//            }
//            Db::name('user')->where('user_id='.$data['user_id'])->update(['suanli'=>$suanli]);
//        }
//
//        if($user['parent_id'] > 0) {
//            $this->jisuanParents($user['parent_ids']);
//        }
        return true;
    }

    public function jisuanParents($ids){
        $ids = substr($ids, 1, strlen($ids)-2);
        $idArr = explode('|', $ids);
        foreach($idArr as $id){
            $sunUsers = Db::name('user')->field('user_id')->where('parent_id='.$id)->select();
            $totalMoney1 = 0;
            if(isset($sunUsers[0])){
                $totalMoney11 = Db::name('user')->where('user_id='.$sunUsers[0]['user_id'])->value('cz_money');
                $totalMoney1 = Db::name('user')->where('parent_ids like \'%|'.$sunUsers[0]['user_id'].'|%\'')->sum('cz_money');
                empty($totalMoney1) && $totalMoney1 = 0;
                $totalMoney1 = bcadd($totalMoney11, $totalMoney1);
            }
            $totalMoney2 = 0;
            if(isset($sunUsers[1])){
                $totalMoney22 = Db::name('user')->where('user_id='.$sunUsers[1]['user_id'])->value('cz_money');
                $totalMoney2 = Db::name('user')->where('parent_ids like \'%|'.$sunUsers[1]['user_id'].'|%\'')->sum('cz_money');
                empty($totalMoney2) && $totalMoney2 = 0;
                $totalMoney2 = bcadd($totalMoney22, $totalMoney2);
            }
            if(bccomp($totalMoney1, $totalMoney2) >=0 ){
                //取小值
                $suanli = bcdiv($totalMoney2, 200000);
                if($suanli > 0) {
                    $u = Db::name('user')->where('user_id='.$id)->field('prize_count,suanli')->find();
                    if (bccomp($suanli, $u['prize_count'])) {
                        $cha = bcsub($suanli,$u['prize_count']);
                        $zjMoney = bcmul($cha, $u['suanli']);
                        Db::name('user')->where('user_id='.$id)->update(['prize_count'=>['exp', 'prize_count+'.$cha], 'money'=>['exp', 'money+'.$zjMoney], 'zs_money'=>['exp', 'zs_money+'.$zjMoney]]);
                        Db::name('money_log')->insert(['user_id'=>$id, 'money'=>$zjMoney, 'type'=>2, 'remark'=>'系统赠送']);
                    }
                }
            }
        }

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