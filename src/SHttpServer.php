<?php
/**
 * Swoole 实现的 http server,用来处理异步多进程任务
 * $Id: SHttpServer.php 9507 2016-09-29 06:48:44Z mevyen $
 * $Date: 2016-09-29 14:48:44 +0800 (Wed, 07 Sep 2016) $
 * $Author: mevyen $
 */

namespace mevyen\swooleAsync\src;

class SHttpServer {
    /**
     * swoole http-server 实例
     * @var null|swoole_http_server
     */
    private $server = null;

    /**
     * swoole 配置
     * @var array
     */
    private $setting = [];

    /**
     * Yii::$app 对象
     * @var array
     */
    private $app = null;

    /**
     * [__construct description]
     * @param string  $host [description]
     * @param integer $port [description]
     * @param string  $env  [description]
     */
    public function __construct($setting,$app){
        $this->setting = $setting;
        $this->app = $app;
    }

    /**
     * 设置swoole进程名称
     * @param string $name swoole进程名称
     */
    private function setProcessName($name){
        if (function_exists('cli_set_process_title')) {
            @cli_set_process_title($name);
        } else {
            if (function_exists('swoole_set_process_name')) {
                @swoole_set_process_name($name);
            } else {
                trigger_error(__METHOD__. " failed.require cli_set_process_title or swoole_set_process_name.");
            }
        }
    }

    /**
     * 运行服务
     * @return [type] [description]
     */
    public function run(){

        $this->server = new \swoole_http_server($this->setting['host'], $this->setting['port']);
        $this->server->set($this->setting);
        //回调函数
        $call = [
            'start',
            'workerStart',
            'managerStart',
            'request',
            'task',
            'finish',
            'workerStop',
            'shutdown',
        ];
        //事件回调函数绑定
        foreach ($call as $v) {
            $m = 'on' . ucfirst($v);
            if (method_exists($this, $m)) {
                $this->server->on($v, [$this, $m]);
            }
        }

        echo "服务成功启动" . PHP_EOL;
        echo "服务运行名称:{$this->setting['process_name']}" . PHP_EOL;
        echo "服务运行端口:{$this->setting['host']}:{$this->setting['port']}" . PHP_EOL;

        return $this->server->start();
    }

    /**
     * [onStart description]
     * @param  [type] $server [description]
     * @return [type]         [description]
     */
    public function onStart($server){
        echo '[' . date('Y-m-d H:i:s') . "]\t swoole_http_server master worker start\n";
        $this->setProcessName($server->setting['process_name'] . '-master');
        //记录进程id,脚本实现自动重启
        $pid = "{$this->server->master_pid}\n{$this->server->manager_pid}";
        file_put_contents($this->setting['pidfile'], $pid);
        return true;
    }

    /**
     * [onManagerStart description]
     * @param  [type] $server [description]
     * @return [type]         [description]
     */
    public function onManagerStart($server){
        echo '[' . date('Y-m-d H:i:s') . "]\t swoole_http_server manager worker start\n";
        $this->setProcessName($server->setting['process_name'] . '-manager');
    }

    /**
     * [onShutdown description]
     * @return [type] [description]
     */
    public function onClose(){
        unlink($this->setting['pidfile']);
        echo '[' . date('Y-m-d H:i:s') . "]\t swoole_http_server shutdown\n";
    }

    /**
     * [onWorkerStart description]
     * @param  [type] $server   [description]
     * @param  [type] $workerId [description]
     * @return [type]           [description]
     */
    public function onWorkerStart($server, $workerId){
        if ($workerId >= $this->setting['worker_num']) {
            $this->setProcessName($server->setting['process_name'] . '-task');
        } else {
            $this->setProcessName($server->setting['process_name'] . '-event');
        }
    }

    /**
     * [onWorkerStop description]
     * @param  [type] $server   [description]
     * @param  [type] $workerId [description]
     * @return [type]           [description]
     */
    public function onWorkerStop($server, $workerId){
        echo '['. date('Y-m-d H:i:s') ."]\t swoole_http_server[{$server->setting['process_name']}  worker:{$workerId} shutdown\n";
    }

