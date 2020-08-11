<?php

namespace Yarauth;

use Exception;

/**
 * rpc调用身份认证
 */
class Auth
{
    // 有效时间 ：秒
    const ACTIVE_TIME = 180;
    
    // 签名验证
    public static function checkSign($service, $class, $ts, $sign)
    {
        // 有效时间
        if (abs(time() - $ts) > self::ACTIVE_TIME) {
            return false;
        }
        
        return self::sign($service, $class, $ts) === $sign;
    }
    
    // 服务和类解密
    public static function serviceDecode($str)
    {
        return $str ? json_decode(base64_decode($str), true) : [];
    }
    
    // 服务和类加密
    public static function serviceEncode($service, $class)
    {
        return base64_encode(json_encode(['service' => $service, 'class' => $class]));
    }

    // 签名
    public static function sign($service, $class, $ts)
    {
        $params['service'] = $service;
        $params['class'] = $class;
        $params['time'] = $ts;

        ksort($params);
        $str = '';
        foreach ($params as $key => $var) {
            $str .= $key . $var;
        }

        $conf = Conf::get();
        if (! empty($conf[$params['service']]['secret'])) {
            throw new Exception('找不到' . $service . '服务的secret配置');
        }

        $str = $str . $conf[$params['service']]['secret'];

        return strtoupper(md5($str));
    }
}