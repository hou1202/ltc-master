<?php
return [

    'view_replace_str'       => array(
        '__CSS__'    => '/static/admin/css',
        '__JS__'     => '/static/admin/js',
        '__IMG__' => '/static/admin/images',
    ),

    // +----------------------------------------------------------------------
    // | 日志设置
    // +----------------------------------------------------------------------

    'log'                    => [
        // 日志记录方式，内置 file socket 支持扩展
        'type'  => 'File',
        // 日志保存目录
        'path'  => RUNTIME_PATH . 'log' . DS . 'admin' . DS,
        // 日志记录级别
        'level' => [],

        'apart_level' => ['error','sql'],
    ],


    // +----------------------------------------------------------------------
    // | Session设置
    // +----------------------------------------------------------------------
/*
    'session'  => [
        'type'   => 'redis',
        'host'       => '127.0.0.1',
        'port'       => 6379,
        'prefix' => 'KingTP_admin_',
        'expire' => 1*3600*24,
        'auto_start' => true,
    ],*/

    'nologin_redirect' => '/admin/login/index',
    'nopermission_redirect' => '/405.html'

];
