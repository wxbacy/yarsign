<?php

namespace Yarauth;

use Yar_Client;
use Exception;
use Yar_Concurrent_Client;

// yar客户端
class Client
{
    /**
     * 获取远程调用对象实例
     *
     * @param string $service
     * @param string $class
     * @return \Yar_Client
     */
    public static function getInstance($service, $class)
    {
        return new Yar_Client(self::getUrlByServiceAndClass($service, $class));
    }
    
    /**
     * 并发调用
     *
     * @param string $service
     * @param string $class
     * @param string $method
     * @param array $params
     * @param callable $callback
     * @return void
     */
    public static function concurrentCall($service, $class, $method, $params, $callback)
    {
        Yar_Concurrent_Client::call(self::getUrlByServiceAndClass($service, $class), $method, $params, $callback);
    }
    
    /**
     * 获取rpc完整地址
     *
     * @param string $service
     * @param string $class
     * @return string
     */
    private static function getUrlByServiceAndClass($service, $class)
    {
        $conf = Conf::get();
        
        if (! isset( $conf[$service]['service_address'])) {
            throw new Exception('找不到' . $service . '服务的service_address配置');
        }

        
        return $conf[$service]['service_address']. '?service=' . Auth::serviceEncode($service, $class) . '&ts=' . time() . '&sign=' . Auth::sign($service, $class, time());
    }
}