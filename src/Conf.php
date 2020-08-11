<?php

namespace Yarsign;

use Exception;

// 服务初始化，加载配置
class Conf
{
    /**
     * 服务配置
     * 
     * @var array
     */
    private static $conf;
    
    /**
     * 加载配置
     * 
     * @param array $conf
     */
    public static function load(array $conf)
    {
        self::$conf = $conf;
    }
    
    /**
     * 获取配置
     * 
     * @return array
     */
    public static function get()
    {
        if (! self::$conf) {
            throw new Exception('请先调用\Yarsign\ServiceConf::load($conf)');
        }
        
        return self::$conf;
    }
}