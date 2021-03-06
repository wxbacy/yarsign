<?php

require_once __DIR__ . '/../autoload.php';

use Yarsign\Conf;
use Yarsign\Sign;

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

// 以下写在控制器
$serviceStr = $_GET['service'];
$ts = $_GET['ts'];
$sign = $_GET['sign'];

// 解密
$service = Sign::serviceDecode($serviceStr);

// 身份认证
if (! Sign::checkSign($service['service'], $service['class'], $ts, $sign)) {
    return;
}

$server = new Yar_Server(new $service['class']());
$server->handle();