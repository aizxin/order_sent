<?php
/**
 * FileName: Token.php
 * ==============================================
 * Copy right 2016-2018
 * ----------------------------------------------
 * This is not a free software, without any authorization is not allowed to use and spread.
 * ==============================================
 * @date  : 2019/11/1 10:41
 */
declare (strict_types = 1);

namespace app\common\util;


use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\ValidationData;

/**
 * Class Token
 * @package app\common\util
 */
class Token
{
    /**
     * 生成 token
     *
     * @param array $data
     *
     * @return string
     */
    public static function getToken(array $data = []): string
    {
        /*
         * iss 【issuer】发布者的url地址
         * sub 【subject】该JWT所面向的用户，用于处理特定应用，不是常用的字段
         * aud 【audience】接受者的url地址
         * exp 【expiration】 该jwt销毁的时间；unix时间戳
         * nbf 【not before】 该jwt的使用时间不能早于该时间；unix时间戳
         * iat 【issued at】 该jwt的发布时间；unix 时间戳
         * jti 【JWT ID】 该jwt的唯一ID编号
         * */
        $config = config('jwt');
        $singer = new Sha256();
        $builder = new Builder();
        $key = new Key($config['key']);
        $now = new \DateTimeImmutable();
        $token = $builder
            ->issuedBy($config['iss'])                  // iss claim
            ->relatedTo($config['sub'])                 // sub claim
            ->permittedFor($config['aud'])              // aud claim
            ->identifiedBy($config['jti'])              // jti claim
            ->issuedAt($now)
            // Configures the time that the token can be used (nbf claim)
            ->canOnlyBeUsedAfter($now)
            // Configures the expiration time of the token (exp claim)
            ->expiresAt($now->modify($config['expires']))
            ->withClaim('data', $data)                  // 追加附加信息
            ->getToken($singer, $key);

        return (string) $token;
    }

    /**
     * 解析 token,并且返回 token 里面的自定义数据
     *
     * @param string $token
     *
     * @return array
     */
    public static function parsingToken(string $token)
    {
        if (self::validateToken($token)) {
            $token = (new Parser())->parse($token);

            return (array) $token->getClaim('data');
        }

        return [];
    }

    /**
     * 解析远程用户中心的 token TODO
     *
     * @param string $token
     *
     */
    public static function parsingTokenFromCurl(string $token)
    {
        # TODO
    }

    /**
     * 验证 token 是否有效
     *
     * @param string $token
     *
     * @return bool
     */
    public static function validateToken(string $token): bool
    {
        $config = config('jwt');
        $time = time();
        $singer = new Sha256();
        $key = new Key($config['key']);
        $token = (new Parser())->parse($token);
        $data = new ValidationData();
        $data->setIssuer($config['iss']);
        $data->setAudience($config['aud']);
        $data->setId($config['jti']);
        $data->setCurrentTime($time);

        return $token->validate($data) && $token->verify($singer, $key);
    }
}