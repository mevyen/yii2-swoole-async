<?php
/**
 * description
 * $Id$
 * $Date$
 * $Author$
 */
namespace mevyen\swooleAsynchronous\src;

class SwooleService{
    /**
     * [$settings description]
     * @var array
     */
    private $settings = [];
    /**
     * [$app description]
     * @var null
     */
    private $app = null;

    function __construct($settings,$app){
        $this->check();
        $this->settings = $settings;
        $this->app = $app;
    }

    /**
     * [check description]
     * @return [type] [description]
     */
    private function check(){
        /**
        * 检测 PDO_MYSQL
        */
        if (!extension_loaded('pdo_mysql')) {
            exit('error:请安装PDO_MYSQL扩展' . PHP_EOL);
        }
        /**
        * 检查exec 函数是否启用
        */
        if (!function_exists('exec')) {
            exit('error:exec函数不可用' . PHP_EOL);
        }
        /**
        * 检查命令 lsof 命令是否存在
        */
        exec("whereis lsof", $out);
        if (strpos($out[0], "/usr/sbin/lsof") === false ) {
            exit('error:找不到lsof命令,请确保lsof在/usr/sbin下' . PHP_EOL);
        }
    }

    /**
     * 获取指定端口的服务占用列表
     * @param  [type] $port 端口号
     * @return [type]       [description]
     */
    private function bindPort($port) {
        $res = [];
        $cmd = "/usr/sbin/lsof -i :{$port}|awk '$1 != \"COMMAND\"  {print $1, $2, $9}'";
        exec($cmd, $out);
        if ($out) {
            foreach ($out as $v) {
                $a = explode(' ', $v);
                list($ip, $p) = explode(':', $a[2]);
                $res[$a[1]] = [
                    'cmd'  => $a[0],
                    'ip'   => $ip,
                    'port' => $p,
                ];
            }
        }
        return $res;
    }
    /**
     * 启动服务
     * @return [type] [description]
     */
    public function serviceStart(){

        $pidfile = $this->settings['pidfile'];
        $host = $this->settings['host'];
        $port = $this->settings['port'];

        $this->msg("服务正在启动...");

        if (!is_writable(dirname($pidfile))) {
            $this->error("pid文件需要写入权限");
        }
        if (file_exists($pidfile)) {
            $pid = explode("\n", file_get_contents($pidfile));
            $cmd = "ps ax | awk '{ print $1 }' | grep -e \"^{$pid[0]}$\"";
            exec($cmd, $out);
            if (!empty($out)) {
                $this->msg("[warning]:pid文件已存在,服务已经启动,进程id为:{$pid[0]}",true);
            } else {
                $this->msg("[warning]:pid文件已存在,可能是服务上次异常退出");
                unlink($pidfile);
            }
        }

        $bind = $this->bindPort($port);

        if ($bind) {
            foreach ($bind as $k => $v) {
                if ($v['ip'] == '*' || $v['ip'] == $host) {
                    $this->error("服务启动失败,{$host}:$port端口已经被进程ID:{$k}占用");
                }
            }
        }

        //启动
        $server = new SHttpServer($this->settings,$this->app);
        $server->run();
        
    }

    /**
     * 查看服务状态
     * @param  [type] $host host
     * @param  [type] $port port
     * @return [type]       [description]
     */
    public function serviceStatus($host, $port){

        $host = $this->settings['host'];
        $port = $this->settings['port'];

        $cmd = "curl -s '{$host}:{$port}?cmd=status'";
        exec($cmd, $out);

        if (empty($out)) {
            $this->error("{$host}:{$port}服务不存在或者已经停止");
        }

        foreach ($out as $v) {
            $a = json_decode($v);
            foreach ($a as $k1 => $v1) {
                $this->msg("$k1:\t$v1");
            }
        }

    }

    /**
     * 查看进程列表
     * @return [type] [description]
     */
    public function serviceList(){

        $cmd = "ps aux|grep " . $this->settings['process_name'] . "|grep -v grep|awk '{print $1, $2, $6, $8, $9, $11}'";
        exec($cmd, $out);

        if (empty($out)) {
            $this->msg("没有发现正在运行服务",true);
        }

        $this->msg("本机运行的服务进程列表:");
        $this->msg("USER PID RSS(kb) STAT START COMMAND");

        foreach ($out as $v) {
            $this->msg($v);
        }

    }

    /**
     * 停止服务
     * @param  [type]  $host      host
     * @param  [type]  $port      port
     * @return [type]             [description]
     */
    public function serviceStop(){

        $pidfile = $this->settings['pidfile'];

        $this->msg("正在停止服务...");

        if (!file_exists($pidfile)) {
            $this->error("pid文件:". $pidfile ."不存在");
        }
        $pid = explode("\n", file_get_contents($pidfile));

        if ($pid[0]) {
            $cmd = "kill {$pid[0]}";
            exec($cmd);
            do {
                $out = [];
                $c = "ps ax | awk '{ print $1 }' | grep -e \"^{$pid[0]}$\"";
                exec($c, $out);
                if (empty($out)) {
                    break;
                }else{
                    exec("kill -9 {$pid[0]}");
                }
            } while (true);
        }

        //确保停止服务后swoole-task-pid文件被删除
        if (file_exists($pidfile)) {
            unlink($pidfile);
        }

        $this->msg("服务已停止");

    }

    /**
     * [error description]
     * @param  [type] $msg [description]
     * @return [type]      [description]
     */
    private function msg($msg,$exit=false){

        if($exit){
            exit($msg . PHP_EOL);
        }else{
            echo $msg . PHP_EOL;
        }
    }    
    /**
     * [error description]
     * @param  [type] $msg [description]
     * @return [type]      [description]
     */
    private function error($msg){
        exit("[error]:".$msg . PHP_EOL);
    }
    
}


