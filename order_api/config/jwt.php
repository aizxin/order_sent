<?php
/**
 * FileName: jwt.php
 * ==============================================
 * Copy right 2016-2018
 * ----------------------------------------------
 * This is not a free software, without any authorization is not allowed to use and spread.
 * ==============================================
 * @author: 永 | <chuanshuo_yongyuan@163.com>
 * @date  : 2019/11/1 10:56
 */
declare (strict_types = 1);

return [
    'key'     => '%*)(*)(*_HIKHKJGUY%&^*)_)*_^jggkjlhlh%*&696970-847687', // token 加盐
    'expires' => '+1 month', // token 过期时间
    /*
    * iss 【issuer】发布者的url地址
    * sub 【subject】该JWT所面向的用户，用于处理特定应用，不是常用的字段
    * aud 【audience】接受者的url地址
    * exp 【expiration】 该jwt销毁的时间；unix时间戳
    * nbf 【not before】 该jwt的使用时间不能早于该时间；unix时间戳
    * iat 【issued at】 该jwt的发布时间；unix 时间戳
    * jti 【JWT ID】 该jwt的唯一ID编号
    * */
    'iss'     => '',
    'sub'     => '',
    'aud'     => '',
    'jti'     => '',
];