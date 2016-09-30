<?php
/**
 * yii2 基于swoole的异步处理
 * $Id$
 * $Date$
 * $Author$
 */
namespace mevyen\swooleAsync;

use Yii;
use yii\console\Controller;
use yii\console\Exception;
use mevyen\swooleAsync\src\SwooleService;

class SwooleAsyncController extends Controller {
    
    /**
     * [$settings description]
     * @var array
     */
    private $settings = [];
    /**
     * @var string the name of the default action. Defaults to 'run'.
     */
    public $defaultAction = 'run';

    /**
     * [init description]
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
            'pidfile'         => $runtimePath.'/yii2-swoole-asynchronous/tmp/yii2-swoole-asynchronous.pid',
            'log_dir'           => $runtimePath.'/yii2-swoole-asynchronous/log',
            'task_tmpdir'       => $runtimePath.'/yii2-swoole-asynchronous/task',
            'log_file'          => $runtimePath.'/yii2-swoole-asynchronous/log/http.log',
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
     * [actionRun description]
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
            case 'status':
                $swooleService->serviceStatus();
                break;
            case 'list':
                $swooleService->serviceList();
                break;
            default:
                exit('error:参数错误');
                break;
        }
    }
    
    /**
     * Provides the command description.
     * @return string the command description.
     */
    public function getHelp() {
        $commandUsage = Yii::getAlias('@runnerScript').' '.$this->getName();
        return <<<RAW
Usage: {$commandUsage} <action>

Actions:
    view <tags> - Show active tasks, specified by tags.
    run <options> <tags> - Run suitable tasks, specified by tags (default action).
    help - Show this help.

Tags:
    [tag1] [tag2] [...] [tagN] - List of tags

Options:
    [--tagPrefix=value]
    [--interpreterPath=value]
    [--logsDir=value]
    [--logFileName=value]
    [--bootstrapScript=value]
    [--timestamp=value]

RAW;
    }

}