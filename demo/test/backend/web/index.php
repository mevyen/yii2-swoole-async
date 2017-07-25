<?php

//当前项目的目录位置
$local_dir = __DIR__ ;

require(__DIR__ . '/../../common/config/env-config.php');

$application = new yii\web\Application($config);
$application->run();
