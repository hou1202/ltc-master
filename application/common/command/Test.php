<?php

namespace app\common\command;


use think\Config;
use think\console\Command;
use think\console\Input;
use think\console\Output;
use think\Db;

class Test extends Command
{

    protected function configure()
    {
        Config::load(APP_PATH . '../config/config.php');
        $loadConfig = Config::get('app_status');
        Config::load(APP_PATH . '../config/'.$loadConfig.'.php');
        $this->setName('test')->setDescription('Here is the remark');
    }

    protected function execute(Input $input, Output $output)
    {

        //清空所有用户今日锁仓收益
        Db::name('user')->where('user_id>0')->update(['today_income'=>0, 'to_share_income'=>0]);

        //计算用户锁仓收益、邀请收益
        //获取锁仓订单
        $incomeOrders = Db::name('lock_order')->field('id,user_id,income')
            ->where('status=0')
            ->select();

        //获取收益
        //$sys = Db::name('config')->field('content')->where('id in(17,18,19,20)')->order('id asc')->select();

        //遍历订单,计算用户理财收益
        foreach($incomeOrders as $v){
            /*//获取用户父级树
            $parentIds = Db::name('user')->where('user_id='.$v['user_id'])->value('parent_ids');
            //判断父级树是否为空
            if ($parentIds!= '') {
                //获得父级树数组
                $parentIds = explode('|', substr($parentIds, 1, count($parentIds)-2));
                if(isset($parentIds[0])) {
                     // bcmul    将两个高精度数字相乘
                     // bcdiv    将两个高精度数字相除
                     // $shareIncome1    一级父类收益
                    $shareIncome1 = bcmul($v['income'],bcdiv($sys[0]['content'],100,4),4);

                     // bccomp    比较两个高精度数字，返回-1,0,1

                    if(bccomp($shareIncome1, 0, 4)>0) {
                        Db::name('user')->where('user_id=' . $parentIds[0])->update([
                            'share_income' => ['exp', 'share_income+' .$shareIncome1],
                            'to_share_income' => ['exp', 'to_share_income+' . $shareIncome1],
                            'ky_money'=>['exp', 'ky_money+'.$shareIncome1],
                        ]);
                        Db::name('money_log')
                            ->insert(['user_id'=> $parentIds[0],'order_id'=>$v['id'], 'money'=>$shareIncome1, 'sign'=>'+', 'remark'=>'好友收益', 'type'=>6]);
                    }
                    if(isset($parentIds[1])) {
                        $shareIncome2 = bcmul($v['income'],bcdiv($sys[1]['content'],100,4),4);
                        if(bccomp($shareIncome2, 0, 4)>0) {
                            Db::name('user')->where('user_id=' . $parentIds[1])->update([
                                'share_income' => ['exp', 'share_income+' .$shareIncome2],
                                'to_share_income' => ['exp', 'to_share_income+' . $shareIncome2],
                                'ky_money'=>['exp', 'ky_money+'.$shareIncome2],
                            ]);
                            Db::name('money_log')
                                ->insert(['user_id'=> $parentIds[1], 'order_id'=>$v['id'], 'money'=>$shareIncome2, 'sign'=>'+', 'remark'=>'好友收益', 'type'=>6]);
                        }
                        if(isset($parentIds[2])) {
                            $shareIncome3 = bcmul($v['income'],bcdiv($sys[2]['content'],100,4),4);
                            if(bccomp($shareIncome3, 0, 4)>0) {
                                Db::name('user')->where('user_id=' . $parentIds[2])->update([
                                    'share_income' => ['exp', 'share_income+' .$shareIncome3],
                                    'to_share_income' => ['exp', 'to_share_income+' . $shareIncome3],
                                    'ky_money'=>['exp', 'ky_money+'.$shareIncome3],
                                ]);
                                Db::name('money_log')
                                    ->insert(['user_id'=> $parentIds[2], 'order_id'=>$v['id'], 'money'=>$shareIncome3, 'sign'=>'+', 'remark'=>'好友收益', 'type'=>6]);
                            }
                            if(count($parentIds) > 3) {
                                unset($parentIds[0], $parentIds[1], $parentIds[2]);
                                $shareIncome4 = bcmul($v['income'],bcdiv($sys[3]['content'],100,4),4);
                                if(bccomp($shareIncome4, 0, 4)>0) {
                                    Db::name('user')->where('user_id in(' . implode(',',$parentIds).')')->update([
                                        'share_income' => ['exp', 'share_income+' .$shareIncome4],
                                        'to_share_income' => ['exp', 'to_share_income+' . $shareIncome4],
                                        'ky_money'=>['exp', 'ky_money+'.$shareIncome4],
                                    ]);
                                    $insertAll = [];
                                    foreach($parentIds as $id){
                                      $insertAll[] = ['user_id'=> $id,'order_id'=>$v['id'], 'money'=>$shareIncome4, 'sign'=>'+', 'remark'=>'好友收益', 'type'=>6];
                                    }
                                    Db::name('money_log')
                                        ->insertAll($insertAll);
                                }
                            }
                        }
                    }
                }
            }*/

            Db::name('user')->where('user_id='.$v['user_id'])
                ->update([
                    'ky_money'=>['exp', 'ky_money+'.$v['income']],
                    'total_income'=>['exp', 'total_income+'.$v['income']],
                    'today_income'=>['exp', 'today_income+'.$v['income']],
                ]);
            Db::name('money_log')
                ->insert(['user_id'=> $v['user_id'], 'order_id'=>$v['id'], 'money'=>$v['income'], 'sign'=>'+', 'remark'=>'理财收益', 'type'=>4]);

        }

        //退出用户的锁仓计划
        $date = date('Y-m-d');
        $orders = Db::name('lock_order')->field('id,user_id,money')
            ->where('end_date<=\''.$date.'\' AND status=0')
            ->select();
        foreach($orders as $v) {
            Db::name('user')->where('user_id='.$v['user_id'])
                ->update([
                    'ky_money'=>['exp', 'ky_money+'.$v['money']],
                    'gd_money'=>['exp', 'gd_money-'.$v['money']]
                ]);
            Db::name('money_log')->insert(['user_id'=>$v['user_id'], 'order_id'=>$v['id'], 'money'=>$v['money'], 'sign'=>'+', 'remark'=>'理财到期', 'type'=>5]);
            Db::name('lock_order')->where('id='.$v['id'])->update(['status'=>1]);
        }


        //计算今日矿机收益
        $minerOrder = Db::name('miner')->alias('m')
            ->field('m.id,u.user_id,m.number,e_time,u.grade')
            ->where('m.status = 0')
            ->join('p_user u','u.user_id=m.user_id')
            ->select();
        $config = Db::name('config')->field('content')->where('id in(30,31,32,33,34)')->order('id asc')->select();
        foreach($minerOrder as $k=>$miner){

            //更新资金
            $profit = bcmul($miner['number'],$config[$miner['grade']-1]['content'],4);
            Db::name('user')->where('user_id='.$miner['user_id'])->update([
                'ky_money'=> ['exp', 'ky_money+'.$profit],
                'total_income'=>['exp', 'total_income+'.$profit],
                'today_income'=>['exp', 'today_income+'.$profit],
            ]);
            //添加记录
            Db::name('money_log')
                ->insert(['user_id'=>$miner['user_id'], 'money'=>$profit, 'sign'=>'+', 'remark'=>'矿机收益', 'type'=>13]);

            //清理到期
            $today = time();
            $end = strtotime(date('Y-m-d',strtotime($miner['e_time'])));
            if($today > $end){
                Db::name('miner')->where('id='.$miner['id'])->update([
                    'status' => 1,
                ]);
            }
        }



    }

}