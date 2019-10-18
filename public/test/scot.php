<?php
set_time_limit(0);

$sfd = socket_create(AF_INET, SOCK_STREAM, 0);
socket_bind($sfd, '127.0.0.1', 1234);
socket_listen($sfd, 511);
socket_set_option($sfd, SOL_SOCKET, SO_REUSEADDR, 1);
socket_set_nonblock($sfd);
$rfds = array($sfd);
$wfds = array();

do {
    $rs = $rfds;
    $ws = $wfds;
    $es = array();
    $ret = socket_select($rs, $ws, $es, 3);

    //读取事件 
    foreach ($rs as $fd) {

        if ($fd == $sfd) {
            $cfd = socket_accept($sfd);
            socket_set_nonblock($cfd);
            $rfds[] = $cfd;
            echo "new client coming, fd=$cfd\n";
        } else {

            $msg = socket_read($fd, 1024);

            if ($msg <= 0) {
                //close  
            } else {
                echo "on message, fd=$fd data=$msg\n";
            }
        }
    }



    //写入事件 

    foreach ($ws as $fd) {
        socket_write($fd, '........');
    }
} while (true);
