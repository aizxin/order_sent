<?php
// 事件定义文件
use app\common\listener\ActionLog;

return [
    'bind' => [
    ],

    'listen' => [
        'AppInit'   => [],
        'HttpRun'   => [],
        'HttpEnd'   => [],
        'LogLevel'  => [],
        'LogWrite'  => [],
        'ActionLog' => [
            ActionLog::class,
        ],
    ],

    'subscribe' => [
    ],
];
