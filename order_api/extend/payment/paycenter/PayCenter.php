<?php
/**
 *
 *
 * Author: alan <admin@shenjinpeng.cn>
 * Date: 2020/4/3 16:56
 */


namespace payment\paycenter;


use payment\paycenter\util\Net;
use think\facade\Cache;
use think\facade\Config;

class PayCenter
{
    protected $loginCode ;
    protected $secret ;
    protected $tokenUrl ;
    const CURL_TIMEOUT = 30 ;

    // 本地url , 因为docker获取不到https协议, 所以不能直接使用 request()->domain
    public $domain = '';

    public function __construct($config)
    {
        $this -> loginCode = $config['loginCode'];
        $this -> secret = $config['secret'];
        $this -> tokenUrl = $config['tokenUrl'];
        $this -> domain = $config['scheme'] . request()->host();
    }


    // 获取通信token ,有效期15分钟, 缓存120s
    public static function getToken($refresh = false){

        if($refresh) Cache::delete('pay_center_token');
        return Cache::remember('pay_center_token',function(){
            $tokenUrl = Config::get('payment.tokenUrl');
            $post = [
                'loginCode' => Config::get('payment.loginCode'),
                "secret"=> Config::get('payment.secret'),
                "token"=>"",
                "timeStamp"=>(string)time(),
                "requestType"=>"99"
            ];
            $re = Net::curlPost($tokenUrl,json_encode(array_filter($post)),self::CURL_TIMEOUT,['Content-type: application/json']);
            $re = json_decode($re,1);
            if(json_last_error() === JSON_ERROR_NONE){
                return $re['token'];
            }else{
                throw new \Exception('json error',0);
            }
        },120);
    }

    // 获取通信token ,有效期15分钟, 缓存120s
    public function getHeaterToken($refresh = false){

        if($refresh) Cache::delete('pay_center_token');
        return Cache::remember('pay_center_token',function() {
            $post = [
                'loginCode' => $this->loginCode,
                "secret"=> $this->secret,
                "token"=>"",
                "timeStamp"=>(string)time(),
                "requestType"=>"99"
            ];
            $re = Net::curlPost($this->tokenUrl,json_encode(array_filter($post)),self::CURL_TIMEOUT,['Content-type: application/json']);
            $re = json_decode($re,1);
            if(json_last_error() === JSON_ERROR_NONE){
                return $re['token'];
            }else{
                return false;
            }
        },120);
    }

}
