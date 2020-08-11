<?php

require_once __DIR__ . '/../autoload.php';

use Yarsign\Conf;
use Yarsign\Client;

// 此处可写在项目初始化文件
Conf::load([
    'user' => [
        'service_address' => 'http://userservice.com/rpc/index',
        'secret' => '1532YADJas',
    ],
    'mall' => [
        'service_address' => 'http://mallservice.com/rpc/index',
        'secret' => '1532YADJas',
    ],
]);

// 以下为串行调用service示例，user为服务名称，UserModel为服务里需要实例化的类，如果有对应namespace需要带上，如：Client::getInstance('user', "\\business\\User");
$userModel = Client::getInstance('user', 'UserModel');
$userModel->getUserByUserid(156562);

// 以下为并行调用service示例
$userDetail = [];
Client::concurrentCall('user', 'UserModel', 'getUserByUserid', [156562], function($retval, $callinfo) use (&$userDetail){
    if ($callinfo == NULL) {
        // TODO::此处可以写一些此处远程调用的前置逻辑，yar会在请求发出后立即调用此回调（收到响应前），且$callinfo参数为null
        return true;
    }
    // 此处为远程调用收到响应时的回调处理逻辑
    $userDetail['username'] = $retval['username'];
    $userDetail['gender'] = $retval['gender'];
});
    
Client::concurrentCall('user', 'UserModel', 'getUserAccount', [156562], function($retval, $callinfo) use (&$userDetail){
    if ($callinfo == NULL) {
        // TODO::此处可以写一些此处远程调用的前置逻辑，yar会在请求发出后立即调用此回调（收到响应前），且$callinfo参数为null
        return true;
    }
    // 此处为远程调用收到响应时的回调处理逻辑
    $userDetail['balance_lyb'] = $retval['balance_lyb'];
    $userDetail['balance_mb'] = $retval['balance_mb'];
});
    
Yar_Concurrent_Client::loop();