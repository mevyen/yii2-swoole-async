<?php

define('YII_ENV', 'dev');

defined('YII_DEBUG') or define('YII_DEBUG', true);

require($local_dir . '/../../../yii2.0/vendor/autoload.php');
require($local_dir . '/../../../yii2.0/vendor/yiisoft/yii2/Yii.php');
require($local_dir . '/../../common/config/bootstrap.php');
require($local_dir . '/../config/bootstrap.php');

$config = yii\helpers\ArrayHelper::merge(
    require($local_dir . '/../../common/config/main.php'), 
    require($local_dir . '/../../common/config/main-local.php'), 
    require($local_dir . '/../config/main.php'), 
    require($local_dir . '/../config/main-local.php')
);
$params = array_merge(
    require($local_dir . '/../../common/config/params.php'), 
    require($local_dir . '/../../common/config/params-local.php'), 
    require($local_dir . '/../config/params.php'), 
    require($local_dir . '/../config/params-local.php'),
    require($local_dir . '/../config/params_swoole.php')
);

$config['params'] = $params;


