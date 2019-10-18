$!/bin/bash

echo "loading"
pid="pidof swoole"
echo $pid
kill -USR1 $pid
echo "reloading"
