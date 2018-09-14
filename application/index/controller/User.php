<?php
namespace app\index\controller;



use app\common\controller\IndexController;
use app\common\model\FileCheck;
use think\Config;
use think\Db;
use think\Session;

class User extends IndexController
{

    protected static $sPermissionArr = [
        'forgetpass'=>1,  //忘记密码页面
        'login'=>3,
        'logout'=>1,
        'index'=>5,
        'editpass'=>3, //忘记密码
        'reg'=>3,
        'editpassword'=>5,  //修改密码
        'tradepassword'=>5,  //修改交易密码-页面
        'tradepass'=>7, //修改交易密码
        'data'=>5,
        'update'=>7,
        'income'=>5,
        'sign'=>5,
    ];

    protected static $sParamsArr =[
        'login'=>['mobile'=>2, 'password'=>2],
        'setsign'=>['sign'=>2],
        'editpass'=>['mobile'=>2, 'password'=>2, 'password1'=>2, 'verify'=>2],
        'tradepass'=>['mobile'=>2, 'trade_password'=>2, 'trade_password1'=>2, 'verify'=>2],
        'reg'=>['mobile'=>2, 'password'=>2, 'password1'=>2, 'verify'=>2, 'trade_password'=>2,'trade_password1'=>2, 'invitation_code'=>2],
        'update'=>['bank_id'=>2, 'real_name'=>2, 'nick_name'=>2,'bank_number'=>2, 'verify'=>2, 'bank_zh_name'=>2,'alipay_number'=>2, 'poster'=>2, 'mobile'=>2],
    ];

    public function income()
    {
        /*$date = date('Y-m-d 00:00:00');
        $signIncome = Db::name('money_log')->where('user_id='.$this->userId.' AND type=0 AND c_time>=\''.$date.'\'')->value('money');
        empty($signIncome) && $signIncome = 0;
        $totalIncome = bcadd(bcadd($this->userInfo['to_share_income'],$this->userInfo['today_income'], 4), $signIncome, 4);
        */
        $totalIncome = bcadd($this->userInfo['to_share_income'],$this->userInfo['today_income'], 4);
        $this->assign(['totalIncome'=>$totalIncome]);
        return $this->fetch();
    }

    public function data(){
        $this->assign('banks', Db::name('bank')->field('id,name')->select());
        return $this->fetch();
    }

    public function update(){
        $data = $this->requestData;
        unset($data['mobile'], $data['verify']);
        Db::name('user')->where('user_id='.$this->userId)->update($data);
        return $this->jsonSuccess('修改成功');
    }

    /**
     * 忘记密码-html
     * @return mixed
     */
    public function forgetPass(){
        return $this->fetch();
    }

    /**
     * 忘记密码-html
     * @return mixed
     */
    public function tradepassword(){
        return $this->fetch();
    }

    /**
     * 个人中心-首页
     */
    public function index(){
        $shareFriend = (int)Db::name('user')->where('parent_id='.$this->userId)->count();
        /*$date = date('Y-m-d 00:00:00');
        $signIncome = Db::name('money_log')->where('user_id='.$this->userId.' AND type=0 AND c_time>=\''.$date.'\'')->value('money');
        empty($signIncome) && $signIncome = 0;
        $incomeMoney = bcadd(bcadd($this->userInfo['to_share_income'],$this->userInfo['today_income'], 4), $signIncome, 4);
        */
        $incomeMoney = bcadd($this->userInfo['to_share_income'],$this->userInfo['today_income'], 4);
        $this->assign(['shareFriend'=>$shareFriend, 'incomeMoney'=>$incomeMoney]);
        return $this->fetch();
    }

    /**
     * 登录接口
     */
    public function login(){
        $userData = $this->validate->getData();
        if(isset($userData['is_del']) && $userData['is_del'] > 0) {
            return $this->jsonFail('您的账号已被禁用');
        }
        Session::set('userId', $userData['user_id']);
        return $this->jsonSuccess('登录成功,页面跳转中...',['url'=>'/index/user/index']);
    }

    /**
     * 退出登录
     */
    public function logout(){
        Session::delete('userId');
        return $this->jsonSuccess('退出登录成功',['url'=>'/index/index/login']);
    }

    /**
     * 修改密码
     */
    public function editPass(){
        \app\api\model\User::editPass($this->requestData['mobile'], $this->requestData['password']);
        Session::delete('userId');
        return $this->jsonSuccess('修改成功', ['url'=>'/index/login/index']);
    }

    /**
     * 修改trade密码
     */
    public function tradePass(){
        \app\api\model\User::editTradePass($this->requestData['mobile'], $this->requestData['trade_password']);
        return $this->jsonSuccess('修改成功', ['url'=>'/index/user/index']);
    }

