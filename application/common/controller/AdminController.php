<?php
namespace app\common\controller;

use app\common\model\BaseAdminModel;
use app\common\utils\ModelFactory;
use app\common\validate\BaseValidate;
use think\exception\HttpResponseException;
use think\Loader;
use think\Response;

class AdminController extends BaseController
{

    const FAIL_CODE = 0;      //失败code
    const SUCCESS_CODE = 1;   //成功code
    const SESSION_CODE = -1;  //登录信息失效
    const ILLEGAL_CODE = -2;  //没有权限

    protected $systemManagerId;

    /**
     * @var ModelFactory
     */
    protected $modelFactory;

    /**
     * @var BaseAdminModel
     */
    protected $model;

    protected static $sPermissionArr;

    protected static $sModelClass;



    /**
     * @var BaseValidate
     */
    protected $validate;

    /**
     * 初始操作
     */
    protected function init(){
        $action = $this->request->action();
        $class = (empty(static::$sModelClass) ? $this->request->controller() : static::$sModelClass);
        if($this->request->isPost()){
            if($action!='index') {
                $this->validate = Loader::validate($class . 'Validate');
                if (!$this->validate->scene($action)->check($this->request->post())) {
                    throw new HttpResponseException($this->jsonFail($this->validate->getError()));
                }
                $this->model = $this->validate->getModel();
            }
            if($this->model==null){
                $this->model = Loader::model($class,'model',false,'admin');
            }
            $this->modelFactory = ModelFactory::newInstance($this->model);
        }else{
            $this->model = Loader::model($class,'model',false,'admin');
            if($action!='index') {
                $url = $this->request->url();
                $this->assign(['urlParam' => mb_substr($url, mb_strpos($url, '?') + 1)]);
            }
        }
    }


    /**
     * 失败  json类型响应数据
     * @param $msg
     * @param array $data
     * @return Response|\think\response\Json|\think\response\Jsonp|\think\response\Redirect|\think\response\View|\think\response\Xml
     */
    public function jsonFail($msg, $data=[]){
        return $this->json(['code'=>self::FAIL_CODE, 'msg'=>$msg, 'data'=>$data]);
    }

    /**
     * 成功  json类型响应数据
     * @param $msg
     * @param array $data
     * @return Response|\think\response\Json|\think\response\Jsonp|\think\response\Redirect|\think\response\View|\think\response\Xml
     */
    public function jsonSuccess($msg, $data=[]){
        return $this->json(['code'=>self::SUCCESS_CODE, 'msg'=>$msg, 'data'=>$data]);
    }

}