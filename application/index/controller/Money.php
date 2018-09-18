<?php
namespace app\index\controller;

use app\admin\model\Config;
use app\api\model\User;
use app\common\controller\IndexController;
use think\Db;
use think\Log;

class Money extends IndexController
{

    protected static $sPermissionArr = [
        'pull' => 5,
        'pulldetail' => 5,
        'push' => 5,
        'pushdetail' => 5,
        'apply' => 5,
        'pulladdress' => 5,
        'pullnum' => 5,
        'addaddress' => 5,
        'addcb' => 7,
        'addapply' => 7,
        'cancel' => 7,

    ];

    protected static $sParamsArr = [
        'addcb' => ['jyid'=>2, 'count'=>2,'is_kuang'=>2],
        'addapply' => ['mobile'=>2, 'b_id'=>2, 'count'=>2, 'address'=>2, 'payment_id'=>2, 'verify'=>2, 'password'=>2],
        'cancel'=>['id'=>2]
    ];

    public function pull()
    {
        $cbs = Db::name('cb_log')->where('user_id='.$this->userId)->order('id desc')->select();
        $this->assign(['cbs'=>$cbs, 'status'=>[1=>'审核中', 2=>'已通过', 3=>'已驳回']]);
        return $this->fetch();
    }

    public function pulldetail()
    {
        $id = (int)$this->request->param('id');
        if($id<=0){
            abort(404);
        }
        $cb = Db::name('cb_log')->where('id='.$id.' AND user_id='.$this->userId)->find();
        if(empty($cb)){
            abort(404);
        }
        $this->assign(['cb'=>$cb, 'status'=>[1=>'审核中', 2=>'已通过', 3=>'已驳回']]);
        return $this->fetch();
    }

    public function push()
    {
        $tbs = Db::name('tb_log')->alias('l')->field('l.count,l.id,date_format(l.c_time, \'%Y-%m-%d\') as c_time,l.status,b.name')
            ->join('p_b b', 'b.id=l.b_id')
            ->where('l.user_id='.$this->userId)
            ->order('l.id desc')
            ->select();
        $this->assign(['tbs'=>$tbs, 'status'=>[1=>'审核中', 2=>'已通过', 3=>'已驳回', 4=>'已取消']]);

        return $this->fetch();
    }

    public function pushdetail()
    {
        $id = (int)$this->request->param('id');
        if($id<=0){
            abort(404);
        }
        $tb = Db::name('tb_log')->where('id='.$id.' AND user_id='.$this->userId)->find();
        if(empty($tb)){
            abort(404);
        }
        $tbName = Db::name('b')->where('id='.$tb['b_id'])->value('name');
        $this->assign(['tbName'=>$tbName,'tb'=>$tb, 'status'=>[1=>'审核中', 2=>'已通过', 3=>'已驳回', 4=>'已取消']]);
        return $this->fetch();
    }

    public function cancel(){
        $id =$this->requestData['id'];
        $tb = Db::name('tb_log')->where('id='.$id.' AND user_id='.$this->userId)->find();
        if(empty($tb)){
            abort(404);
        }
        if($tb['status'] != 1){
            return $this->jsonFail('该记录发生变化，请刷新再试');
        }
        Db::startTrans();
        try {
            Db::name('tb_log')->where('id='.$id)->update(['status'=>4]);
            Db::name('user')->where('user_id='.$this->userId)->update(['ky_money'=>['exp', 'ky_money+'.$tb['count']]]);
            Db::name('money_log')->insert(['user_id' => $this->userId, 'money' => $tb['count'], 'type' => 10, 'remark' => '提币-用户取消', 'tb_log_id'=>$id]);
            Db::commit();
        }catch(\Exception $e){
            Db::rollback();
            Log::error($e->getMessage());
        }
        return $this->jsonSuccess('修改成功', ['url'=>'/index/money/push']);
    }

    public function apply()
    {
        $this->assign('b', Db::name('b')->where('id>0')->select());
        $rate = Db::name('config')->where('id=35')->value('content');
        $rate = bcdiv($rate,100,2);
        $this->assign('isOpen', Config::getIsOpen());
        $this->assign('rate', $rate);
        return $this->fetch();
    }

    public function addApply()
    {
        if($this->requestData['password'] < 200){
            return $this->jsonFail('数量不得小于200');
        }
        if (User::makePassword($this->requestData['password']) != $this->userInfo['trade_password']){
            return $this->jsonFail('交易密码不正确');
        }
        $data = $this->requestData;
        if (bccomp($this->userInfo['ky_money'], $data['count'])<0){
            return $this->jsonFail('可用资产不足');
        }
        unset($data['mobile'], $data['verify'], $data['password']);
        $rate = Db::name('config')->where('id=35')->value('content');
        $rate = bcdiv($rate,100,2);
        $data['status'] = 1;
        $data['user_id'] = $this->userId;
        $data['sxf_money'] = bcmul($data['count'], $rate, 2);
        $data['sj_money'] = bcsub($data['count'], $data['sxf_money'], 2);
        Db::startTrans();
        try {
            $id = Db::name('tb_log')->insert($data, false, true);
            Db::name('user')->where('user_id='.$this->userId)->update(['ky_money'=>['exp', 'ky_money-'.$data['count']]]);
            Db::name('money_log')->insert(['user_id' => $this->userId, 'money' => $data['count'], 'type' => 9, 'remark' => '提币—申请提币', 'sign' => '-','tb_log_id'=>$id]);
            Db::commit();
        }catch(\Exception $e){
            Db::rollback();
            Log::error($e->getMessage());
        }
        return $this->jsonSuccess('申请成功', ['url'=>'/index/money/push']);
    }

    //获取充币地址
    public function pulladdress()
    {
        //$scAddress = $this->userInfo['address_id']>0? Db::name('address')->where('id='.$this->userInfo['address_id'])->value('content'):'';
        $address = Db::name('address')->field('id,content')->order('id desc')->find();
        $this->assign('scAddress', $address);
        //$this->assign('btnTxt', $this->userInfo['address_id'] == 0 ? '获取充币地址' : '一键复制地址');
        return $this->fetch();
    }

    public function addAddress()
    {
        if($this->userInfo['address_id']>0){
            return $this->jsonFail('你已经获取过了，请刷新再试');
        }
        $address = Db::name('address')->limit(20)->field('id,content')->select();
        if(empty($address)){
            return $this->jsonFail('系统还没添加地址');
        }
        $max = count($address) - 1;
        $random = mt_rand(0, $max);
        Db::name('user')->where('user_id='.$this->userId)->update(['address_id'=>$address[$random]['id']]);
        return $this->jsonSuccess('获取成功', ['address'=>$address[$random]['content']]);
    }

    public function pullnum()
    {
        return $this->fetch();
    }

    public function addcb()
    {
        $data = $this->requestData;
        $data['user_id'] = $this->userId;
        $data['status'] = 1;
        Db::name('cb_log')->insert($data);
        return $this->jsonSuccess('提交成功', ['url'=>'/index/money/pull']);
    }

}