    // /**
    //  * 处理请求
    //  * @param $request
    //  * @param $response
    //  *
    //  * @return mixed
    //  */
    // public function onReceive($server, $fd, $from_id, $data){ 
    //     if($data == 'stats'){
    //         return $this->server->send($fd,var_export($this->server->stats(),true),$from_id);
    //     }
    //     $this->server->task($data); 
    //     return true;

    // }
    /**
     * http请求处理
     * @param $request
     * @param $response
     *
     * @return mixed
     */
    public function onRequest($request, $response)
    { 
        //获取swoole服务的当前状态
        if (isset($request->post['cmd']) && $request->post['cmd'] == 'status') {
            return $response->end(json_encode($this->server->stats()));
        }
        $this->server->task($request->post['data']); 
        
        $out = '[' . date('Y-m-d H:i:s') . '] ' . json_encode($request) . PHP_EOL;
        // $out = '[' . date('Y-m-d H:i:s') . '] ' . var_export($request,true) . PHP_EOL;
        $response->end($out);

        return true;
    }
    /**
     * 任务处理
     * @param $server
     * @param $taskId
     * @param $fromId
     * @param $request
     * @return mixed
     */
    public function onTask($serv, $task_id, $from_id, $data){
        $this->logger('[task data] '.$data);
        $data = $this->parseData($data);
        if($data === false){
            return;
        }
        foreach ($data['data'] as $param) {
            if(!isset($param['a']) || empty($param['a'])){
                continue;
            }
            $action = $param["a"];
            $params = [];
            if(isset($param['p'])){
                $params = $param['p'];
                if(!is_array($params)){
                    $params = [strval($params)];
                }
            }
            try{
                print_r($action);
                $parts = $this->app->createController($action);
                if (is_array($parts)) {
                    $res = $this->app->runAction($action,$params);
                    $this->logger('[task result] '.var_export($res,true));
                }
            }catch(Exception $e){
                $this->logger($e);
            }
        }
        return $data;
    }

    /**
     * 解析data对象
     * @param  [type] $data [description]
     * @return [type]       [description]
     */
    private function parseData($data){

        $data = json_decode($data,true);
        $data = $data ?: [];
        if(!isset($data["data"]) || empty($data["data"])){
            return false;
        }
        return $data;

    }

    /**
     * 解析onfinish数据
     * @param  [type] $data [description]
     * @return [type]       [description]
     */
    private function genFinishData($data){
        if(!isset($data['finish']) || !is_array($data['finish'])){
            return false;
        }
        return json_encode(['data'=>$data['finish']]);
    }

    /**
     * 任务结束回调函数
     * @param  [type] $server [description]
     * @param  [type] $taskId [description]
     * @param  [type] $data   [description]
     * @return [type]         [description]
     */
    public function onFinish($server, $taskId, $data){

        $data = $this->genFinishData($data);
        if($data !== false ){
            return $this->server->task($data);
        }

        return true;

    }

    /**
     * 记录日志 日志文件名为当前年月（date("Y-m")）
     * @param  [type] $msg 日志内容 
     * @return [type]      [description]
     */
    public function logger($msg,$logfile='') {
        if (empty($msg)) {
            return;
        }
        if (!is_string($msg)) {
            $msg = var_export($msg, true);
        }
        //日志内容
        $msg = '['. date('Y-m-d H:i:s') .'] '. $msg . PHP_EOL;
        //日志文件大小
        $maxSize = $this->setting['log_size'];
        //日志文件位置
        $file = $logfile ?: $this->setting['log_dir']."/".date('Y-m').".log";
        //切割日志
        if (file_exists($file) && filesize($file) >= $maxSize) {
            $bak = $file.'-'.time();
            if (!rename($file, $bak)) {
                error_log("rename file:{$file} to {$bak} failed", 3, $file);
            }
        }
        error_log($msg, 3, $file);
    }
}

