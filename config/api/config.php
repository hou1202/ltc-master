<?php
return [

    'log'  => [
        'type'  => 'File',
        'path'  => LOG_PATH . 'api' . DS,
        'level' => [],
        'apart_level' => ['error','sql'],
    ],

    //token配置
    'api_token_prifix'=>'KingTP_token_prifix_',

    //缓存配置
    'cache_prifix' => 'KingTP_api_cache'




];
