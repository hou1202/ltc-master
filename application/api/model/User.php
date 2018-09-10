<?php
namespace app\api\model;

use app\admin\model\Config;
use app\admin\model\Order;
use app\admin\model\OrderZd;
use app\admin\model\PatientFile;
use app\common\model\ApiUserToken;
use app\common\model\Easemob;
use app\common\model\Hashids;
use app\common\model\VerifyModel;
use Endroid\QrCode\QrCode;
use think\Cache;
use think\Config as ThinkConfig;
use think\Db;
use think\Exception;
use think\Request;
use think\Session;

class User
{

    public static $sTableName = 'user';

    public static $sVerifyLogTable = 'zp_log_user_action';

    public static $sFiles = [
        'poster'=>['count'=>1, 'type'=>0, 'size'=>1048576],
        'cards'=>['count'=>3, 'type'=>0,'size'=>1048576]
    ];

    /**
     * 获取小鱼信息
     * @param $userId
     * @return array|null
     */
    public static function getXiaoYuInfo($userId){
        return Db::name(static::$sTableName)->where('user_id='.$userId)->field('xiaoyu_id,dayu_id')->find();
    }

    /**
     * 添加诊断次数
     * @param $shopId
     * @throws Exception
     */
    public static function addDiagnoseCount($shopId){
        Db::name(static::$sTableName)->where('user_id='.$shopId)->setInc('diagnose_count');
    }

    /**
     * 获取用户基本信息
     * @param $userId
     * @return array|null
     */
    public static function getUserBaseInfo($userId){
        return Db::name(static::$sTableName)->alias('u')->field('u.real_name,u.bank_number,u.bank_name,h.hospital_name,d.department_name')
            ->join('p_hospital h', 'h.hospital_id=u.hospital_id')
            ->join('p_hospital_department d', 'd.department_id=u.department_id')
            ->where('u.user_id='.$userId)->find();
    }

    /**
     * 获取用户所在医院
     * @param $userId
     * @return string|null
     */
    public static function getHospitalName($userId){
        return Db::name(static::$sTableName)->alias('u')->join('p_hospital h', 'h.hospital_id=u.hospital_id')->where('u.user_id='.$userId)->value('h.hospital_name');
    }

    /**
     * 获取用户所在医院和部门
     * @param $userId
     * @return array
     */
    public static function getHosNameAndDepName($userId){
        $userInfo = Db::name(static::$sTableName)->alias('u')
            ->join('p_hospital h', 'h.hospital_id=u.hospital_id')
            ->join('p_hospital_department d', 'd.department_id=u.department_id')
            ->where('u.user_id='.$userId)
            ->field('h.hospital_name,d.department_name')->find();
        return $userInfo == null ? ['hospital_name'=>'', 'department_name'=>''] : $userInfo;
    }

    /**
     * 获取环信信息
     * @param $mobile
     * @return array|false|\PDOStatement|string|\think\Model
     */
    public static function getHxInfo($mobile){
        return Db::name(static::$sTableName)->field('real_name,poster')->where('mobile like :mobile')->bind(['mobile'=>$mobile])->find();
    }


    /**
     * 获取诊断费用和申请费用
     * @param $userId
     * @return int|string
     */
    public static function getDiagnosePrice($userId){
        $shop = Db::name(static::$sTableName)->field('tag,hospital_id')->where('user_id='.$userId)->find();
        if($shop == null){
            return 0;
        }
        $hospitalDiagnosePrice = Hospital::getDiagnosePrice($shop['hospital_id']);
        $rate = Config::getDiagnosePriceByType($shop['tag']);
        return [bcmul($hospitalDiagnosePrice,$rate[0], 2), bcmul($hospitalDiagnosePrice,$rate[1], 2)];
    }

    /**
     * 获取用户电子签名
     * @param $userId
     * @return string
     */
    public static function getSignByUserId($userId){
        return Db::name(static::$sTableName)->where('user_id='.$userId)->value('dz_sign', '');
    }

