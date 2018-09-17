<?php
namespace app\admin\controller;


use app\admin\model\News;
use app\admin\model\NewsCategory;
use app\common\controller\BaseController;
use think\Db;
use think\Response;

class TextType extends BaseController
{
    private $model;

    public function map(){
        return $this->fetch();
    }

    public function article(){
        $this->model = new News();
        if ($this->request->isPost()) {
            $order = $this->request->post('sortField').' '.$this->request->post('sortType','desc');
            $page = (int)$this->request->post('page');
            $limit = (int)$this->request->post('limit');
            $name = $this->request->post('searchName', '');
            $searhDate = $this->request->post('searchDate', '');
            $categoryId = $this->request->post('category_id', ' ');
            $where = 'is_del=0';
            !empty($name) && $where .= ' AND title like \'%'.$name.'%\'';
            if(!empty($searhDate)){
                $dates = explode(' - ', $searhDate);
                $where .= ' AND c_time>=\''.$dates[0].'\' AND c_time<=\''.$dates[1].'\'';
            }
            $categoryId !== ' ' && $where .= ' AND category_id='.$categoryId;
            $totalCount = $this->model->totalCount($where);
            $list = $totalCount > 0 ? $this->model->index($where, $page, $limit, $order) : [];
            $data = ['code' => 0, 'msg' => 'Success', 'data' => $list, 'count' => $totalCount];
            return $this->json($data);
        } else {
            $assign['primaryKey'] = 'category_id';
            $assign['title'] = '文章列表';
            $assign['loadUrl'] = '/admin/text_type/article';
            $assign['page'] = $this->request->get('page', 1);
            $assign['sortType'] = $this->request->get('sortType', 'desc');
            $assign['sortField'] = $this->request->get('sortField', 'category_id');
            $assign['searchName'] = $this->request->get('searchName','');
            $assign['searchDate'] = $this->request->get('searchDate','');
            $assign['category_id'] = $this->request->get('category_id',' ');
            $assign['categorys'] = NewsCategory::all(['is_del'=>0]);
            $this->assign($assign);
            return $this->fetch();
        }
    }

    /**
     * 成功返回的json
     * @param $data array
     * @return Response|\think\response\Json|\think\response\Jsonp|\think\response\Redirect|\think\response\View|\think\response\Xml
     */
    public function jsonSuccess($msg, $data)
    {
        // TODO: Implement jsonSuccess() method.
    }

    /**
     * 失败返回的json
     * @param $data array
     * @return Response|\think\response\Json|\think\response\Jsonp|\think\response\Redirect|\think\response\View|\think\response\Xml
     */
    public function jsonFail($msg, $data)
    {
        // TODO: Implement jsonFail() method.
    }
}