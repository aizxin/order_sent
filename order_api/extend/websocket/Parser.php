<?php
/**
 * FileName: Parser.php
 * ==============================================
 * Copy right 2016-2018
 * ----------------------------------------------
 * This is not a free software, without any authorization is not allowed to use and spread.
 * ==============================================
 * @author: kong | <iwhero@yeah.com>
 * @date  : 2020/4/24 5:53 下午
 */
declare (strict_types = 1);

namespace websocket;


use think\swoole\contract\websocket\ParserInterface;

class Parser implements ParserInterface
{

    /**
     * 编码
     * Encode output payload for websocket push.
     *
     * @param string $event
     * @param mixed  $data
     *
     * @return mixed
     */
    public function encode(string $event, $data)
    {
        return json_encode(['event' => $event, 'data' => $data]);
    }

    /**
     * 解码
     * Decode message from websocket client.
     * Define and return payload here.
     *
     * @param \Swoole\Websocket\Frame $frame
     *
     * @return array
     */
    public function decode($frame)
    {
        $payload = Packet::getPayload($frame->data);

        return [
            'event' => $payload['event'] ?? 'error',
            'data'  => $payload['data'] ?? null,
        ];
    }




}