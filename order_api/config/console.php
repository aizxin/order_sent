<?php
// +----------------------------------------------------------------------
// | 控制台配置
// +----------------------------------------------------------------------
return [
    // 指令定义
    'commands' => [
        'create:controller' => \app\common\command\create\Controller::class,
        'create:logic'      => \app\common\command\create\Logic::class,
        'create:tran'       => \app\common\command\create\Transformer::class,
        'admin:temp'       => \app\common\command\OneStep::class
    ],
];
