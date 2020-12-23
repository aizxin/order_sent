<?php
/**
 * 网络类
 *
 * Author: alan <admin@shenjinpeng.cn>
 * Date: 2020/4/2 11:08
 */


namespace tools\util;


class Net
{
    // 通过curl get数据
     public static function curlGet($url, $timeout = 10, $header =  []) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header); //模拟的header头
        $result = curl_exec($ch);
        if($result === false)
        {
            throw new \Exception('Curl error: ' . curl_error($ch)) ;
        }
        curl_close($ch);
        return $result;
    }

    // 通过curl post数据
     public static function curlPost($url, $post_data , $timeout = 10, $header = []) {

        if(is_array($post_data)){
            $post_string = http_build_query($post_data);
        }else{
            $post_string = $post_data;
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header); //模拟的header头
        $result = curl_exec($ch);
        if($result === false)
        {
            throw new \Exception('Curl error: ' . curl_error($ch)) ;
        }
        curl_close($ch);
        return $result;
    }

    public static function decodeJson($json){

        $re = json_decode($json,1);
        if(json_last_error() === JSON_ERROR_NONE){
            return $re;
        }else{
            throw new \Exception(json_last_error_msg(),400);
        }
    }

}