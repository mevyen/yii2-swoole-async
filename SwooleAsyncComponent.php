<?php
/**
 * 异步组件
 * $Id: SwooleAsyncComponent.php 9507 2016-09-29 06:48:44Z mevyen $
 * $Date: 2016-09-29 14:48:44 +0800 (Wed, 07 Sep 2016) $
 * $Author: mevyen $
 */
namespace mevyen\swooleAsync;

use Yii;

class SwooleAsyncComponent extends \yii\base\Component
{
    /**
     * 异步执行入口
     * $data.data 定义需要执行的任务列表，其中如果指定多个任务(以数组形式),则server将顺序执行
     * $data.finish 定义了data中的任务执行完成后的回调任务，执行方式同$data.data
     * @param  [json] $data 结构如下
     * [
     *     'data' => [
     *         [
     *             'a' => 'test1/mail1' #要执行的console控制器和action
     *             'p' => ['p1','p2','p3'] // action参数列表
     *         ],
     *         [
     *             'a' => 'test2/mail2' #要执行的console控制器和action
     *             'p' => ['p1','p2','p3'] // action参数列表
     *         ]
     *     ],
     *     'finish' => [
     *         [
     *             'a' => 'test3/mail3' #要执行的console控制器和action
     *             'p' => ['p1','p2','p3'] // action参数列表
     *         ],
     *         [
     *             'a' => 'test4/mail4' #要执行的console控制器和action
     *             'p' => ['p1','p2','p3'] // action参数列表
     *         ]
     *     ]
     * ]
     * @return [type]       [description]
     */
    public function async($data)
    {
        $client = new \swoole_client(SWOOLE_SOCK_TCP,SWOOLE_SOCK_ASYNC);

        $settings = Yii::$app->params['swooleAsync'];
        
        $client->on('Connect', function ($cli) use ($data) {
            $cli->send($data);
            $cli->close();
        });
        
        $client->on('Receive', function( $cli, $data ) {});
        $client->on('Close', function ($cli) {});
        $client->on('Error', function(){});

        if (!$client->connect($settings['host'], $settings['port'], $settings['client_timeout'])){
            exit("Error: connect server failed. code[{$client->errCode}]\n");
        }
    }
    
}