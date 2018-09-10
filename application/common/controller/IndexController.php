<?php
// +----------------------------------------------------------------------
// | KingTP
// +----------------------------------------------------------------------
// | 2018/2/2 10:34
// +----------------------------------------------------------------------
// | **基于ThinkPHP 5.0.14 + LayUI2.2.5封装**
// +----------------------------------------------------------------------
// | Author: 晓晓攻城师 <邮箱：1228676735@qq.com><博客：http://blog.csdn.net/qq_26245325>
// +----------------------------------------------------------------------

namespace app\common\controller;


use app\api\model\User;
use app\common\validate\BaseValidate;
use think\exception\HttpResponseException;
use think\Loader;
use think\response\Redirect;
use think\Session;
use think\config as ThinkConfig;

class IndexController extends ViewController
{

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
    protected $requestData;

    /**
     * @var array 1没有数据  3有数据 7检查session 15检查权限
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
        //dump($_POST);
        $this->mPermissionIndex = $this->request->action();
        if(!isset(static::$sPermissionArr[$this->mPermissionIndex])){
            $this->debugInfo('该链接已失效');
        }
        $permission = static::$sPermissionArr[$this->mPermissionIndex];
        if (!empty($permission)) {
            //check 加密
            //($permission & 1) && $this->checkPermission();
            //check  Post值
            if($permission & 2){
                $this->setRequestData();
                //验证器验证
                $class = $this->request->controller();
                $this->validate = Loader::validate($class . 'Validate');
                if (!$this->validate->scene($this->mPermissionIndex)->check($this->requestData)) {
                    $this->debugInfo($this->validate->getError());
                }
            }
            //检查token值
            ($permission & 4) && $this->checkToken();
            //检查用户状态
            ($permission & 8) && $this->checkUserStatus();
        }
    }

    private function checkPermission(){}

    private function setRequestData(){
        $paramsPermissionArr = [];
        if(isset(static::$sParamsArr[$this->mPermissionIndex])) {
            $paramsPermissionArr = static::$sParamsArr[$this->mPermissionIndex];
        }else{
            $this->debugInfo('配置错误,检查相关控制器');
        }
        //检测传入数据字段
        foreach($paramsPermissionArr as $k=>$v){
            $value = $this->request->param($k);
            if($v==2 && $value === null){
                $this->debugInfo('字段' . $k . '必传');
            }
            $this->requestData[$k] = $value;
        }
        /*if(empty($this->requestData)){
            $this->debugInfo('需要传参');
        }*/
    }

    private function checkToken(){
        $this->userId = (int)Session::get('userId');
        if($this->userId<=0){
            $returnUrl = ThinkConfig::get('nologin_redirect');
            $response = $this->request->isAjax() || $this->request->isPost() ?
                $this->json(['code' => self::ILL_CODE, 'msg' => '用户信息失效，请重新登录', ['url'=>$returnUrl]]) : new Redirect($returnUrl);
            throw new HttpResponseException($response);
        }else{
            $this->userInfo = User::getUserInfoByUserId($this->userId);
            if($this->userInfo == null){
                $returnUrl = ThinkConfig::get('nologin_redirect');
                $response = $this->request->isAjax() || $this->request->isPost() ?
                    $this->json(['code' => self::ILL_CODE, 'msg' => '用户信息失效，请重新登录', ['url'=>$returnUrl]]) : new Redirect($returnUrl);
                throw new HttpResponseException($response);
            }
            if ($this->userInfo['is_del'] > 0) {
                Session::delete('userId');
                $returnUrl = ThinkConfig::get('nologin_redirect');
                $response = $this->request->isAjax() || $this->request->isPost() ?
                    $this->json(['code' => self::ILL_CODE, 'msg' => '用户信息失效，请重新登录', ['url'=>$returnUrl]]) : new Redirect($returnUrl);
                throw new HttpResponseException($response);
            }
            $this->assign(['user'=>$this->userInfo]);
        }
    }

    private function checkUserStatus(){
        if($this->userInfo['status'] == 1){
            $this->debugInfo('您的信息还未审核不能操作');
        }
        if($this->userInfo['status'] == 3){
            $this->debugInfo('您的信息审核不通过');
        }
    }

    protected function debugInfo($msg){
        if($this->request->isPost()){
            throw new HttpResponseException($this->json(['code' => self::FAIL_CODE, 'msg' => $msg]));
        }else{
            die($msg);
        }
    }

    protected function getRequestData($key, $default=null){
        return isset($this->requestData[$key]) ? $this->requestData[$key] : $default;
    }


}