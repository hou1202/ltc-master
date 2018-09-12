<?php
namespace app\admin\controller;


use app\common\controller\AdminCheckLoginController;
use app\common\model\CommonUtils;
use think\Db;

class Miner extends AdminCheckLoginController
{

    public function init()
    {
        parent::init();
    }

    public function index()
    {
        if ($this->request->isPost()) {
            $order = $this->request->post('sortField').' '.$this->request->post('sortType');
            $page = (int)$this->request->post('page');
            $limit = (int)$this->request->post('limit');
            $name = $this->request->post('searchName', '');
            $searhDate = $this->request->post('searchDate', '');
            $where = '';
            !empty($name) && $where = CommonUtils::concatWhere($where, ' (u.mobile like \'%'.$name.'%\' OR u.real_name like \'%'.$name.'%\' OR u.invitation_code like \'%'.$name.'%\')');
            if(!empty($searhDate)){
                $dates = explode(' - ', $searhDate);
                $where =  CommonUtils::concatWhere($where, ' m.c_time>=\''.$dates[0].'\' AND m.c_time<=\''.$dates[1].'\'');
            }
            $totalCount = $this->model->totalCount($where);
            $list = $totalCount > 0 ? $this->model->index($where, $page, $limit, $order) : [];
            $data = ['code' => 0, 'msg' => 'Success', 'data' => $list, 'count' => $totalCount];
            return $this->json($data);
        } else {
            $assign['page'] = $this->request->get('page', 1);
            $assign['sortType'] = $this->request->get('sortType', 'desc');
            $assign['sortField'] = $this->request->get('sortField', $this->view->primaryKey);
            $assign['searchName'] = $this->request->get('searchName','');
            $assign['searchDate'] = $this->request->get('searchDate','');
            $this->assign($assign);
            return $this->fetch();
        }
    }

    public function add()
    {
        /*if($this->request->isPost()){
            $data = $this->request->post();

            if($this->modelFactory->add($data)){
                return $this->jsonSuccess('添加成功');
            }else{
                return $this->jsonFail('添加失败');
            }
        }else{
            $userId = $this->request->get('userId', 0);
            $users = Db::name('user')->field('user_id,mobile,real_name,invitation_code')->select();
            $this->assign(['users'=>$users, 'userId'=>$userId]);
            return $this->fetch();
        }*/
    }

    public function edit()
    {
        if($this->request->isPost()){
            $data = $this->request->post();
            //获取该矿机用户数据
            $miner = Db::name('miner')->alias('m')->field('m.id,m.number,m.user_id,m.e_time,m.status,u.ky_money')
                ->where('m.id='.$data['id'])
                ->join('p_user u', 'u.user_id=m.user_id')
                ->find();
            //获取续期费用
            $config = Db::name('config')->field('content')->where('id=29')->find();
            //续期总金额
            $renew = bcmul($config['content'],$miner['number'],4);

            if($miner['ky_money'] < $renew){
                return $this->jsonFail('该用户可用资产不足，请先充值');
            }
             //续期结束时间
            if(strtotime($miner['e_time'])<time()){
                $endTime=date('Y-m-d H:i:s',time()+(365*24*60*60));
            }else{
                $endTime=date('Y-m-d H:i:s',strtotime($miner['e_time'])+(365*24*60*60));
            }

            Db::startTrans();
            try{
                //更新用户可用资产
                Db::name('user')->where('user_id=' . $miner['user_id'])->update([
                    'ky_money'=>['exp', 'ky_money-'.$renew],
                ]);
                Db::name('money_log')
                    ->insert(['user_id'=> $miner['user_id'],'money'=>$renew, 'sign'=>'-', 'remark'=>'矿机续期', 'type'=>13]);
                //更新矿机状态
                Db::name('miner')->where('id='.$miner['id'])->update([
                   'status' => 0,
                    'e_time' => $endTime,
                ]);
                Db::commit();
                return $this->jsonSuccess('矿机续期成功');
            }catch(\Exception $e){
                Db::rollback();
                if($e->getCode() == -2) {
                    return $this->jsonFail('矿机续期失败，请重试');
                }
            }
            return $this->jsonFail('未知错误');


        }
        return $this->jsonFail('无效的续期操作');
    }

    public function del()
    {
        /*if($this->modelFactory->del()){
            return $this->jsonSuccess('删除成功');
        }
        return $this->jsonFail('删除失败');*/
    }

}