<?php

namespace backend\assets;

use yii\web\View;

use backend\assets\BaseAsset;

/**
 * 登陆资源配置
 */
class LoginAsset extends BaseAsset {
    public $css = [
        '/css/layout.css',
        '/css/login.css',
    ];
    
    public $js = [
        '/js/jquery-1.7.2.min.js',
        '/js/js.js',
        '/js/Validform_v5.3.2_min.js'
    ];
    
    public $jsOptions = [
        'position' => View::POS_HEAD
    ];

}
