<?php

ini_set("memory_limit", "-1");
set_time_limit(3600);

$params = require(__DIR__ . '/params.php');
$db = require(__DIR__ . '/db.php');

$config = [
    'id' => 'eipl-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'timeZone' => 'Asia/Calcutta',
    'controllerNamespace' => 'app\commands',
    'aliases' => [
        '@common' =>'@app/common',
    ],
    'components' => [
//        'inboxParseService' => ['class' => 'common\services\InboxParseService'],
        'general' => ['class' => 'app\components\GeneralFunctions'],
        'default' => ['class' => 'app\components\DefaultValue'],
        'encrypter' => [
            'class' => '\nickcv\encrypter\components\Encrypter',
            'globalPassword' => '1234567890123456',
            'iv' => '1234567890123456',
            'useBase64Encoding' => true,
            'use256BitesEncoding' => false,
        ],
        'cache' => ['class' => 'yii\caching\FileCache',],
        'log' => [
            'targets' => [
                    [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        'session' => [
            'class' => 'yii\web\Session',
        ],
        'controls' => ['class' => 'app\components\Controls'],
        'path' => ['class' => 'app\components\Path'],
        'operation' => ['class' => 'app\components\Operation'],
        'label' => ['class' => 'app\components\GeneralLabels'],
//        'user' => [
//            'class' => 'webvimark\modules\UserManagement\components\UserConfig',
//            'identityClass' => 'app\models\User',
//        ],
//        'container' => [
//            'class' => 'yii\di\Container',
//            'definitions' => [],
//            'singletons' => [
//                common\services\InboxParseService::class => common\services\InboxParseService::class,
//            ],
//        ],
    ],
//    'container' => [
//        'singletons' => [
//            common\services\InboxParseService::class => common\services\InboxParseService::class,
//        ],
//    ],
    'modules' => [
        'user-management' => [
            'class' => 'app\modules\usermanagement\usermanagement',
        ],
    ],
    'params' => $params,
        /*
          'controllerMap' => [
          'fixture' => [ // Fixture generation command line.
          'class' => 'yii\faker\FixtureController',
          ],
          ],
         */
];
//Yii::setAlias('@common', dirname(__DIR__) . '/common');
if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
