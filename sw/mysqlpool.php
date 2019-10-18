<?php

class MysqlPool
{
    protected $available = true;
    protected $pool;

    public function __construct()
    {
        $this->pool = new SplQueue;
    }

    public function put($redis)
    {
        $this->pool->push($redis);
    }

    /**
     * @return bool|mixed|\Swoole\Coroutine\Redis
     */
    public function get()
    {
        //有空闲连接且连接池处于可用状态
        if ($this->available && $this->pool->count() > 0) {
            return $this->pool->pop();
        }

        //无空闲连接，创建新连接
        $redis = new Swoole\Coroutine\Redis();
        $res = $redis->connect('127.0.0.1', 6379);

        if ($res == false) {
            return false;
        } else {
            $redis->auth('csgx@)!(csgx@)!(');
            $redis->select(2);
            return $redis;
        }
    }

    public function destruct()
    {
        // 连接池销毁, 置不可用状态, 防止新的客户端进入常驻连接池, 导致服务器无法平滑退出
        $this->available = false;
        while (!$this->pool->isEmpty()) {
            $this->pool->pop();
        }
    }
}