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
        '@common' => '@app/common',
    ],
    'components' => [
        'general' => ['class' => 'app\components\GeneralFunctions'],
        'default' => ['class' => 'app\components\DefaultValue'],
        'customvalidation' => ['class' => 'app\components\CustomValidation'],
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
        'display' => ['class' => 'app\components\Display'],
        'i18n' => [
            'translations' => [
                'app*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@app/messages',
                    'sourceLanguage' => 'en_US',
                    'fileMap' => [
                        'yii' => 'yii.php',
                        'app' => 'app.php',
                        'app/validation' => 'validation.php',
                    ]
                ],
            ],
        ],
    ],
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

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
