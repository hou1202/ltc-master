<?php
namespace app\index\controller;


use app\api\model\User;
use app\common\controller\IndexController;
use think\Db;

class LockOrder extends IndexController
{

    protected static $sPermissionArr = [
        'index'=>5,
        'commit'=>7,
        'orders'=>5,
        'detail'=>5,
        'lockdeal'=>5,
        'miner'=>5,
        'minerdetail'=>5,
    ];

    protected static $sParamsArr =[
        'commit' => ['money'=>2, 'plan_id'=>2,'password'=>2],
    ];

    public function lockdeal()
    {
        $orders = Db::name('order')->alias('o')->field('date_format(o.c_time,\'%Y-%m-%d\') as c_time,o.count,o.price,o.user_id,o.sell_id,u.c_time as user_time,s.c_time as sell_time,s.vip_number as sell_vip_number,u.vip_number as user_vip_number')
            ->join('p_user u', 'u.user_id=o.user_id')
            ->join('p_user s', 's.user_id=o.sell_id')
            ->where('o.status=4')->order('o.id desc')->limit(50)->select();
        $this->assign('orders', $orders);
        return $this->fetch();
    }

    public function commit()
    {
        if(User::makePassword($this->requestData['password']) != $this->userInfo['trade_password']) {
            return $this->jsonFail('交易密码不正确');
        }
        $today = date('Y-m-d H:i:s');
        $plan = Db::name('lock_plan')->field('plan_id,sy_count,start_time,end_time,days,rate')
            ->where('plan_id='.$this->requestData['plan_id'].' AND is_del=0')
            ->find();
        if (empty($plan)) {
            return $this->jsonFail('该理财计划有误');
        }
        if($this->userInfo['ky_money'] < $this->requestData['money']) {
            return $this->jsonFail('您的可用资产不足,请充值');
        }
        /*if ($plan['sy_count'] < $this->requestData['money']) {
            return $this->jsonFail('剩余份额不足', ['status'=>2, 'data'=>$plan['sy_count']]);
        }*/

        /*if(Db::name('lock_order')->where('plan_id='.$plan['plan_id'].' AND user_id='.$this->userId)->count() > 0) {
            return $this->jsonFail('该理财计划您已经购买过了');
        }*/

        $money = $this->requestData['money'];
        $income = bcmul($money,bcdiv($plan['rate'],100,4),4);
        $startDate = date('Y-m-d');
        $endDate = date('Y-m-d', strtotime($startDate.' +'.$plan['days'].' day'));
        $order = ['plan_id'=>$plan['plan_id'], 'rate'=>$plan['rate'], 'days'=>$plan['days'], 'money'=>$money, 'user_id'=>$this->userId,
            'income'=>$income, 'total_income'=>bcmul($income,$plan['days'],4), 'start_date'=>$startDate, 'end_date'=>$endDate
        ];
        $log = ['user_id'=>$this->userId, 'money'=>$money, 'sign'=>'-', 'remark'=>'理财计划', 'type'=>3];
        $updateUser = ['ky_money'=>['exp','ky_money-'.$money],'gd_money'=>['exp', 'gd_money+'.$money]];
        if($this->userInfo['is_sc'] == 0){
            $updateUser['is_sc'] = 1;
        }
        Db::startTrans();
        try{
            /*$lCount = Db::name('lock_plan')->where('plan_id='.$plan['plan_id'].' AND sy_count-'.$money.'>=0')->update(['sy_count'=>['exp','sy_count-'.$money]]);
            if(empty($lCount)){
                throw new \Exception('not plan', -2);
            }*/
            $uCount = Db::name('user')->where('user_id='.$this->userId.' AND ky_money-'.$money.'>=0')
                ->update($updateUser);
            if(isset($updateUser['is_sc'])){
                //更新用户活跃度
                $parentIds = explode('|',substr($this->userInfo['parent_ids'], 1, strlen($this->userInfo['parent_ids'])-2));
                Db::name('user')->where('user_id', 'in', $parentIds)->update(['hy_count'=>['exp', 'hy_count+1']]);
                //添加锁币记录
                Db::name('lock_log')->insert(['user_id'=>$this->userId, 'money'=>$money, 'vip_number'=>$this->userInfo['vip_number']]);
            }
            if(empty($uCount)) {
                throw new \Exception('update user error', -1);
            }

            Db::name('lock_order')->insert($order);
            Db::name('money_log')->insert($log);
            Db::commit();
            return $this->jsonSuccess('添加成功', ['url'=>'/index/lock_order/orders']);
        }catch(\Exception $e){
            Db::rollback();
            if($e->getCode() == -2) {
                return $this->jsonFail('理财计划创建失败，请重试', ['status'=>2, 'data'=>Db::name('lock_plan')->where('plan_id='.$plan['plan_id'])->value('sy_count')]);
            }
        }
        return $this->jsonFail('未知错误');

    }

