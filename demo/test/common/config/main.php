<?php
return [
    'language' => 'zh-CN',
    'charset' => 'utf-8',
    'bootstrap' => ['log'],
    'vendorPath' => dirname(dirname(dirname(__DIR__))) . '/yii2.0/vendor',
    'modules' => [
        'gii' => 'yii\gii\Module',
        'gridview' => 'kartik\grid\Module',
    ],
    'components' => [


        'urlManager' => [
           'enablePrettyUrl' => true,
           'showScriptName' => false,
        ],

    ]
];




