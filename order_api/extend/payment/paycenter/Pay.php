<?php
/**
 *  支付结算中心
 *
 * Author: alan <admin@shenjinpeng.cn>
 * Date: 2020/4/2 10:44
 */


namespace payment\paycenter;

use think\facade\{Config,Log,Cache};
use payment\paycenter\util\Net;

class Pay
{

    public function __construct()
    {

    }


    public  static function createOrder($data){
        //
        $time = $data['buy_time'] ?? time();
        $postData = [
            'token' => PayCenter::getToken(),
            'secret' => Config::get('payment.secret'),
            'timeStamp' => strval($time),
            'paramContent' => [
                'validate' => md5($time),
                'subNotifyUrl' => $data['notify_url'] ?? request()->domain() .'/pay/notice',
                'subJumpUrl' => $data['jump_url'] ?? request()->domain(),
                'subAppId' => $data['appid'],
                'subVendorId' => $data['vendorid'],
                'universalLink' => $data['universalLink'] ?? Config::get('payment.universalLink'),
                'goodsId' => strval($data['goods_id']),
                'goodsName' => $data['goods_name'],
                'outOrderId' => $data['pay_sn'],
                'openId' => $data['openid'] ?? '',
                'mac' => $data['mac'] ?? 'mac',
                'share' => $data['share'] ?? 'N',
                'trxIp' => $data['ip'] ?? '127.0.0.1',
                'trxIpCity' => $data['city'] ?? '昆明',
                'payAmount' => $data['amount'],
                'payMaxMinutes' => $data['timeout'] ?? '15',
                'mobileType' => $data['mobile_type'] ?? '01',
                'orderNote' => $data['note'] ?? "",
                'orderSubmitDate' => date("Y-m-d",$data['time'] ?? $time),
                'orderSubmitTime' => date("H:i:s",$data['time'] ?? $time),
            ],
        ];
        Log::write( " 下单参数");
        Log::write($postData);
        try {
            $re = Net::curlPost(Config::get('payment.orderUrl'), json_encode($postData), 30, ['Content-type: application/json']);
            $re = json_decode($re,1);
            if(json_last_error() === JSON_ERROR_NONE){
                if(!empty($re['orderNo'])){
                    $re['token'] = $postData['token'];
                    $re['timeStamp'] = $postData['timeStamp'];
                    return $re;
                }else{
                    Log::error($postData);
                    Log::error($re);
                    throw new \Exception("下单失败: " . $re['message']);
                }
            }else{
                Log::write($postData);
                Log::write($re);
                throw new \Exception("下单失败: Json 解析失败");
            }
        } catch (\Exception $e) {
            throw $e;
        }

    }

    // 获取小程序支付报文
    public static function getWeChatMiniPayParameter($orderId,$subAppId,$outOrderId){

        $data = [
            "token" => PayCenter::getToken(),
            "secret" => Config::get('payment.secret'),
            "timeStamp" => strval(time()),
            "paramContent" => [
                "subAppId" => $subAppId,
                "subVendorId" => $outOrderId,
                "outOrderId" => $orderId
            ]
        ];
        try {
            $re = Net::curlPost(Config::get('payment.wx_miniPayUrl'), json_encode($data), 30, ['Content-type: application/json']);
            $re = json_decode($re,1);
            if(json_last_error() === JSON_ERROR_NONE){
                return $re;
            }else{
                throw new \Exception(lang('json parse error'));
            }

        } catch (\Exception $e) {
            throw $e;
        }


    }

    public static function validateOrder($order,$validate)
    {

    }


}