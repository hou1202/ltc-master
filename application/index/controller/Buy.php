<?php
namespace app\index\controller;



use app\api\model\User;
use app\common\controller\IndexController;
use app\common\model\CommonUtils;
use think\Db;

class Buy extends IndexController
{

    protected static $sPermissionArr = [
        'buy'=>5,
        'commit'=>7,
        'tradelist'=>5,
        'cancel'=>7,
        'buylist'=>5,
        'trade'=>5,
        'cuohe'=>7,
        'buydetail'=>5,
        'remit'=>5,
        'remitdetail'=>5,
        'huikuan'=>7,
        'shoukuan'=>7,

    ];

    protected static $sParamsArr = [
        'commit'=>['count'=>2, 'password'=>2, 'mobile'=>2],
        'cancel'=>['id'=>2],
        'cuohe'=>['id'=>2, 'count'=>2, 'price'=>2, 'total_price'=>2],
        'huikuan'=>['id'=>2, 'password'=>2],
        'shoukuan'=>['id'=>2, 'password'=>2],
    ];

    public function buy(){
        $this->assign('ltcPrice', Db::name('money_price')->where('is_del=0')->order('id desc')->value('price'));
        return $this->fetch();
    }

    public function commit(){
        if($this->userInfo['real_name']=='' || $this->userInfo['bank_number']==''){
            return $this->jsonFail('请先完善资料');
        }
        if (User::makePassword($this->requestData['password']) != $this->userInfo['trade_password']){
            return $this->jsonFail('密码不正确');
        }
        $count = (int)$this->requestData['count'];
        if($count<=0){
            return $this->jsonFail('请输入正确的购买数量！');
        }
        $data = ['user_id'=>$this->userId, 'count'=>$count, 'status'=>1];
        Db::name('order')->insert($data);
        return $this->jsonSuccess('提交成功', ['url'=>'/index/buy/tradelist']);
    }

    public function tradelist(){
        $trades = Db::name('order')->field('id,count,c_time')->where('user_id='.$this->userId.' AND status=1')->order('id desc')->select();
        $price = Db::name('money_price')->where('is_del=0')->order('id desc')->value('price');
        foreach($trades as $k=>$v) {
            $trades[$k]['total_price'] = bcmul($price, $v['count'], 4);
            $trades[$k]['c_time'] = date('Y-m-d', strtotime($v['c_time']));
        }
        $this->assign('trades', $trades);
        return $this->fetch();
    }

    public function cancel(){
        $id = $this->requestData['id'];
        $order = Db::name('order')->where('id='.$id.' AND user_id='.$this->userId)->find();
        if(empty($order)){
            abort(404);
        }
        if($order['status'] != 1){
            return $this->jsonFail('该订单状态已变，请刷新再试');
        }
        Db::name('order')->where('id='.$id.' AND user_id='.$this->userId.' AND status=1')->update(['status'=>9, 'sx_time'=>date('Y-m-d H:i:s')]);
        return $this->jsonSuccess('取消成功');
    }

    public function buylist(){
        CommonUtils::flushOrders(0, $this->userId);
        $orders = Db::name('order')->field('id,count,other_count,total_price,status')
            ->where('sell_id='.$this->userId.' AND status>1')
            ->order('id desc')
            ->select();
        $this->assign('orders', $orders);
        $this->assign('status', [2=>'交易中', 3=>'待确认', 4=>'已完成', 9=>'已失效']);
        return $this->fetch();
    }

    public function trade(){
        $orders = Db::name('order')->field('id,count')->where('status=1')->order('id desc')->select();
        $price = Db::name('money_price')->where('is_del=0')->order('id desc')->value('price');
        $this->assign(['price'=>$price, 'orders'=>$orders]);
        return $this->fetch();
    }

    public function buydetail(){
        CommonUtils::flushOrders(0, $this->userId);
        $id = (int)$this->request->param('id');
        if($id<=0){
            abort(404);
        }
        $order = Db::name('order')->where('id='.$id.' AND sell_id='.$this->userId)->find();
        if(empty($order)){
            abort(404);
        }
        $maijia = Db::name('user')->field('user_id,vip_number,real_name,mobile,c_time')->where('user_id='.$order['user_id'])->find();
        if($maijia==null){
            abort(404);
        }
        $time = 10800- (time() - strtotime($order['ty_time']));
        $time > 0 && $time = CommonUtils::secToTime($time);
        $jyTime = 10800 - (time() - strtotime($order['dqr_time']));
        $jyTime > 0 && $jyTime = CommonUtils::secToTime($jyTime);
        $this->assign('order', $order);
        $this->assign('maijia', $maijia);
        $this->assign('time', $time);
        $this->assign('jyTime', $jyTime);
        $this->assign('status', [2=>'交易中', 3=>'待确认', 4=>'已完成', 9=>'已失效']);
        return $this->fetch();
    }

