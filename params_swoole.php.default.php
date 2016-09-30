<?php

/**
 * 配置文件示例
 */
return [
    'swooleAsync' => [
        'host'             => '127.0.0.1',      //服务启动IP
        'port'             => '9512',           //服务启动端口
        'process_name'     => 'swooleServ',     //服务进程名
        'open_tcp_nodelay' => '1',              //启用open_tcp_nodelay
        'daemonize'        => '1',              //守护进程化
        'worker_num'       => '2',              //work进程数目
        'task_worker_num'  => '2',              //task进程的数量
        'task_max_request' => '10000',          //work进程最大处理的请求数
        'task_tmpdir'      => '/tmp/task',       //设置task的数据临时目录
        'log_file'         => '/tmp/log/http.log', //指定swoole错误日志文件
        'client_timeout'   => '20',              //client链接服务器时超时时间(s)
        'pidfile'          => '/tmp/y.pid',          //服务启动进程id文件保存位置

        //--以上配置项均来自swoole-server的同名配置，可随意参考swoole-server配置说明自主增删--
        'log_size'         => 204800000,             //运行时日志 单个文件大小
        'log_dir'          => '/tmp/log',            //运行时日志 存放目录
    ]
];