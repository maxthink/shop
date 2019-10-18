<?php


$serv = new swoole_server("0.0.0.0",12340);

$serv->set(array(
    'daemonize'   => false,  //守护进程模式
    'max_conn'    => 2000, //最大允许维持多少个tcp连接
    'reactor_num' => 2,     //reactor进程数, 默认设置为CPU核数
    'worker_num'  => 4,     //worker进程数, 全异步非阻塞模式设置为cpu核心数的1-4倍
    'backlog'     => 128,   //Listen队列长度, 决定最多同时又多少待accept连接
    'max_request' => 2000,  //防止内存溢出, 每个worker最多执行次数
    'dispatch_mode' => 2,   //分配模式, 1:平均分配, 2:固定分配, 3:抢占式分配
    'log_file'=> '/data/www/csgx2/sw/swoole_err.log',
    
    'open_length_check' => true,
    'package_max_length' => 128,
    'package_length_type' => 'n', //see php pack()
    'package_length_offset' => 0,
    'package_body_offset' => 3,
    
));

require __DIR__.DIRECTORY_SEPARATOR.'logic.php';

$serv->on('WorkerStart', function ($serv, $worker_id){
    
    //进程重命名
    if($worker_id >= $serv->setting['worker_num']) {
        swoole_set_process_name("swoole $worker_id task worker");
    } else {
        swoole_set_process_name("swoole $worker_id event worker");
    }

    ini_set('default_socket_timeout', -1); //redis不超时


    $serv->mysql = new Swoole\Coroutine\MySQL();
    $serv->mysql->connect([
        'host' => '127.0.0.1',
        'port' => 3306,
        'user' => 'www',
        'password' => 'guoqu123!@#',
        'database' => 'liangzi',
        'charset' => 'utf8mb4',
    ]);

    //requie 逻辑代码, reload 用
    //require __DIR__.DIRECTORY_SEPARATOR.'logic.php';
    
    require dirname(__FILE__).DIRECTORY_SEPARATOR.'redispool.php';
    
});

$serv->on('Start', function( $serv ){
    swoole_set_process_name("swoole worker");
});

$serv->on('WorkerStop',function($serv, $worker_id){
 	//$serv->redis->close();
 	//$serv->mysql->close();
});

$serv->on('Connect','Logic::onConnect');
$serv->on('Receive', 'Logic::onReceive');
$serv->on('Close', 'Logic::onClose');

$serv->start();
