<?php
namespace app\common\controller;

use app\api\model\User;
use app\common\model\ApiUserToken;
use app\common\validate\BaseValidate;
use think\Cache;
use think\Config;
use think\Controller;
use think\Db;
use think\exception\HttpResponseException;
use think\Loader;
use think\Response;

/**
 * Class ApiController
 * @package app\common\controller
 * @version 2.1
 * @msg     修改权限认证直接为action
 */
class ApiController extends BaseController
{
    const FAIL_CODE = 404;      //失败code
    const SUCCESS_CODE = 200;   //成功code
    const SESSION_CODE = 2001;  //token验证失败code
    const ILLEGAL_CODE = 201;  //非法操作code
    const API_ERROR_CODE = 5000;  //非法操作code


    private $mTimeKey = '943ea691b02c404c6bb3c3190cfc0562';   //time加密key

    /**
     * @var array 键=》查权限  action
     */
    private $mPermissionIndex;

    /**
     * @var int 用户ID
     */
    protected $userId;

    /**
     * @var BaseValidate
     */
    protected $validate;

    /**
     * @var array
     */
    protected $requestPostData;

    /**
     * @var array 1普通加密认证  3赋值data 7检查token
     */
    protected static $sPermissionArr;

    /**
     * @var array 1不是必传  2必传
     */
    protected static $sParamsArr;

    protected $userInfo;

    /**
     * 初始操作
     */
    protected function init(){
        $this->mPermissionIndex = $this->request->action();
        if(!isset(static::$sPermissionArr[$this->mPermissionIndex])){
            throw new HttpResponseException($this->json(['code' => self::API_ERROR_CODE, 'msg' => '该接口不存在了']));
        }
        $permission = static::$sPermissionArr[$this->mPermissionIndex];
        if (!empty($permission)) {

            //check 加密
            ($permission & 1) && $this->checkPermission();
            //check  Post值
            if($permission & 2){
                $this->setRequestData();
                //验证器验证
                $class = $this->request->controller();
                $this->validate = Loader::validate($class . 'Validate');
                if (!$this->validate->scene($this->mPermissionIndex)->check($this->requestPostData)) {
                    throw new HttpResponseException($this->jsonFail($this->validate->getError()));
                }
            }
            //检查token值
            ($permission & 4) && $this->checkToken();
            //检查用户状态
            ($permission & 8) && $this->checkUserStatus();
        }
    }

    private function checkUserStatus(){
        $user = User::getUserInfoByUserId($this->userId);
        if($user['status'] == 1){
            throw new HttpResponseException($this->jsonFail('您的信息还未审核不能操作'));
        }
        if($user['status'] == 3){
            throw new HttpResponseException($this->jsonFail('您的信息审核不通过'));
        }
        $this->userInfo = $user;
    }


    /**
     * 检查权限
     */
    private function checkPermission()
    {
        $timeKey = (int)$this->request->post('time');
        $timeValue = $this->request->post('value');
        /*$time = time();
        echo $time,'=======',md5('ruishitong_time_key'),'=======',md5(md5($time . md5($this->mTimeKey)));exit;*/
        //$time = time();&& $time<$timeKey+10
        if(Config::get('app_debug')){
            if (!($timeKey && $timeValue && $timeValue == md5(md5($timeKey . $this->mTimeKey)))) {
                throw new HttpResponseException($this->json(['code' => self::ILLEGAL_CODE, 'msg' => '非法请求']));
            }
        }else {
            if (!($timeKey && $timeValue && time() < $timeKey + 10 && $timeValue == md5(md5($timeKey . $this->mTimeKey)))) {
                throw new HttpResponseException($this->json(['code' => self::ILLEGAL_CODE, 'msg' => '非法请求']));
            }
        }
    }

    private function setRequestData()
    {
        /*加密算法
        $dataKey = $this->request->post('data');
        if (!empty($dataKey)) {
            $dataJson = DESArith::decrypt($dataKey);
            $data = json_decode($dataJson, true);
            if (empty($data)) {
                throw new HttpResponseException(Response::create(['code' => self::FAIL_CODE, 'msg' => '数据解析失败'], 'json', 200, [], []));
            }
            $this->requestPostData = $data;
        }
        */

        $data = $this->request->post();
        unset($data['time'], $data['value'], $data['request_type']);

        if (empty($data)) {
            throw new HttpResponseException($this->json(['code' => self::API_ERROR_CODE, 'msg' => '这个接口需要传time、value以外的参数哦！']));
        }
        if(isset(static::$sParamsArr[$this->mPermissionIndex])) {
            $paramsPermissionArr = static::$sParamsArr[$this->mPermissionIndex];
        }else{
            throw new HttpResponseException($this->json(['code' => self::API_ERROR_CODE, 'msg' => '联系后台开发攻城师，这个接口需要传参！']));
        }
        $this->requestPostData = $data;
        //检测传入数据字段
        foreach($paramsPermissionArr as $k=>$v){
            if(isset($data[$k])){
                unset($data[$k]);
            }elseif($v == 2){
                throw new HttpResponseException($this->json(['code' => self::API_ERROR_CODE, 'msg' => '字段' . $k . '必传']));
            }
        }
        if(!empty($data)){
            throw new HttpResponseException($this->json(['code'=>self::API_ERROR_CODE, 'msg'=>'字段'.implode(',', array_keys($data)).'不需要传吧']));
        }
    }

    private function checkToken()
    {
        if(!$this->checkTokenData()){
            throw new HttpResponseException(Response::create(['code' => self::SESSION_CODE, 'msg' => '用户信息失效'], 'json', 200, [], []));
        }
    }

    protected function checkTokenData()
    {
        if (empty($this->requestPostData['token'])) {
            return false;
        }
        $apiUserToken = ApiUserToken::newInstance();
        $userId = $apiUserToken->getToken($this->requestPostData['token']);
        if (!empty($userId)) {
            $apiUserToken->updateApiTokenTime($this->requestPostData['token']);
            $this->userId = $userId;
            unset($this->requestPostData['token']);
            return true;
        }
        return false;
    }

    /**
     * 失败  json类型响应数据
     * @param $msg
     * @param array $data
     * @return Response|\think\response\Json|\think\response\Jsonp|\think\response\Redirect|\think\response\View|\think\response\Xml
     */
    public function jsonFail($msg, $data=[]){
        $result = empty($data) ? ['code'=>self::FAIL_CODE, 'msg'=>$msg] : ['code'=>self::FAIL_CODE, 'msg'=>$msg, 'data'=>$data];
        return $this->json($result);
    }

    /**
     * 成功  json类型响应数据
     * @param $msg
     * @param array $data
     * @return Response|\think\response\Json|\think\response\Jsonp|\think\response\Redirect|\think\response\View|\think\response\Xml
     */
    public function jsonSuccess($msg, $data=[]){
        //$result = empty($data) ? ['code'=>self::SUCCESS_CODE, 'msg'=>$msg] : ['code'=>self::SUCCESS_CODE, 'msg'=>$msg, 'data'=>$data];
        $result = ['code'=>self::SUCCESS_CODE, 'msg'=>$msg, 'data'=>$data];
        return $this->json($result);
    }
}
