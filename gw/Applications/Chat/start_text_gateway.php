<?php
use \Workerman\Worker;
use \GatewayWorker\Gateway;
use \Workerman\Autoloader;
//require_once __DIR__ . '/../../Workerman/Autoloader.php';
require_once __DIR__ . '/../../vendor/workerman/workerman/Autoloader.php';
Autoloader::setRootPath(__DIR__);

// #### 内部推送端口(假设当前服务器内网ip为192.168.100.100) ####
// #### 端口不能与原来start_gateway.php中一样 ####
$internal_gateway = new Gateway("Text://172.17.161.47:7273");   // 172.17.161.47  阿里云 ecs 私有IP地址
$internal_gateway->name='internalGateway';
// #### 不要与原来start_gateway.php的一样####
// #### 比原来跨度大一些，比如在原有startPort基础上+1000 ####
$internal_gateway->startPort = 4900;
// #### 这里设置成与原start_gateway.php 一样 ####
$internal_gateway->registerAddress = '127.0.0.1:1239';
// #### 内部推送端口设置完毕 ####

if(!defined('GLOBAL_START'))
{
    Worker::runAll();
}