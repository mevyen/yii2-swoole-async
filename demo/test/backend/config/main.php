<?php
return [
    'id' => 'app-backend',
    'defaultRoute'=>'/login/index',
    'basePath' => dirname(__DIR__),
    'name' => 'website',
   
    'controllerNamespace' => 'backend\controllers',
    'homeUrl' => '/login/index',
    
    'components' => [

        'request'=>[
            'class' => 'yii\web\Request',
            'enableCsrfValidation'=>false,
            'cookieValidationKey' => 'abycLGNffIytuRfQ94_Q6t9OZpiRxlab',
        ],

        'swooleasync' => [
            'class' => 'mevyen\swooleAsync\SwooleAsyncComponent',
        ],
       
    ],
];
