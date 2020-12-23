<?php
// 事件定义文件
use app\admin\listener\AdminLoginLog;

return [
    'bind' => [
    ],

    'listen' => [
        'AdminLoginLog' => [
            AdminLoginLog::class,
        ],
    ],

    'subscribe' => [
    ],
];
