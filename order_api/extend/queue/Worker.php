<?php
/**
 * FileName: Worker.php
 * ==============================================
 * Copy right 2016-2018
 * ----------------------------------------------
 * This is not a free software, without any authorization is not allowed to use and spread.
 * ==============================================
 * @author: kong | <iwhero@yeah.com>
 * @date  : 2020/4/23 5:48 下午
 */
declare (strict_types = 1);

namespace queue;


use think\Cache;
use think\Event;
use think\exception\Handle;
use think\Queue;
use think\queue\event\WorkerStopping;

/**
 * Class Worker
 * @package queue
 */
class Worker extends \think\queue\Worker
{
    public function __construct(Queue $queue, Event $event, Handle $handle, Cache $cache = null)
    {
        parent::__construct($queue, $event, $handle, $cache);
    }

    /**
     * Stop listening and bail out of the script.
     *
     * @param int $status
     *
     * @return void
     */
    public function stop($status = 0)
    {
        $this->event->trigger(new WorkerStopping($status));

        return $status;
    }

    /**
     * Kill the process.
     *
     * @param int $status
     *
     * @return void
     */
    public function kill($status = 0)
    {
        $this->event->trigger(new WorkerStopping($status));

        if (extension_loaded('posix')) {
            posix_kill(getmypid(), SIGKILL);
        }

        return $status;
    }

    public function getWorkNextJob($connection, $queue)
    {
        $job = $this->getNextJob(
            $this->queue->connection($connection), $queue
        );

        return $job;
    }
}