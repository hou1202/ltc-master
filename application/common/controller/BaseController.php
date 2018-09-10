<?php
namespace app\common\controller;

use think\Config;
use think\Controller;
use think\Request;
use think\Response;
use think\View;

abstract class BaseController
{

    /**
     * @var \think\View 视图类实例
     */
    protected $view;

    /**
     * @var \think\Request 请求对象
     */
    protected $request;


    public function __construct()
    {
        $this->request = Request::instance();
        $this->init();
    }

    /**
     * 进入控制器的前置操作
     */
    protected function init(){}

    /**
     * 成功返回的json
     * @param $data array
     * @return Response|\think\response\Json|\think\response\Jsonp|\think\response\Redirect|\think\response\View|\think\response\Xml
     */
    public abstract function jsonSuccess($msg, $data);

    /**
     * 失败返回的json
     * @param $data array
     * @return Response|\think\response\Json|\think\response\Jsonp|\think\response\Redirect|\think\response\View|\think\response\Xml
     */
    public abstract function jsonFail($msg, $data);


    /**
     * @param $data array
     * @return Response|\think\response\Json|\think\response\Jsonp|\think\response\Redirect|\think\response\View|\think\response\Xml
     */
    protected function json($data=[]){
        return Response::create($data,'json');
    }

    /**
     * 模板变量赋值
     * @access protected
     * @param mixed $name  要显示的模板变量
     * @param mixed $value 变量的值
     * @return void
     */
    protected function assign($name, $value = '')
    {
        if($this->view == null){
            $this->view = View::instance(Config::get('template'), Config::get('view_replace_str'));
        }
        $this->view->assign($name, $value);
    }

    /**
     * 加载模板输出
     * @access protected
     * @param string $template 模板文件名
     * @param array  $vars     模板输出变量
     * @param array  $replace  模板替换
     * @param array  $config   模板参数
     * @return mixed
     */
    protected function fetch($template = '', $vars = [], $replace = [], $config = [])
    {
        if($this->view == null){
            $this->view = View::instance(Config::get('template'), Config::get('view_replace_str'));
        }
        return $this->view->fetch($template, $vars, $replace, $config);
    }

}
