<?php
/**
 * 结算中心 下单api
 *
 * Author: alan <admin@shenjinpeng.cn>
 * Date: 2020/4/10 10:53
 */


namespace payment\paycenter;


use app\common\model\PaymentAccount;
use payment\paycenter\util\Net;
use think\facade\Config;
use think\facade\Log;

class PayApi extends PayCenter
{

    // 通信秘钥,验证订单真实性
    protected $paymentConfig = null;
    // 商户支付配置
    protected $vendor = null;

    public function __construct()
    {
        $this -> paymentConfig = Config::get('payment');
        parent::__construct($this -> paymentConfig);
    }

    // 设置商户信息
    public function setVendor($sid)
    {
        try {
            $this->vendor = PaymentAccount::where('sid', $sid)->find();
        } catch (\Exception $e) {
            throw $e;
        }

    }

    // $orderId pay_sn
    // 获取小程序支付报文
    public function getWeChatMiniPayParameter($orderId){

        $data = [
            "token" => parent::getHeaterToken(),
            "secret" => $this->paymentConfig['secret'],
            "timeStamp" => strval(time()),
            "paramContent" => [
                "subAppId" => $this->vendor['appid'],
                "subVendorId" => $this->vendor['vendorid'],
                "outOrderId" => $orderId
            ]
        ];
        try {
            $re = Net::curlPost($this->paymentConfig['wx_miniPayUrl'], json_encode($data), self::CURL_TIMEOUT, ['Content-type: application/json']);
            $re = \json_decode($re,1);
            if(json_last_error() === JSON_ERROR_NONE){
                if($re['code'] == 0){
                    return $re;
                }else{
                    throw new \Exception($re['message']);
                }
            }else{
                throw new \Exception(lang('json parse error'));
            }
        } catch (\Exception $e) {
            throw $e;
        }


    }


    // 下订单

    /**
     * $data 参数说明
     *
     * buy_time 下单时间 必传
     * notify_url 通知地址
     * jump_url 支付成功跳转地址
     * universalLink ios App首页
     * pay_sn 支付订单号
     * goods_name 订单标题
     * goods_id 商品id
     * openid 小程序传
     * mac 硬件mac地址
     * share 分账 Y 是 N 否
     * ip 客户端ip
     * city 城市
     * amount 支付金额
     * timeout 超时时间 单位分钟 默认 15
     * mobile_type 手机类型 01 IOS, 02 安卓
     * note 订单备注信息
     *
     * note 下单日期
     * note 下单时间
     *
     * @param $data
     * @return bool|mixed|string
     * @throws \Exception
     */
    public function PlaceOrder($data)
    {
        //
        $time = $data['time'] ?? time();
        $postData = [
            'token' => parent::getHeaterToken(true),
            'secret' => $this->paymentConfig['secret'],
            'timeStamp' => strval(time()),
            'paramContent' => [
                'validate' => md5($this->paymentConfig['aq_key'] . $data['pay_sn'] ),
                'subNotifyUrl' => $data['notify_url'] ?? $this->domain .'/pay/notice' ,
                'subJumpUrl' => $data['jump_url'] ?? $this->domain,
                'subAppId' => $this->vendor['appid'],
                'subVendorId' => $this->vendor['vendorid'],
                'isSpec' => $this->vendor['spec'],
                'universalLink' => $data['universalLink'] ?? $this->paymentConfig['universalLink'],
                'goodsId' => strval($data['goods_id']),
                'goodsName' => (string)$data['goods_name'],
                'outOrderId' => $data['pay_sn'],
                'openId' => $data['openid'] ?? '',
                'mac' => $data['mac'] ?? 'mac',
                'share' => $data['share'] ?? 'N',
                'trxIp' => $data['ip'] ?? request() -> ip(),
                'trxIpCity' => $data['city'] ?? '昆明',
                'payAmount' => (string)$data['amount'],
                'payMaxMinutes' => $data['timeout'] ?? '15',
                'mobileType' => $data['mobile_type'] ?? '01',
                'orderNote' => $data['note'] ?? "订单",
                'orderSubmitDate' => date("Y-m-d",$time),
                'orderSubmitTime' => date("H:i:s",$time),
            ],
        ];
        Log::write( "结算中心下单参数");
        Log::write($postData);
        try {
            $re = Net::curlPost($this->paymentConfig['orderUrl'], json_encode($postData), self::CURL_TIMEOUT, ['Content-type: application/json']);
            $re = json_decode($re,1);
            if(json_last_error() === JSON_ERROR_NONE){
                if(!empty($re['orderNo'])){
                    $re['pay_sn'] = $data['pay_sn'];
                    $re['pay_url'] = $this->paymentConfig['client_payUrl'];
                    $re['secret'] = $this->paymentConfig['secret'];
                    $re['token'] = $postData['token'];
                    $re['timeStamp'] = $postData['timeStamp'];
                    $re['subAppId'] = $this->vendor['appid'];
                    $re['subVendorId'] = $this->vendor['vendorid'];
                    return $re;
                }else{
                    Log::error($postData);
                    Log::error($re);
                    throw new \Exception("下单失败: " . $re['message']);
                }
            }else{
                Log::error($postData);
                Log::error($re);
                throw new \Exception("下单失败: Json 解析失败");
            }
        } catch (\Exception $e) {
            throw $e;
        }

    }

