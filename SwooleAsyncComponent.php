<?php

namespace mevyen\swooleAsynchronous;

use Yii;

class SwooleAsyncComponent extends \yii\base\Component
{
    /**
     * 异步执行入口
     * @param  [type] $data [description]
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