    /**
     * 获取用户身份 格式为：医院+部门+姓名
     * @param $userId
     * @return string
     */
    public static function getDoctorInfo($userId){
        $user = static::getUserBaseInfo($userId);
        return $user == null ? '' : $user['hospital_name'].$user['department_name'].$user['real_name'];
    }



    /**
     * 获取用户手机号
     * @param $userId
     * @return string
     */
    public static function getMobileByUserId($userId){
        return Db::name(static::$sTableName)->where('user_id='.$userId)->value('mobile', '');
    }

    /**
     * 获取会诊专家信息
     * @param $shopId
     * @return array|false|\PDOStatement|string|\think\Model
     */
    public static function getHZInfo($shopId){
        return Db::name(static::$sTableName)->alias('u')->field('u.user_id as shop_id,u.mobile,u.real_name,u.dayu_id,u.xiaoyu_id,h.hospital_name')
            ->join('p_hospital h', 'h.hospital_id=u.hospital_id')
            ->where('u.user_id='.$shopId)->find();
    }

    /**
     * 获取会诊专家信息
     * @param $shopId
     * @return array|false|\PDOStatement|string|\think\Model
     */
    public static function getIndexHZInfo($shopId){
        return Db::name(static::$sTableName)->alias('u')->field('u.user_id as shop_id,u.mobile,u.real_name,h.hospital_name,d.department_name')
            ->join('p_hospital h', 'h.hospital_id=u.hospital_id')
            ->join('p_hospital_department d', 'd.department_id=u.department_id')
            ->where('u.user_id='.$shopId)->find();
    }

    /**
     * 获取专家
     * @param $shopId
     * @param $userId
     * @return $this|null
     */
    public static function getDoctor($shopId, $userId){
        $shop = Db::name(static::$sTableName)->field('user_id as shop_id,hospital_id,department_id,poster,real_name,profession,tag,intro,dayu_id,xiaoyu_id,mobile')->where('user_id='.$shopId)->find();
        if($shop == null){
            return null;
        }
        if($shopId == $userId){
            $shop['diagnose_count'] = 0;
        }else{
            $shop['diagnose_count'] = Order::getDoctorDiagnoseCount($userId, $shopId);
        }
        $shop['is_conllect'] = Collect::isCollect($userId, $shopId);
        $shop['hospital_name'] = $shop['hospital_id']>0 ? Hospital::getHospitalNameById($shop['hospital_id']) : '';
        $shop['depart_name'] = $shop['department_id']>0 ? HospitalDepartment::getDepartmentNameById($shop['department_id']) : '';
        unset($shop['hospital_id'], $shop['department_id']);
        return $shop;
    }

    /**
     * 通过搜索名获取专家列表
     * @param $searchName
     * @param $userId
     * @return false|\PDOStatement|string|\think\Collection
     */
    public static function getDoctorsBySearchName($searchName, $userId){
        $searchName = '%'.$searchName.'%';
        $users = Db::name(static::$sTableName)->alias('u')
            ->field('u.user_id as shop_id,u.real_name,h.hospital_name,d.department_name,u.profession,u.poster,u.tag,u.diagnose_count,u.dayu_id,u.xiaoyu_id,IFNULL(c.collect_id, 0) as collect_id')
            ->join('p_hospital h', 'h.hospital_id=u.hospital_id')
            ->join('p_hospital_department d', 'd.department_id=u.department_id')
            ->join('p_user_collect c', 'c.user_id='.$userId.' AND c.shop_id=u.user_id', 'LEFT')
            ->where('u.type=1 AND u.status=2 AND u.user_id!='.$userId.' AND (u.real_name like :real_name OR h.hospital_name like :hospital_name OR d.department_name like :department_name)')
            ->bind(['real_name'=>$searchName, 'hospital_name'=>$searchName, 'department_name'=>$searchName])
            ->limit(0, 20)
            ->order('u.diagnose_count desc,u.user_id desc')
            ->select();
        return $users;
    }

