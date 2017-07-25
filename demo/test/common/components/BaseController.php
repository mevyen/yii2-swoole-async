<?php

namespace common\components;

use Yii;
use yii\web\Controller;
use common\models\OptionLogs;

/**
 * 所有项目的控制器基类
 * 目前封装了操作日志记录逻辑
 * @author huangpeixun
 */
class BaseController extends Controller {

    /**
     * logPointer 是否开启记录操作日志 默认不开启
     */
    private $logPointer = false;

    /**
     * 操作日志对应的数据表名
     * @var string
     */
    private $tableName = 'option_logs';

    /**
     * 输入参数
     * @var string
     */
    private $inParams = [];

    /**
     * 输入参数
     * @var string
     */
    private $outParams = [];

    /**
     * 执行后操作
     * @param type $action
     * @param type $result
     * @return type
     */
    public function afterAction($action, $result) {
        //action执行结束时统一记录日志
        $this->optionLog();
        return parent::afterAction($action, $result);
    }

    /**
     * 记录日志
     * @param boolean $logPointer [description]
     */
    protected function setLogPointer($inParams = '', $outParams = '') {
        $this->logPointer = true;
        $this->setInParams($inParams);
        $this->setOutParams($outParams);
    }

    /**
     * [getLogOn description]
     * @return [type] [description]
     */
    protected function getLogPointer() {
        return $this->logPointer;
    }

    /**
     * [getInParams description]
     * @return [type] [description]
     */
    protected function getInParams() {
        return $this->inParams;
    }

    /**
     * [getOutParams description]
     * @return [type] [description]
     */
    protected function getOutParams() {
        return $this->outParams;
    }

    /**
     * 设置
     * @return [type] [description]
     */
    protected function setInParams($params = '') {
        $this->inParams[] = $params;
    }

    /**
     * [getInParams description]
     * @return [type] [description]
     */
    protected function setOutParams($outParams = '') {
        $this->outParams[] = $outParams;
    }

    /**
     * 记录操作日志
     * @return [type] [description]
     */
    private function optionLog() {
        if ($this->logPointer) {
            OptionLogs::log($this->inParams, $this->outParams, OptionLogs::TYPE_ACTION);
        }
    }

}