    public function cuohe(){
        if($this->userInfo['real_name']=='' || $this->userInfo['bank_number']==''){
            return $this->jsonFail('请先完善资料');
        }
        $id = $this->requestData['id'];
        $order = Db::name('order')->where('id='.$id)->find();
        if(empty($order)){
            abort(404);
        }
        if($order['user_id'] == $this->userId){
            return $this->jsonFail('自己不能给自己发布的购买撮合！');
        }
        if($order['status'] != 1){
            return $this->jsonFail('该交易已被别人抢先一步了！');
        }
        $price = Db::name('money_price')->where('is_del=0')->order('id desc')->value('price');
        if(bccomp($price, $this->requestData['price'], 4)!=0){
            return $this->jsonFail('价格有变动！');
        }
        $data = ['price'=>$price, 'sell_id'=>$this->userId, 'status'=>2, 'ty_time'=>date('Y-m-d H:i:s')];
        $rate = Db::name('config')->where('id=21')->value('content');
        $data['total_price'] = bcmul($price, $order['count'], 4);
        $data['other_count'] = bcmul($order['count'], bcdiv($rate, 100, 4), 4);
        if(bccomp($data['other_count'], 0.0001, 4)<0){
            $data['other_count'] = 0.0001;
        }
        $data['total_count'] = bcadd($order['count'], $data['other_count'], 4);
        if(bccomp($this->userInfo['ky_money'], $data['total_count'], 4)<0){
            return $this->jsonFail('可用资产不足！');
        }
        $log = ['user_id'=>$this->userId, 'money'=>$data['total_count'], 'sign'=>'-', 'type'=>7, 'remark'=>'出售锁定', 'o_id'=>$id];
        Db::startTrans();
        try{
            //更新订单状态
            if(Db::name('order')->where('id='.$id.' AND status=1')->update($data) != 1){
                throw new \Exception('ORDER cuohe update status ERROR!id='.$id, -1);
            }
            //更新用户可用资产
            if(Db::name('user')->where('user_id='.$this->userId.' AND ky_money-'.$data['total_count'].'>=0')
                ->update(['ky_money'=>['exp', 'ky_money-'.$data['total_count']]]) != 1){
                throw new \Exception('ORDER cuohe update user ky_money  ERROR!id='.$id, -2);
            }
            //插入新记录
            if(Db::name('money_log')->insert($log)<=0){
                throw new \Exception('ORDER cuohe insert money log  ERROR!id='.$id, -3);
            }
            Db::commit();
        }catch(\Exception $e){
            Db::rollback();
            $code = $e->getCode();
            if($code == -1){
                return $this->jsonFail('该交易已被别人抢先一步了！');
            }elseif($code == -2){
                return $this->jsonFail('可用资产不足！');
            }
            return $this->jsonFail('未知错误');
        }
        return $this->jsonSuccess('提交成功', ['url'=>'/index/buy/buylist']);
    }

    public function remit(){
        CommonUtils::flushOrders($this->userId, 0);
        $orders = Db::name('order')->field('id,count,price,total_price,status')
            ->where('user_id='.$this->userId.' AND status>1')
            ->order('id desc')
            ->select();
        $this->assign('orders', $orders);
        $this->assign('status', [2=>'交易中', 3=>'待确认', 4=>'已完成', 9=>'已失效']);
        return $this->fetch();
    }

    public function remitdetail(){
        CommonUtils::flushOrders($this->userId, 0);
        $id = (int)$this->request->param('id');
        if($id<=0){
            abort(404);
        }
        $order = Db::name('order')->where('id='.$id.' AND user_id='.$this->userId)->find();
        if(empty($order)){
            abort(404);
        }
        if ($order['sell_id']>0) {
            $maijia = Db::name('user')->field('vip_number,user_id,alipay_number,bank_number,bank_id,real_name,mobile,c_time')->where('user_id=' . $order['sell_id'])->find();
            if ($maijia == null) {
                abort(404);
            }
            $maijia['bank_name'] = Db::name('bank')->where('id=' . $maijia['bank_id'])->value('name');
        } else {
            $maijia = ['vip_number'=>'', 'real_name'=>'', 'mobile'=>'', 'bank_name'=>'', 'alipay_number'=>'','bank_number'=>''];
        }
        $time = 10800 - (time() - strtotime($order['ty_time']));
        $time > 0 && $time = CommonUtils::secToTime($time);
        $jyTime = 10800 - (time() - strtotime($order['dqr_time']));
        $jyTime > 0 && $jyTime = CommonUtils::secToTime($jyTime);
        $this->assign('order', $order);
        $this->assign('maijia', $maijia);
        $this->assign('time', $time);
        $this->assign('jyTime', $jyTime);
        $this->assign('status', [2=>'交易中', 3=>'待确认', 4=>'已完成', 9=>'已失效']);
        return $this->fetch();
    }

    public function huikuan(){
        if (User::makePassword($this->requestData['password']) != $this->userInfo['trade_password']){
            return $this->jsonFail('交易密码不正确');
        }
        CommonUtils::flushOrders($this->userId, 0);
        $id = $this->requestData['id'];
        $order = Db::name('order')->where('id='.$id.' AND user_id='.$this->userId.' AND status=2')->find();
        if(empty($order)){
            return $this->jsonFail('该订单状态已发生变化，请刷新再试！');
        }
        Db::name('order')->where('id='.$id)->update(['status'=>3, 'dqr_time'=>date('Y-m-d H:i:s')]);
        return $this->jsonSuccess('确认汇款成功');
    }

    public function shoukuan(){
        if (User::makePassword($this->requestData['password']) != $this->userInfo['trade_password']){
            return $this->jsonFail('密码不正确');
        }
        CommonUtils::flushOrders(0, $this->userId);
        $id = $this->requestData['id'];
        $order = Db::name('order')->where('id='.$id.' AND sell_id='.$this->userId.' AND status=3')->find();
        if(empty($order)){
            return $this->jsonFail('该订单状态已发生变化，请刷新再试！');
        }
        Db::startTrans();
        try {
            Db::name('order')->where('id=' . $id)->update(['status' => 4, 'ywc_time' => date('Y-m-d H:i:s')]);
            Db::name('user')->where('user_id=' . $order['user_id'])->update(['ky_money' => ['exp', 'ky_money+' . $order['count']]]);
            Db::name('money_log')->insert(['user_id' => $order['user_id'], 'money' => $order['count'], 'type' => 8, 'remark' => '平台交易', 'o_id' => $order['id']]);
            Db::commit();
        }catch(\Exception $e){
            Db::rollback();
        }
        return $this->jsonSuccess('确认收款成功');
    }
}