    /**
     * 获取专家列表
     * @param $where
     * @param $userId
     * @param $page
     * @return array
     */
    public static function getDoctors($where, $userId, $page){
        $where['u.type'] = 1;
        $where['u.status'] = 2;
        $where['u.user_id'] = ['<>', $userId];
        $users = Db::name(static::$sTableName)->alias('u')
            ->field('u.user_id as shop_id,u.real_name,h.hospital_name,d.department_name,u.profession,u.poster,u.tag,u.diagnose_count,u.dayu_id,u.xiaoyu_id,IFNULL(c.collect_id, 0) as collect_id')
            ->join('p_hospital h', 'h.hospital_id=u.hospital_id')
            ->join('p_hospital_department d', 'd.department_id=u.department_id')
            ->join('p_user_collect c', 'c.user_id='.$userId.' AND c.shop_id=u.user_id', 'LEFT')
            ->where($where)
            ->limit($page*10, 10)
            ->order('u.diagnose_count desc,u.user_id desc')
            ->select();
        return $users;
    }

    /**
     * 获取专家列表
     * @param $hospitalId
     * @param $userId
     * @return array
     */
    public static function getIndexPushDoctors($hospitalId, $userId){
        $where['u.type'] = 1;
        $where['u.hospital_id'] = $hospitalId;
        $where['u.status'] = 2;
        $where['u.user_id'] = ['<>', $userId];
        return Db::name(static::$sTableName)->alias('u')
            ->field('u.user_id as shop_id,u.real_name,h.hospital_name,d.department_name')
            ->join('p_hospital h', 'h.hospital_id=u.hospital_id')
            ->join('p_hospital_department d', 'd.department_id=u.department_id')
            ->where($where)
            ->order('u.diagnose_count desc,u.user_id desc')
            ->select();
    }

    /**
     * 获取专家列表
     * @param $where
     * @param $userId
     * @param $page
     * @return array
     */
    public static function getIndexDoctors($where, $userId, $page){
        $where['u.type'] = 1;
        $where['u.status'] = 2;
        $where['u.user_id'] = ['<>', $userId];
        $users = Db::name(static::$sTableName)->alias('u')
            ->field('u.user_id as shop_id,u.real_name,h.hospital_name,d.department_name,u.profession,u.poster,u.tag,u.diagnose_count')
            ->join('p_hospital h', 'h.hospital_id=u.hospital_id')
            ->join('p_hospital_department d', 'd.department_id=u.department_id')
            ->where($where)
            ->limit(($page-1)*6, 6)
            ->order('u.diagnose_count desc,u.user_id desc')
            ->select();
        return $users;
    }

    /**
     * 获取总的数量
     * @param $where
     * @param $userId
     * @return int|string
     */
    public static function getIndexDoctorCount($where, $userId){
        $where['u.type'] = 1;
        $where['u.status'] = 2;
        $where['u.user_id'] = ['<>', $userId];
        return (int)Db::name(static::$sTableName)->alias('u')->where($where)->count();
    }

    /**
     * 获取档案的用户信息
     * @param $where
     * @return array
     */
    public static function getUsers($where){
        $users = Db::name(static::$sTableName)->alias('u')
            ->field('u.user_id,u.real_name,h.hospital_name,d.department_name')
            ->join('p_hospital h', 'h.hospital_id=u.hospital_id')
            ->join('p_hospital_department d', 'd.department_id=u.department_id')
            ->where($where)
            ->select();
        $fUsers = [];
        foreach($users as $k=>$v){
            $fUsers[$v['user_id']] = $v;
        }
        return $fUsers;
    }

    /**
     * 获取订单的用户信息
     * @param $where
     * @return array
     */
    public static function getOrderUsers($where){
        $users = Db::name(static::$sTableName)->alias('u')
            ->field(['u.user_id AS shop_id','u.real_name','u.poster','u.tag','CONCAT(h.hospital_name,\'/\',d.department_name) AS hospital_info'])
            ->join('p_hospital h', 'h.hospital_id=u.hospital_id')
            ->join('p_hospital_department d', 'd.department_id=u.department_id')
            ->where($where)
            ->select();
        $fUsers = [];
        foreach($users as $k=>$v){
            $fUsers[$v['shop_id']] = $v;
        }
        return $fUsers;
    }

