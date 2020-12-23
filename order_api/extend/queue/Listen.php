<?php
/**
 * FileName: Listen.php
 * ==============================================
 * Copy right 2016-2018
 * ----------------------------------------------
 * This is not a free software, without any authorization is not allowed to use and spread.
 * ==============================================
 * @author: kong | <iwhero@yeah.com>
 * @date  : 2020/4/23 5:53 下午
 */
declare (strict_types = 1);

namespace queue;


use Swoole\Process;
use Swoole\Server;

class Listen
{
    protected $app;
    protected $worker;
    public function __construct(Worker $worker)
    {
        $this->app = app();
        $this->worker = $worker;
    }

    /**
     * @author: kong | <iwhero@yeah.com>
     * @date  : 2020/4/23 6:14 下午
     */
    public function handle()
    {
        $swoole = $this->app->make(Server::class);
        return $this->addQueueProcess($swoole);

//        $swoole->addProcess(new Process(function ()  {
//            sleep(1);
//            var_dump(121313);
//        }, false, 0));
    }

    /**
     * @param $swoole
     *
     * @author: kong | <iwhero@yeah.com>
     * @date  : 2020/4/23 6:14 下午
     */
    private function addQueueProcess($swoole)
    {
        $auto_queue = $this->app->config->get('swoole.auto_queue', false);

        if ( ! $auto_queue) {

            $this->addDefaultQueueProcess($swoole);

            return;
        };

        $items = $this->app->config->get('swoole.queue');

        if ( ! $items) {

            $this->addDefaultQueueProcess($swoole);

            return;
        };

        foreach ($items as $key => $item) {

            $driver = $item['driver'];
            for ($index = 1; $index <= $item['nums']; $index++) {

                $swoole->addProcess(new Process(function () use ($driver, $key) {
                    sleep(2);
                    $this->worker->daemon($driver, $key);
                }, false, 0));

            }
        }

        return;

    }

    /**
     * @param $swoole
     *
     * @author: kong | <iwhero@yeah.com>
     * @date  : 2020/4/23 6:14 下午
     */
    private function addDefaultQueueProcess($swoole)
    {
        $connection = $this->app->config->get('queue.default');

        $queue = $this->app->config->get("queue.connections.{$connection}.queue", 'default');

        $swoole->addProcess(new Process(function () use ($connection, $queue) {
            $this->worker->daemon($connection, $queue);
        }, false, 0));
    }
}