    // 参数效验
    public function validateOrder($order,$validate)
    {
        $payApi = new self;
        return ($order -> buy_time . $payApi->paymentConfig['aq_key'] . $order -> pay_sn === $validate);

    }

    // 退款

    /**
     * $data 参数说明
     *
     * refund_time 退款时间 必传
     * pay_sn 支付订单号
     * refund_sn 退款订单号
     * amount 订单金额
     * refund_amount 退款金额
     *
     * last_time 退款时间
     * last_time 退款时间单位
     * class 退款类型 01 普通 02 合并订单退款
     * type 01 普通 02 合快捷退款 03 支付疑问退款
     *
     * audit_uid 审核用户uid
     * audit_name 审核人
     * audit_phone 审核人联系方式
     * audit_status 审核状态
     * audit_con 审核内容
     *
     * notes 退款备注
     *
     * @param $data
     * @return bool|mixed|string
     * @throws \Exception
     */
    public function refundOrder(array $data)
    {
        $time = $data['refund_time'];
        $postData = [
            'token' => parent::getHeaterToken(),
            'secret' => $this->paymentConfig['secret'],
            'timeStamp' => strval($time ) . '000',
            'paramContent' => [
                'subValidate' => md5($time . $this->paymentConfig['aq_key'] . $data['refund_sn']),
                "subNotifyUrl"=> $data['notify_url'] ?? request()->domain() .'/pay/notice',
                "appId"=>$this->vendor['appid'],
                "vendorCode"=>$this->vendor['vendorid'],
                "outOrderId"=> $data['pay_sn'],
                "outRefundId"=>$data['refund_sn'],
                "lastTime"=>$data['last_time'] ?? "3",
                "lastTimeUnit"=>$data['last_time_unit'] ?? "01",
                "payAmount"=>(string)$data['amount'],
                "refundAmount"=>(string)$data['refund_amount'],
                "refundClass"=>$data['class'] ?? "01",
                "refundType"=>$data['type'] ?? "01",
                "systemDate"=>date("Y-m-d",$time),
                "systemTime"=>date("H:i:s",$time),
                "auditorId"=>$data['audit_uid'] ?? "001",
                "auditor"=>$data['audit_name'] ?? "系统自动退款",
                "auditorTel"=>$data['audit_phone'] ?? "13888170196",
                "auditStatus"=>$data['audit_status'] ?? "01",
                "auditDate"=>date("Y-m-d H:i:s",$data['audit_time'] ?? time()),
                "auditIdea"=>$data['audit_con'] ?? "同意退款",
                "notes"=>$data['notes'] ?? "退款",
            ]
        ];

        try {
            $re = Net::curlPost($this->paymentConfig['refundUrl'], json_encode($postData), self::CURL_TIMEOUT, ['Content-type: application/json']);
            $re = json_decode($re,1);
            if(json_last_error() === JSON_ERROR_NONE){
                if(!empty($re['orderNo'])){
                    return $re;
                }else{
                    Log::error($postData);
                    Log::error($re);
                    throw new \Exception("退款失败: " . $re['message']);
                }
            }else{
                Log::write($postData);
                Log::write($re);
                throw new \Exception("退款失败: Json 解析错误");
            }
        } catch (\Exception $e) {
            throw $e;
        }


    }

    // 返回成功
    public static function success(){
        return '{"returnCode":"true","returnMsg":"成功"}';
    }
    // 返回失败
    public static function error(){
        return '{"returnCode":"false","returnMsg":"失败"}';
    }


}
