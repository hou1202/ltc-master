<?php
/**
 * Created by PhpStorm.
 * User: Hou-ShiShu
 * Date: 2018/9/14
 * Time: 15:12
 */

namespace app\common\command;

use think\console\Command;
use think\console\Input;
use think\console\Output;
use think\Db;

class TestSign extends Command
{

    protected function configure()
    {

        $this->setName('test')->setDescription('Here is the remark');
    }

    protected function execute(Input $input, Output $output)
    {

        // 输出到日志文件
        $output->writeln("TestCommand:");
            Db::name('user_sign')->insert([
                'user_id'=>12,
                'sign_data'=>'2000-01-01',
            ]);
        // 定时器需要执行的内容
        // .....
        $output->writeln("end....");
    }

}