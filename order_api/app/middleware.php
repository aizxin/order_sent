<?php
// 全局中间件定义文件

use app\common\middleware\AllowCrossDomain;

return [
    // 全局请求缓存
    AllowCrossDomain::class,
];
