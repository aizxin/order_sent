<?php
/**
 * FileName: Timer.php
 * ==============================================
 * Copy right 2016-2018
 * ----------------------------------------------
 * This is not a free software, without any authorization is not allowed to use and spread.
 * ==============================================
 * @author: kong | <iwhero@yeah.com>
 * @date  : 2020/5/1 2:21 下午
 */
declare (strict_types = 1);

namespace websocket;


use Swoole\Timer as SwooleTimer;

class Timer
{
    /**
     * 每隔固定时间执行一次
     *
     * @param int   $time     间隔时间
     * @param mixed $callback 可以是回调 可以是定时器任务模板
     *
     * @return bool
     */
    public function tick($time, $callback)
    {
        if ($callback instanceof \Closure) {
            return SwooleTimer::tick($time, $callback);
        } elseif (is_object($callback) && method_exists($callback, 'run')) {
            return SwooleTimer::tick($time, function ($timerId) use ($callback) {
                $callback->run($timerId);
            });
        }

        return false;
    }

    /**
     * 延迟执行
     *
     * @param int   $time     间隔时间
     * @param mixed $callback 可以是回调 可以是定时器任务模板
     *
     * @return bool
     */
    public function after($time, $callback)
    {
        if ($callback instanceof \Closure) {
            return SwooleTimer::after($time, $callback);
        } elseif (is_object($callback) && method_exists($callback, 'run')) {
            return SwooleTimer::after($time, function ($timerId) use ($callback) {
                $callback->run($timerId);
            });
        }

        return false;
    }

    /**
     * 清除定时器
     *
     * @param int $timerId
     *
     * @return bool
     */
    public function clear($timerId)
    {
        return SwooleTimer::clear($timerId);
    }
}