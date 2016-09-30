# yii2 Swoole Async

yii2异步任务扩展:基于swoole和yii2-console的异步任务实现，配置简洁，与Yii2无缝集成，只需要安装swoole扩展即可使用

swoole版本要求：>=1.8.1

实现原理
------------

1、客户端发送异步console任务执行请求

2、服务端响应请求，并立即返回

3、服务端发起task执行任务，执行请求携带的所有任务

适用场景
------------
需要客户端触发的耗时请求，客户端无需等待返回结果

安装
------------
```
composer require mevyen/yii2-swoole-async "dev-master"
```

如何使用
-----
1、新增配置文件params_swoole.php
```php
return [
    'swooleAsync' => [
		'host'             => '127.0.0.1', 		//服务启动IP
		'port'             => '9512',      		//服务启动端口
		'process_name'     => 'swooleServ',		//服务进程名
		'open_tcp_nodelay' => '1',         		//启用open_tcp_nodelay
		'daemonize'        => '1',				//守护进程化
		'worker_num'       => '2',				//work进程数目
		'task_worker_num'  => '2',				//task进程的数量
		'task_max_request' => '10000',			//work进程最大处理的请求数
		'task_tmpdir'      => '/tmp/task',		 //设置task的数据临时目录
		'log_file'         => '/tmp/log/http.log', //指定swoole错误日志文件
		'client_timeout'   => '20',				 //client链接服务器时超时时间(s)
		'pidfile'          => '/tmp/y.pid', 		 //服务启动进程id文件保存位置

		//--以上配置项均来自swoole-server的同名配置，可随意参考swoole-server配置说明自主增删--
		'log_size'         => 204800000, 			 //运行时日志 单个文件大小
		'log_dir'          => '/tmp/log',			 //运行时日志 存放目录
	]
];
```

2、在主配置文件中增加controllerMap
```php
'controllerMap' => [
    'swooleasync' => [
        'class' => 'mevyen\swooleAsync\SwooleAsyncController',
    ],
],
```

3、在主配置文件中增加components
```php
'components' => [
    'swooleasync' => [
        'class' => 'mevyen\swooleAsync\SwooleAsyncComponent',
    ]
]
```

4、服务管理
```php
//启动
/path/to/yii/application/yii swooleasync/run start
 
//重启
/path/to/yii/application/yii swooleasync/run restart

//停止
/path/to/yii/application/yii swooleasync/run stop

//查看状态
/path/to/yii/application/yii swooleasync/run stats

//查看进程列表
/path/to/yii/application/yii swooleasync/run list

```

5、执行任务

```php
namespace console\controllers;
use yii\console\Controller;

class TestController extends Controller 
{  
	public function actionSwooleasync(){
		$data = [
			"data":[
				[
					"a" => "test/mail",
					"p" => ["测试邮件1","测试邮件2"]
				],
				...
			],
			"finish" => [
				[
					"a" => "test/mail",
					"p" => ["测试邮件回调1","测试邮件回调2"]
				],
				...
			]
		];
		\Yii::$app->swooleasync->async(json_encode($data));
	}

	public function actionMail($a='',$b=''){
		echo $a.'-'.$b;
	}  
}
```

6、无人值守

服务启动脚本自带服务检测功能，因此可以将启动脚本配置为crontab用以自动保活服务

```php
* * * * * /path/to/yii/application/yii swooleasync/run start >> /var/log/console-app.log 2>&1
```