    /**
     * 注册请求
     */
    public function reg(){
        unset($this->requestData['password1'], $this->requestData['trade_password1'], $this->requestData['invitation_code']);
        $this->requestData['parent_id'] = $this->validate->getData('parent_id');
        $this->requestData['parent_ids'] = $this->validate->getData('parent_ids');
        if($this->requestData['parent_id'] == 0){
            $this->requestData['parent_id'] = 0;
            $this->requestData['parent_ids'] = '';
        }else {
            if ($this->requestData['parent_ids'] == '') {
                $this->requestData['parent_ids'] = '|' . $this->requestData['parent_id'] . '|';
            } else {
                //判断父级有几级
                $parentids = explode('|', substr($this->requestData['parent_ids'], 1, strlen($this->requestData['parent_ids']) - 2));
                $count = count($parentids);
                if ($count > 9) {
                    $parentids = array_slice($parentids, 0, 9);
                }
                array_unshift($parentids, $this->requestData['parent_id']);
                $this->requestData['parent_ids'] = '|' . implode('|', $parentids) . '|';
            }
        }
        $userId = \app\api\model\User::reg($this->requestData);
        if($userId>0){
            //File::update(['typeid'=>$userId], ['url'=>['in', $this->requestData['cards']]]);
            return $this->jsonSuccess('注册成功', ['url'=>'/index/index/login']);
        }
        return $this->jsonFail('注册失败');
    }

    public function userInfo(){
        $this->userInfo = array_merge($this->userInfo, \app\api\model\User::getHosNameAndDepName($this->userId));
        $this->assign(['user'=>$this->userInfo]);
        return $this->fetch();
    }

    public function updatePoster(){
        $result = FileCheck::saveAllFiles(['img' => 1]);
        if (is_string($result)) {
            return $this->jsonFail($result);
        }
        $result['img'] = str_replace('\\', '/', $result['img']);
        $data['url'] = $result['img'];
        $data['src'] = substr($result['img'], strpos($result['img'], '/uploads'));
        $data['typeid'] = $this->userId;
        $data['from'] = 'p_user';
        $data['action'] = 'poster';
        \app\api\model\User::edit($this->userId, ['poster'=>$result['img']]);
        $file = new \app\admin\model\File();
        if ($file->add($data)) {
            return $this->jsonSuccess('上传成功', ['url' => $result['img'], 'id' => $file->id]);
        }
        return $this->jsonFail('上传失败');
    }

    public function editPassword(){
        $this->assign(['mobile'=>$this->userInfo['mobile']]);
        return $this->fetch();
    }

    public function getSign(){
        $sign = \app\api\model\User::getSignByUserId($this->userId);
        if(empty($sign)){
            return $this->jsonFail('您还没设置签名');
        }else{
            return $this->jsonSuccess('获取签名成功', ['sign'=>$sign]);
        }
    }

    public function setSign()
    {
        $sign = \app\api\model\User::getSignByUserId($this->userId);
        if ($sign != '') {
            return $this->jsonSuccess('你的签名已设置，不能重复设置', ['url' => '/index/user/userInfo']);
        }
        $sign = $this->request->post('sign');
        $imgBody = substr(strstr($sign, ','), 1);
        $imgData = base64_decode($imgBody);
        $filePath = ROOT_PATH . 'public' . DS . 'uploads' . DS . date('Ymd');
        $fileName = md5(microtime(true)) . '.png';
        if (!file_exists($filePath)) {
            mkdir($filePath, 0777, true);
        }
        $filePath = $filePath . DS . $fileName;
        $savePath = 'uploads/' . date('Ymd') . '/' . $fileName;
        if (file_put_contents($filePath, $imgData)) {
           // Db::table('p_user')->where('user_id=' . $this->userId)->update(['sign' => $this->request->domain() . '/' . $savePath]);
            $domain = Config::get('upload_file_domain');
            $data['url'] = $domain.'/'.$savePath;
            $data['src'] = '/'.$savePath;
            $data['typeid'] = $this->userId;
            $data['from'] = 'p_user';
            $data['action'] = 'dz_sign';
            \app\api\model\User::edit($this->userId, ['dz_sign'=>$data['url']]);
            $file = new \app\admin\model\File();
            if ($file->add($data)) {
                return $this->jsonSuccess('设置签名成功', ['url' => '/index/user/userInfo']);
            }
        }
        return $this->jsonFail('设置失败');
    }

    public function sign(){
        //判断是否签到
        $date = date('Y-m-d');
        $count = Db::name('user_sign')->where(['user_id'=>$this->userId, 'sign_date'=>$date])->count();
        if($count>0) {
            return $this->jsonFail('您今天已经签到过了');
        }
        Db::name('user_sign')->insert(['user_id'=>$this->userId, 'sign_date'=>$date]);
        $money = \app\admin\model\Config::getSignMoney();
        if($money>0) {
            Db::name('money_log')->insert(['user_id' => $this->userId, 'money' => $money, 'remark'=>'签到获得']);
            Db::name('user')->where('user_id='.$this->userId)
                ->update(['ky_money' => ['exp', 'ky_money+'.$money]]);
        }
        $kyMoney = Db::name('user')->where('user_id='.$this->userId)->value('ky_money');
        return $this->jsonSuccess('签到成功，获取'.$money.'金币', ['ky_money'=>$kyMoney]);
    }



}