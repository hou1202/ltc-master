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

        //清空所有用户今日理财、分享、矿机收益
        Db::name('user')->where('user_id>0')->update(['today_income'=>0, 'to_share_income'=>0,'to_miner_income'=>0]);

        //计算用户锁仓收益、邀请收益
        //获取锁仓订单
        $incomeOrders = Db::name('lock_order')->field('id,user_id,income')
            ->where('status=0')
            ->select();
        /*$incomeOrders = Db::name('lock_order')->alias('l')->field('l.id,l.user_id,l.income,l.money,p.rate')
            ->join('p_lock_plan p','p.plan_id = l.plan_id')
            ->where('l.status=0')
            ->select();*/

        //遍历订单,计算用户理财收益
        foreach($incomeOrders as $v){

            //理财收益
            //$lock_profit =bcmul(bcdiv($v['rate'],100,4),$v['money'],4);
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

            //矿机收益
            $profit = bcmul($miner['number'],$config[$miner['grade']-1]['content'],4);
            //用户记录中的总矿机收益和今日矿机总收益
            Db::name('user')->where('user_id='.$miner['user_id'])->update([
                'ky_money'=> ['exp', 'ky_money+'.$profit],
                'miner_income'=>['exp', 'miner_income+'.$profit],
                'to_miner_income'=>['exp', 'to_miner_income+'.$profit],
            ]);
            //矿机记录中的矿机目前总收益
            Db::name('miner')->where('id='.$miner['id'])->update([
               'now_income'=>['exp', 'now_income+'.$profit],
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