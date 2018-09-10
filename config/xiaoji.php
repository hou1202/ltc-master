<?php
//线下配置文件
return [
    // 服务器地址
    'database'=> [
        // 数据库类型
        'type'            => 'mysql',
        // 服务器地址
        'hostname'        => '139.224.164.117',
        // 数据库名
        'database'        => 'ltc',
        // 用户名
        'username'        => 'root',
        // 密码
        'password'        => 'kejiXiaoji2016',
        // 端口
        'hostport'        => '',
        // 连接dsn
        'dsn'             => '',
        // 数据库连接参数
        'params'          => [],
        // 数据库编码默认采用utf8
        'charset'         => 'utf8mb4',
        // 数据库表前缀
        'prefix'          => 'p_',
        // 数据库调试模式
        'debug'           => true,
        // 数据库部署方式:0 集中式(单一服务器),1 分布式(主从服务器)
        'deploy'          => 0,
        // 数据库读写是否分离 主从式有效
        'rw_separate'     => false,
        // 读写分离后 主服务器数量
        'master_num'      => 1,
        // 指定从服务器序号
        'slave_no'        => '',
        // 是否严格检查字段是否存在
        'fields_strict'   => true,
        // 数据集返回类型
        'resultset_type'  => 'array',
        // 自动写入时间戳字段
        'auto_timestamp'  => false,
        // 时间字段取出后的默认时间格式
        'datetime_format' => 'Y-m-d H:i:s',
        // 是否需要进行SQL性能分析
        'sql_explain'     => false,
    ],

    //支付宝配置参数
    'alipay_config'=>[
        'partner' =>'2088521367543235',   //这里是你在成功申请支付宝接口后获取到的PID；
        'seller_id' => 'xiaojikeji@aliyun.com', //网页版没这个参数
        // 'key'=>'9t***********ie',//这里是你在成功申请支付宝接口后获取到的Key
        'sign_type'=>'RSA',
        'input_charset'=> 'utf-8',
        'transport'=> 'http',
    ],

    //以上配置项，是从接口包中alipay.config.php 文件中复制过来，进行配置；
    'alipay_url'   =>[
        //这里是异步通知页面url，提交到项目的Pay控制器的notifyurl方法；
        'notify_url'=>'http://cardada3.min-ji.com/api/callback/alicomplete',
        //这里是页面跳转通知url，提交到项目的Pay控制器的returnurl方法；
        'return_url'=>'http://www.xxx.com/Pay/returnurl',
        //支付成功跳转到的页面，我这里跳转到项目的User控制器，myorder方法，并传参payed（已支付列表）
        'successpage'=>'User/myorder?ordtype=payed',
        //支付失败跳转到的页面，我这里跳转到项目的User控制器，myorder方法，并传参unpay（未支付列表）
        'errorpage'=>'User/myorder?ordtype=unpay',
    ],

    'alipay_key' =>[
        'cacert'=> getcwd().'/../pay/alipay/key/cacert.pem',
        'rsa_key'=> getcwd().'/../pay/alipay/key/rsa_private_key.pem',
        'public_key'=> getcwd().'/../pay/alipay/key/alipay_public_key.pem',
    ],

    //小吉小程序微信config
    'wx_config' => [
        'key' => '0bf728ce0a35404f660b666aec55ee83',
        'app_secret' => 'd9b9bc5f076f469b0ab0afbed7e16abe',
        'appid' => 'wxf24e3eebcb76d445',
        'mch_id' => '1457831502',
        'notify_url' => 'https://sharechain.min-ji.com/index/callback/wx',
    ],

    'upload_file_domain' => 'http://www.ltc.com',

    // +----------------------------------------------------------------------
    // | jpush 设置
    // +----------------------------------------------------------------------
    'jpush_app_key' => '1c9cf79f46050ce0be831ff0',
    'jpush_master_secret' => '7bbe0237c0ad591c51fd1a2d',
    'jpush_log' => RUNTIME_PATH . 'log' . DS . 'jpush' . DS .'jpush.log',

    //环信
    'client_id' => 'YXA6YFGDYPgBEee0SB3SB0B6mA',
    'client_secret' => 'YXA6rTWobJJM_EnrD1IIcz7TNr-phHw',
    'org_name' => '1105180112115771',
    'app_name' => 'ruishitong-test',

];