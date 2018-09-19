<?php
namespace app\admin\controller;


use app\common\controller\AdminCheckLoginController;
use app\common\model\CommonUtils;
use think\Db;

class CbLog extends AdminCheckLoginController
{

    public function init()
    {
        parent::init();
    }

    public function index()
    {

        if ($this->request->isPost()) {
            $order = $this->request->post('sortField').' '.$this->request->post('sortType','desc');
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
        if($this->request->isPost()){
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
        }
    }

    public function edit()
    {
        if($this->request->isPost()){
            $data = $this->request->post();
            //$userId = $this->request->get('userId', 0);

            $cbLog = Db::name('cb_log')->field('id,user_id,count,is_kuang')->where('id='.$data['id'])->find();
            if($data['is_kuang'] == 1 && $cbLog['count']%500 != 0){
                return $this->jsonFail('购买数量非矿机基数的倍数');
            }

            //购买矿机操作
            if($data['status'] == 2 && $data['is_kuang'] == 1){
                //获取用户信息和父级树
                $user = Db::name('user')->field('user_id,parent_id,parent_ids,is_miner')->where('user_id='.$cbLog['user_id'])->find();

                //矿机数量
                $miner = intval($cbLog['count']/500);
                //插入矿机记录
                $miner_id = Db::name('miner')->insertGetId([
                    'user_id'=>$cbLog['user_id'],
                    'number'=>$miner,
                    'c_time'=>date('Y-m-d H:i:s'),
                    'e_time'=>date('Y-m-d H:i:s',time()+(365*24*60*60)),
                ]);

                //更新用户矿机数量
                $miner_data = [];
                $miner_data['miner_num'] = ['exp', 'miner_num+'.$miner];
                if($user['is_miner'] === 1){
                    $miner_data['is_miner'] = 2;
                }
                Db::name('user')->where('user_id='.$user['user_id'])->update($miner_data);

                //如果是第一次购买矿机，更新父级直推活跃矿机用户和等级
                if($user['is_miner'] == 1 && !empty($user['parent_id'])){

                    $parentUser = Db::name('user')->field('user_id,active_miner,grade')->where('user_id='.$user['parent_id'])->find();
                    switch($parentUser['active_miner']+1){
                        case 20:
                            $grade=2;
                            break;
                        case 50:
                            $grade=3;
                            break;
                        case 100:
                            $grade=4;
                            break;
                        case 200:
                            $grade=5;
                            break;
                        default:
                            $grade = $parentUser['grade'];
                            break;
                    }
                    Db::name('user')->where('user_id='.$parentUser['user_id'])->update([
                        'active_miner'=>['exp', 'active_miner+1'],
                        'grade'=>$grade,
                    ]);
                }

                //判断父级树是否为空,计算用户返利
                if($user['parent_ids'] != ''){
                    $parentIds = explode('|', substr($user['parent_ids'], 1, count($user['parent_ids'])-2));
                    //获取等级收益利率
                    $rate = Db::name('config')->field('content')->where('id','in',[17,18,19,20,23,24,25,26,27,28])->order('id asc')->select();
                    $sup_cont = Db::name('config')->field('content')->where('id','in',[36,37,38,39])->order('id asc')->select();
                    foreach($parentIds as $key =>$parent){
                        //计算收益
                        $parentIncome = bcmul($cbLog['count'],bcdiv($rate[$key]['content'],100,4),4);
                        if(bccomp($parentIncome, 0, 4)>0) {
                            Db::name('user')->where('user_id=' . $parent)->update([
                                'share_income' => ['exp', 'share_income+' .$parentIncome],
                                'to_share_income' => ['exp', 'to_share_income+' . $parentIncome],
                                'ky_money'=>['exp', 'ky_money+'.$parentIncome],
                                'miner_count'=>['exp','miner_count+'.$miner],
                            ]);
                            Db::name('money_log')
                                ->insert(['user_id'=> $parent,'order_id'=>$miner_id, 'money'=>$parentIncome, 'sign'=>'+', 'remark'=>'好友收益', 'type'=>6]);
                        }

                        //计算是否是用户超级返
                        if($sup_cont[0]['content'] > 0){
                            $sup_user = Db::name('user')->field('user_id,active_miner,miner_count,is_team')->where('user_id='.$parent)->find();
                            if($sup_user['is_team'] == 1 && $sup_user['active_miner'] == $sup_cont[1]['content'] && $sup_user['miner_count'] > $sup_cont[2]['content']){
                                Db::name('user')->where('user_id=' . $sup_user['id'])->update([
                                    'ky_money'=>['exp', 'ky_money+'.$sup_cont[3]['content']],
                                    'is_team'=>2,
                                ]);
                                Db::name('money_log')
                                    ->insert(['user_id'=> $sup_user['user_id'], 'money'=>$sup_cont[3]['content'], 'sign'=>'+', 'remark'=>'超级返利', 'type'=>2]);
                            }
                        }

                    }
                }

            }

            if($this->modelFactory->edit($data)){
                return $this->jsonSuccess('修改成功');
            }else{
                return $this->jsonFail('修改失败');
            }
        }else{
            $user =  Db::name('user')->where('user_id='.$this->model->user_id)->find();
            $this->assign('user',$user);
            $this->assign('address', Db::name('address')->where('id='.$user['address_id'])->find());
            return $this->fetch();
        }
    }

    public function del()
    {
        if($this->modelFactory->del()){
            return $this->jsonSuccess('删除成功');
        }
        return $this->jsonFail('删除失败');
    }

}