    public function index()
    {
        /*$today = date('Y-m-d 00:00:00');
        $endDay = date('Y-m-d 20:00:00');*/
        $boxs = Db::name('lock_plan')->field('plan_id,sy_count,start_time,end_time,days,rate')
            ->where('is_del=0')
            ->order('days asc')->select();
        /*$time = time();
        $selectIndex = 0;
        $isSel = false;
        foreach($boxs as $k=>$v){
            if($time>strtotime($v['start_time']) && $time<strtotime($v['end_time'])) {
                $selectIndex = $k;
                $isSel = true;
            }
            $boxs[$k]['hours'] = (int)date('H', strtotime($v['start_time']));
        }
        $selBox = !empty($boxs) && $isSel ? ['sy_count'=>$boxs[$selectIndex]['sy_count'],'sel_index'=>$selectIndex] : ['sy_count'=>0,'sel_index'=>0];
        */

        $totalCount = (int)Db::name('money_price')->where('is_del=0')->count();
        $dates = [];
        $moneys = [];
        if($totalCount>0){
            $offset = $totalCount-20;
            $offset<0 && $offset = 0;
            $moneyPrices = Db::name('money_price')->field('price,c_time')->where('is_del=0')->order('id asc')->limit($offset,20)->select();
            foreach($moneyPrices as $v) {
                $dates[] = date('m-d H:00',strtotime($v['c_time']));
                $moneys[] = $v['price'];
            }
        }
        //$this->assign(['boxs'=>$boxs, 'selBox'=>$selBox, 'isSel'=>$isSel, 'dates'=>json_encode($dates), 'moneys'=>json_encode($moneys)]);
        $this->assign(['boxs'=>$boxs, 'dates'=>json_encode($dates), 'moneys'=>json_encode($moneys)]);
        return $this->fetch();
    }

    public function orders()
    {
        $orders = Db::name('lock_order')->where('user_id='.$this->userId.' AND status=0')->order('id asc')->select();
        $this->assign('orders', $orders);
        return $this->fetch();
    }

    public function detail(){
        $id = $this->request->param('id');
        if(empty($id)){
            abort(404);
        }
        $order = Db::name('lock_order')->where('id='.$id.' AND user_id='.$this->userId)->find();
        if(empty($order)){
            abort(404);
        }
        $status = $order['status']==1?'已退出' :'理财中';
        $day = bcdiv((strtotime(date('Y-m-d')) - strtotime($order['start_date'])), 86400);
        $currentIncome = bcmul(bcmul($day, bcdiv($order['rate'], 100, 4), 4),$order['money'],4);
        $this->assign(['order'=>$order, 'status'=>$status, 'day'=>$day, 'currentIncome'=>$currentIncome]);
        return $this->fetch();
    }

    public function miner(){
        $miners = Db::name('miner')->where('user_id='.$this->userId)->order('id desc')->select();
        $this->assign('miners', $miners);
        return $this->fetch();

    }

    public function minerdetail(){
        $id = $this->request->param('id');
        if(empty($id)){
            abort(404);
        }
        $miner = Db::name('miner')->where('id='.$id.' AND user_id='.$this->userId)->find();
        if(empty($miner)){
            abort(404);
        }
        $status = $miner['status']==1?'已退出' :'挖矿中';
        $day = bcdiv((strtotime(date('Y-m-d')) - strtotime(substr($miner['c_time'],0,10))), 86400);
        //$currentIncome = bcmul(bcmul($day, bcdiv($miner['rate'], 100, 4), 4),$miner['money'],4);
        //$this->assign(['miner'=>$miner, 'status'=>$status, 'day'=>$day, 'currentIncome'=>$currentIncome]);
        $grades = Db::name('config')->field('id,content')->where('id in(30,31,32,33,34)')->order('id asc')->select();
        $rate = $grades[$this->userInfo['grade']-1]['content'];
        $this->assign(['miner'=>$miner, 'status'=>$status, 'day'=>$day,'rate'=>$rate]);
        return $this->fetch();
    }

}