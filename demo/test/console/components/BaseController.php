<?php

namespace console\components;

use Yii;
use yii\db\Query;
use yii\helpers\ArrayHelper;

/**
 * 基类控制器
 * @author wangjiacheng
 */
class BaseController extends \yii\console\Controller {
    
    // 同步设置表定义
    const IC_SYNC_SETTING = 'ic_sync_setting';

    /**
     * 初始化 
     */
    public function init() {
        
    }

    /**
     * 执行前操作
     * @param type $action
     * @return boolean
     */
    public function beforeAction($action) {
        return parent::beforeAction($action);
    }

    /**
     * 执行后操作
     * @param type $action
     * @param type $result
     * @return type
     */
    public function afterAction($action, $result) {
        return parent::afterAction($action, $result);
    }
}
