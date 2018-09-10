<?php
return [
    'log'   => [
        'type'  => 'File',
        'path'  => LOG_PATH . 'index' . DS,
        'level' => [],
        'apart_level' => ['error','sql'],
    ],

   /* 'session'  => [
        'type'   => 'redis',
        'host'       => '127.0.0.1',
        'port'       => 6379,
        'prefix' => 'KingTP_index_',
        'expire' => 1*3600*24,
        'auto_start' => true,
    ],*/

    'nologin_redirect' => '/index/login/index',
    'nopermission_redirect' => '/405.html'

];