    /**
     * 修改密码
     * @param $mobile
     * @param $password
     * @return bool
     * @throws Exception
     */
    public static function editPass($mobile, $password){
        $user = Db::name(static::$sTableName)->where(['mobile'=>$mobile])->find();
        Db::name(static::$sTableName)->where(['mobile'=>$mobile])->update(['password'=>static::makePassword($password)]);
        static::log(['user_id'=>$user['user_id'], 'action_type'=>2, 'log'=>'修改了密码', 'important_info'=>json_encode($user)]);
        ApiUserToken::newInstance()->delTokenKey($mobile);
        return true;
    }

    /**
     * 修改交易密码
     * @param $mobile
     * @param $password
     * @return bool
     * @throws Exception
     */
    public static function editTradePass($mobile, $password){
        $user = Db::name(static::$sTableName)->where(['mobile'=>$mobile])->find();
        Db::name(static::$sTableName)->where(['mobile'=>$mobile])->update(['trade_password'=>static::makePassword($password)]);
        static::log(['user_id'=>$user['user_id'], 'action_type'=>2, 'log'=>'修改了交易密码', 'important_info'=>json_encode($user)]);
        return true;
    }

    /**
     * 修改个人信息
     * @param $userId
     * @param $data
     * @return bool
     * @throws Exception
     */
    public static function edit($userId, $data){
        $user = Db::name(static::$sTableName)->where(['user_id'=>$userId])->find();
        Db::name(static::$sTableName)->where('user_id='.$userId)->update($data);
        static::log(['user_id'=>$userId, 'action_type'=>3, 'log'=>'修改了信息', 'important_info'=>json_encode($user)]);
        return true;
    }

    /**
     * 注册
     * @param $data
     * @return int
     */
    public static function reg($data){
        $verify = $data['verify'];unset($data['verify']);
        $data['password'] = static::makePassword($data['password']);
        $data['trade_password'] = static::makePassword($data['trade_password']);
//        $easemob = new Easemob();
//        $res = $easemob->createUser($data['mobile'], $data['hx_password']);
//        if(isset($res['entities'][0]['uuid'])){
        $time = time()%10000;
        $length = strlen($time);
        if($length < 4){
            $length = 4 - $length;
        }else{
            $length = 0;
        }
        $min = ($length+1)*10;
        $max = ($length+2)*10 -1;
            $data['vip_number'] = 'LTC'.date('y').$time.mt_rand(10,99).mt_rand($min,$max);
            $userId = static::add($data);
            if($userId>0){
                VerifyModel::flushVerify($verify, VerifyModel::TYPE_REG, $data['mobile']);
                static::log(['user_id'=>$userId, 'action_type'=>0, 'log'=>'注册', 'important_info'=>json_encode($data)]);

                //更新邀请码
                $hashIds = new Hashids('ltcltc123', 6);
                $code = $hashIds->encode($userId);
                Db::name('user')->where('user_id', $userId)->update(['invitation_code'=>$code]);

                //更新团队人数
                if($data['parent_id']!=0) {
                    $parentids = explode('|',substr($data['parent_ids'], 1, strlen($data['parent_ids'])-2));
                    Db::name('user')->where('user_id', 'in', $parentids)->update(['group_count' => ['exp', 'group_count+1']]);
                    Db::name('user')->where('user_id='.$data['parent_id'])->update(['zt_count' => ['exp', 'zt_count+1']]);
                }

                $domain = ThinkConfig::get('upload_file_domain').'/index/index/register?invitation_code='.$code;
                $fileName = md5($domain).'.png';
                $qrCode =  new QrCode();//创建生成二维码对象
                $qrCode->setText($domain)
                    ->setSize(224)
                    ->setForegroundColor(['r'=>0,'g'=>0,'b'=>0,'a'=>1])
                    ->setMargin(16);
                //先创建文件
                $path = ROOT_PATH . 'public' . DS . 'uploads'.DS.'qrcode'.DS.$fileName;
                $qrCode->writeFile($path);
                return $userId;
            }
//            else{
//                $easemob->deleteUser($data['mobile']);
//            }
//        }
        return 0;
    }

