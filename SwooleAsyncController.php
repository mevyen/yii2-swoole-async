<?php
/**
 * yii2 基于swoole的异步处理
 * $Id: SwooleAsyncController.php 9507 2016-09-29 06:48:44Z mevyen $
 * $Date: 2016-09-29 14:48:44 +0800 (Wed, 07 Sep 2016) $
 * $Author: mevyen $
 */
namespace mevyen\swooleAsync;

use Yii;
use yii\console\Controller;
use yii\console\Exception;
use mevyen\swooleAsync\src\SwooleService;

class SwooleAsyncController extends Controller {
    
    /**
     * 存储swooleAsync配置中的所有配置项
     * @var array
     */
    private $settings = [];
    /**
     * 默认controller
     * @var string
     */
    public $defaultAction = 'run';

    /**
     * 初始化
     * @return [type] [description]
     */
    public function init() {

        parent::init();
        $this->prepareSettings();

    }

    /**
     * 初始化配置信息
     * @return [type] [description]
     */
    protected function prepareSettings()
    {
        $runtimePath = Yii::$app->getRuntimePath();
        $this->settings = [
            'host'              => '127.0.0.1',
            'port'              => '9512',
            'process_name'      => 'swooleServ',
            'open_tcp_nodelay'  => '1',
            'daemonize'         => '1',
            'worker_num'        => '2',
            'task_worker_num'   => '2',
            'task_max_request'  => '10000',
            'pidfile'         => $runtimePath.'/yii2-swoole-async/tmp/yii2-swoole-async.pid',
            'log_dir'           => $runtimePath.'/yii2-swoole-async/log',
            'task_tmpdir'       => $runtimePath.'/yii2-swoole-async/task',
            'log_file'          => $runtimePath.'/yii2-swoole-async/log/http.log',
            'log_size'          => 204800000,
        ];
        try {
            $settings = Yii::$app->params['swooleAsync'];
        }catch (yii\base\ErrorException $e) {
            throw new yii\base\ErrorException('Empty param swooleAsync in params. ',8);
        }

        $this->settings = yii\helpers\ArrayHelper::merge(
            $this->settings,
            $settings
        );
    }

    /**
     * 启动服务action
     * @param  array  $args [description]
     * @return [type]       [description]
     */
    public function actionRun($mode='start'){

        $swooleService = new SwooleService($this->settings,Yii::$app);
        switch ($mode) {
            case 'start':
                $swooleService->serviceStart();
                break;
            case 'restart':
                $swooleService->serviceStop();
                $swooleService->serviceStart();
                break;
            case 'stop':
                $swooleService->serviceStop();
                break;
            case 'stats':
                $swooleService->serviceStats();
                break;
            case 'list':
                $swooleService->serviceList();
                break;
            default:
                exit('error:参数错误');
                break;
        }
    }

}