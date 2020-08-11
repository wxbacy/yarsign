> The auth library for yar, make your service more safe which used yar

## Requirement

PHP 5.4+ and YAR extension installed

## Get Started

### Install via composer

Add Yarauth to composer.json configuration file.
```
$ composer require wxbacy/yarauth
```

```php
// If you installed via composer, just use this code to requrie autoloader on the top of your projects.
require 'vendor/autoload.php';

// Using Yarauth namespace
use Yarauth\Conf;
use Yarauth\Client;

// Initialize
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

// Server

// 以下写在控制器
$serviceStr = $_GET['service'];
$ts = $_GET['ts'];
$sign = $_GET['sign'];

// 解密
$service = Auth::serviceDecode($serviceStr);

// 身份认证
if (! Auth::checkSign($service['service'], $service['class'], $ts, $sign)) {
    return;
}

$server = new Yar_Server(new $service['class']());
$server->handle();

// Client
$userModel = Client::getInstance('user', 'UserModel');
$userModel->getUserByUserid(156562);

$userDetail = [];
Client::concurrentCall('user', 'UserModel', 'getUserByUserid', [156562], function($retval, $callinfo) use (&$userDetail){
    if ($callinfo == NULL) {
        return true;
    }
    $userDetail['username'] = $retval['username'];
    $userDetail['gender'] = $retval['gender'];
});
    
Client::concurrentCall('user', 'UserModel', 'getUserAccount', [156562], function($retval, $callinfo) use (&$userDetail){
    if ($callinfo == NULL) {
        return true;
    }
    $userDetail['balance_lyb'] = $retval['balance_lyb'];
    $userDetail['balance_mb'] = $retval['balance_mb'];
});
    
Yar_Concurrent_Client::loop();
```
