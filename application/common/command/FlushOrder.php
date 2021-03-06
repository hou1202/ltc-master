<?php

namespace app\common\command;


use app\common\model\CommonUtils;
use think\Config;
use think\console\Command;
use think\console\Input;
use think\console\Output;
use think\Db;

class FlushOrder extends Command
{

    protected function configure()
    {
        Config::load(APP_PATH . '../config/config.php');
        $loadConfig = Config::get('app_status');
        Config::load(APP_PATH . '../config/'.$loadConfig.'.php');
        $this->setName('flush_order')->setDescription('Here is the remark');
    }

    protected function execute(Input $input, Output $output)
    {
        /*测试
         * Db::name('user_sign')->insert([
           'user_id'=>time(),
            'sign_date'=>date('Y-m-d'),
        ]);*/
        CommonUtils::flushOrders();
    }

}