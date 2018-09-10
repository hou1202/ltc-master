<?php

namespace app\common\command;


use think\Config;
use think\console\Command;
use think\console\Input;
use think\console\Output;
use think\Db;

class AddLockPlan extends Command
{

    protected function configure()
    {
        Config::load(APP_PATH . '../config/config.php');
        $loadConfig = Config::get('app_status');
        Config::load(APP_PATH . '../config/'.$loadConfig.'.php');
        $this->setName('add_lock_plan')->setDescription('Here is the remark');
    }

    protected function execute(Input $input, Output $output)
    {
        $datas = Db::name('lock_plan')->where('is_del=0')->order('plan_id desc')->limit(4)->select();
        $insertData = [];
        $date = date('Y-m-d');
        foreach($datas as $v){
            $insertData[] = ['days'=>$v['days'], 'rate'=>$v['rate'], 'count'=>$v['count'], 'sy_count'=>$v['count'],
                'start_time'=>$date.substr($v['start_time'], 10), 'end_time'=>$date.substr($v['end_time'], 10)];
        }
        Db::name('lock_plan')->insertAll($insertData);
    }

}