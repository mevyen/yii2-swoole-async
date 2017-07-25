<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * 系统基础资源配置
 */
class BaseAsset extends AssetBundle {
    public $basePath = '@webroot';
    
    /**
     * 子类中静态方法单独注册资源时，使用该url
     * @var type 
     */
    static public $_baseUrl;
    
    public function init() {
        
        parent::init();
    }
}