    public static function format($user, $isFlush=true){
        if($user!=null){
            if($isFlush){
                $user['token'] = static::updateUniqueToken($user['mobile'],$user['user_id']);
            }else{
                $user['token'] = Request::instance()->post('token', '');
            }
            $user['hospital_name'] = $user['hospital_id']>0 ? Hospital::getHospitalNameById($user['hospital_id']) : '';
            $user['depart_name'] = $user['department_id']>0 ? HospitalDepartment::getDepartmentNameById($user['department_id']) : '';
            $user['cards'] = $user['cards']==null ? [] : explode(',', $user['cards']);
            $user['patient_count'] = PatientFile::getUserFileCount($user['user_id']);
            $user['wait_count'] = Order::getUserWaitCount($user['user_id']);
            $user['complete_count'] = Order::getUserDiagnoseCount($user['user_id']);
            $user['receive_count'] = Order::getUserReceiveCount($user['user_id']);
            $user['send_count'] = Order::getUserSendCount($user['user_id']);
            $user['money'] = OrderZd::getUserTotalPrice($user['user_id']);
            unset($user['user_id'], $user['nick_name'],$user['password']);
        }
        return $user;
    }

    /**
     * 根据手机号获取用户信息
     * @param $mobile
     * @return array
     */
    public static function getUserInfoByMobile($mobile){
        return Db::name(static::$sTableName)->where('mobile like :mobile')->bind(['mobile'=>$mobile])->find();
    }

    /**
     * 根据用户ID获取用户信息
     * @param $userId
     * @return array
     */
    public static function getUserInfoByUserId($userId){
        $user = Db::name(static::$sTableName)->where('user_id='.$userId)->find();
        if($user==null){
            Session::delete('userId');
            return null;
        }
        //$user['vip_number'] = 'LTC'.date('ym',strtotime($user['c_time'])).sprintf('%05d', $userId);
        empty($user['poster']) && $user['poster'] = '/static/index/image/head.gif';
        $user['c_time'] = date('Y-m-d', strtotime($user['c_time']));
        return $user;
    }

    /**
     * 根据手机号获取用户ID
     * @param $mobile
     * @return int
     */
    public static function getUserIdByMobile($mobile){
        return (int)Db::name(static::$sTableName)->where('mobile like :mobile')->bind(['mobile'=>$mobile])->value('user_id');
    }

    /**
     * 根据手机号获取用户数量
     * @param $mobile
     * @return int
     */
    public static function getUserCountByMobile($mobile){
        return Db::name(static::$sTableName)->where('mobile like :mobile')->bind(['mobile'=>$mobile])->count();
    }

    /**
     * 更新用户的token,唯一用户
     * @param $mobile string 手机号
     * @param $userId int    用户主键
     * @return string 返回用户的token
     */
    public static function updateUniqueToken($mobile, $userId)
    {
        $newToken = static::makeToken($mobile);
        ApiUserToken::newInstance()->updateUniqueApiToken($mobile, $userId, $newToken);
        return $newToken;
    }

    /**
     * 添加用户
     * @param array $data 用户数据
     * @return int 新用户的主键
     */
    public static function add($data)
    {
        return Db::name(static::$sTableName)->insert($data, false, true);
    }

    /**
     * 生成保存的密码
     * @param $password string 明文密码
     * @return string 密码
     */
    public static function makePassword($password)
    {
        return md5(md5($password . ThinkConfig::get('password_prefix')));
    }

    /**
     * 生成保存的token
     * @param $mobile string 手机号
     * @return string token字符串
     */
    public static function makeToken($mobile)
    {
        return md5(md5($mobile . ThinkConfig::get('token_prefix') . time()));
    }


    /**
     * 记录用户操作日志
     * @param $logData array 日志信息
     */
    public static function log($logData)
    {
        $logData['ip'] = Request::instance()->ip();
        $logData['request_type'] = Request::instance()->post('request_type', 99);
        Db::table(static::$sVerifyLogTable)->insert($logData);
    }
}
