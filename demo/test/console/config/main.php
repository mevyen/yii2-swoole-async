<?php
return [
    'id' => 'app-console',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'console\controllers',
    'controllerMap' => [
        'swooleasync' => [
            'class' => 'mevyen\swooleAsync\SwooleAsyncController',
        ],
    ],

];
