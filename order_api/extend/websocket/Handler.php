<?php
/**
 * FileName: Handler.php
 * ==============================================
 * Copy right 2016-2018
 * ----------------------------------------------
 * This is not a free software, without any authorization is not allowed to use and spread.
 * ==============================================
 * @author: kong | <iwhero@yeah.com>
 * @date  : 2020/4/24 5:52 下午
 */
declare (strict_types = 1);

namespace websocket;


use Swoole\Server;
use Swoole\Websocket\Frame;
use Swoole\WebSocket\Server as WebsocketServer;
use think\Config;
use think\Event;
use think\helper\Str;
use think\Request;
use think\swoole\contract\websocket\HandlerInterface;
use think\swoole\websocket\socketio\Packet as TPacket;

class Handler implements HandlerInterface
{
    /** @var WebsocketServer */
    protected $server;

    /** @var Config */
    protected $config;

    /**
     * @var Event
     */
    protected $event;

    public function __construct(Server $server, Config $config, Event $event)
    {
        $this->server = $server;
        $this->config = $config;
        $this->event = $event;
    }

    /**
     * "onOpen" listener.
     *
     * @param int     $fd
     * @param Request $request
     */
    public function onOpen($fd, Request $request)
    {
        $this->event->trigger("swoole.websocket.Connect", func_get_args());

        return true;

        if ( ! $request->param('sid')) {
            $payload = json_encode(
                [
                    'sid'          => base64_encode(uniqid()),
                    'upgrades'     => [],
                    'pingInterval' => $this->config->get('swoole.websocket.ping_interval'),
                    'pingTimeout'  => $this->config->get('swoole.websocket.ping_timeout'),
                ]
            );
            $initPayload = TPacket::OPEN . $payload;
            $connectPayload = TPacket::MESSAGE . TPacket::CONNECT;

            $this->server->push($fd, $initPayload);
            $this->server->push($fd, $connectPayload);
        }


    }

    /**
     * "onMessage" listener.
     *  only triggered when event handler not found
     *
     * @param Frame $frame
     *
     * @return bool
     */
    public function onMessage(Frame $frame)
    {
        $packet = $frame->data;
        Packet::setOpcode($frame->opcode);

        $payload = Packet::getPayload($packet);
        if ( ! $payload) {
            // 心跳
            $this->checkHeartbeat($frame->fd, $payload);

            return true;
        }
        $event = $payload['event'];
        // 首字母 转大写
        $method = Str::studly($event);
        if ( ! in_array($event, ['Close', 'Connect'])) {
            $this->event->trigger("swoole.websocket." . $method, [$frame->fd, $event, $payload['data']]);
        }

        return true;

    }

    /**
     * "onClose" listener.
     *
     * @param int $fd
     * @param int $reactorId
     */
    public function onClose($fd, $reactorId)
    {
        if ($this->server->getClientInfo($fd)['websocket_status']) {
            $this->event->trigger("swoole.websocket.Close", func_get_args());
        }

        return true;
    }


    /**
     * @param $fd
     * @param $packet
     *
     * @author: kong | <iwhero@yeah.com>
     * @date  : 2020/5/18 4:42 下午
     */
    protected function checkHeartbeat($fd, $payload)
    {
        $event = $payload['event'] ?? 'ping';

        $returnData = [
            'code'    => 200,
            'message' => $event,
            'event'   => $event,
        ];

        $returnData = json_encode($returnData);

        if (in_array($event, ['ping', 'pong'])) {
            $this->server->push($fd, $returnData);
        }

        unset($returnData);

    }

}
