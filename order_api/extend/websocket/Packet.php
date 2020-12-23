<?php
/**
 * FileName: Packet.php
 * ==============================================
 * Copy right 2016-2018
 * ----------------------------------------------
 * This is not a free software, without any authorization is not allowed to use and spread.
 * ==============================================
 * @author: kong | <iwhero@yeah.com>
 * @date  : 2020/4/26 9:33 上午
 */
declare (strict_types = 1);

namespace websocket;


use think\facade\Log;

class Packet
{
    public static $opcode = 1;

    /**
     * @param string $packet
     *
     * @return array|void
     * @author: kong | <iwhero@yeah.com>
     * @date  : 2020/4/24 5:54 下午
     */
    public static function getPayload(string $packet1)
    {
        $packet = preg_replace('/[\x00-\x1F\x7F-\x9F]/u', '', $packet1);
        if ( ! $packet) {
            Log::write('数据解析错误' . static::getOpcode());
            Log::write($packet1);

            return [
                'event' => 'error',
                'data'  => [],
            ];
        }
        // 判断是否为json字符串
        $data = json_decode(trim($packet), true);

        if ( ! is_array($data)) {
            return false;
        }

        return [
            'event' => $data['event'] ?? 'error',
            'data'  => $data['data'] ?? [],
        ];
    }

    /**
     * @return int
     * @author: kong | <iwhero@yeah.com>
     * @date  : 2020/4/29 9:50 上午
     */
    public static function getOpcode()
    {
        return static::$opcode;
    }

    /**
     * @param $opcode
     *
     * @author: kong | <iwhero@yeah.com>
     * @date  : 2020/4/29 9:51 上午
     */
    public static function setOpcode($opcode)
    {
        static::$opcode = $opcode;
    }
}