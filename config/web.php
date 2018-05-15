<?php

ini_set("memory_limit", "-1");
set_time_limit(3600);
/* test commit */
$params = require(__DIR__ . '/params.php');

//echo Yii::$app->session['LanguageCode'];exit
$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'timeZone' => 'Asia/Calcutta',
    // 'language'=>'gu',
    'components' => [
        'session' => ['name' => 'tirumala'],
        'general' => [ 'class' => 'app\components\GeneralFunctions'],
        'dropdown' => [ 'class' => 'app\components\DropDown'],
        'label' => [ 'class' => 'app\components\GeneralLabels'],
        'grid' => [ 'class' => 'app\components\Grid'],
        'display' => [ 'class' => 'app\components\Display'],
        'controls' => [ 'class' => 'app\components\Controls'],
        'operation' => [ 'class' => 'app\components\Operation'],
        'path' => [ 'class' => 'app\components\Path'],
        'warning' => [ 'class' => 'app\components\Warning'],
        'sql' => [ 'class' => 'app\components\SqlCreate'],
        'bsmartsms' => [ 'class' => 'app\components\BsmartSmsApi'],
        'EIPLSecurity' => [ 'class' => 'app\components\EIPLSecurity'],
        'apiError' => ['class' => 'app\modules\webservice\components\SetError'],
        'urlManager' => [
            'class' => 'app\components\UrlManager',
            'showScriptName' => false,
            'enablePrettyUrl' => true,
            // 'enableStrictParsing' => true,
            'rules' => [
                'site' => 'site/dashboard',
                'site/index' => 'site/dashboard',
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
                //'http://bipl.pcdf-eipl.com:85/bipl-services'=>'pcdf/restservices/bipl/bipl-services',
                'bipl-services' => 'restservices/bipl/bipl-services',
                'stellapps-services' => 'restservices/stellapps/stellapps-services',
                ['class' => 'app\components\UrlRule', 'connectionID' => 'db', 'pattern' => '...', 'route' => 'site/index',],
            //  ['class' => 'app\components\UrlRule', 'connectionID' => 'db'],
            ],
        /* 'urlManager' => [
          'rules' => [
          // ...
          ['class' => 'common\helpers\UrlRule', 'connectionID' => 'db', /* ... */        ],
        /*  ],
          ], */
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'PqRQWzXJwmIUsAA96iTQhWvWzgREpvc2',
        ],
        'cache' => [ 'class' => 'yii\caching\FileCache'],
//        'user' => [
//            'identityClass' => 'app\models\User',
//            'enableAutoLogin' => true,
//        ],
        'user' => [
            'class' => 'webvimark\modules\UserManagement\components\UserConfig',
            //'enableAutoLogin' => true,
            // Comment this if you don't want to record user logins
            'on afterLogin' => function($event) {

                \webvimark\modules\UserManagement\models\UserVisitLog::newVisitor($event->identity->id);
            }
        ],
        'errorHandler' => ['errorAction' => 'site/error'],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.gmail.com', //sample
                'username' => 'knshah2009@gmail.com',
                'password' => 'aura$2014',
                'port' => '587',
                'encryption' => 'tls',
                'streamOptions' => [
                    'ssl' => [
                        'allow_self_signed' => true,
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                    ],
                ],
            ],
        ],
        'view' => [
            'class' => 'yii\web\View',
            'theme' => [
                'class' => 'yii\base\Theme',
                'basePath' => '@app/themes/pcdf',
                'baseUrl' => '@web/themes/pcdf',
                'pathMap' => ['@app/views' => '@app/themes/pcdf',
                    '@vendor/kartik-v/yii2-dynagrid/views' => '@app/themes/pcdf/dynaGrid/views'
                //'@app/modules' => '@app/themes/tradiecom/modules',
                //'@vendor/webvimark/module-user-management/views' => '@app/themes/tradiecom/modules/UserManagement/views',
                ],
            ],
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning', 'trace', 'info'],
                ],
            ],
        ],
        'i18n' => [
            'translations' => [
                'app*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    //'class' => 'yii\i18n\DbMessageSource',
                    'basePath' => '@app/messages',
                    'sourceLanguage' => 'en_US',
                    'fileMap' => [
                        'yii' => 'yii.php',
                        'app' => 'app.php',
                        'app/validation' => 'validation.php',
                    ]
                ],
                'captcha*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@app/messages',
                ],
            ],
        ],
        'db' => require(__DIR__ . '/db.php'),
        'db_rmrd' => require(__DIR__ . '/db_rmrd.php'),
        'db_reil' => require(__DIR__ . '/db_reil.php'),
        /*
          'urlManager' => [
          'enablePrettyUrl' => true,
          'showScriptName' => false,
          'rules' => [
          ],
          ],
         */
        'encrypter' => [
            'class' => '\nickcv\encrypter\components\Encrypter',
            'globalPassword' => '1234567890123456',
            'iv' => '1234567890123456',
            'useBase64Encoding' => true,
            'use256BitesEncoding' => false,
        ],
        'formatter' => [
            'nullDisplay' => 'N/A',
            'class' => 'app\components\Formatter',
        ]
    ],
    'modules' => [
        'user-management' => [
            'class' => 'webvimark\modules\UserManagement\UserManagementModule',
            // 'enableRegistration' => true,
            // Here you can set your handler to change layout for any controller or action
            // Tip: you can use this event in any module
            'on beforeAction' => function(yii\base\ActionEvent $event) {
                if ($event->action->uniqueId == 'user-management/auth/login') {
                    $event->action->controller->layout = 'loginLayout.php';
                };
            },],
        'dynagrid' => [ 'class' => '\kartik\dynagrid\Module',
            'minPageSize' => 1,
            'maxPageSize' => 500,
            'defaultPageSize' => 20],
        'gridview' => ['class' => '\kartik\grid\Module'],
        'translation' => ['class' => 'app\modules\translation\Translation'],
        'import' => [ 'class' => 'app\modules\import\importData'],
        'customimport' => [ 'class' => 'app\modules\customimport\importData'],
        'geo' => ['class' => 'app\modules\geo\geo',],
        'organisation' => ['class' => 'app\modules\organisation\Organisation'],
        'globalmaster' => ['class' => 'app\modules\globalmaster\GlobleMaster',],
        'dcsaccounting' => ['class' => 'app\modules\dcsaccounting\DcsAccounting',],
        'dcsaccounting' => ['class' => 'app\modules\dcsaccounting\DcsAccounting',],
        'staffmanagement' => ['class' => 'app\modules\staffmanagement\StaffManagement',],
        'setting' => ['class' => 'app\modules\setting\Setting',],
        'miscellaneous' => ['class' => 'app\modules\miscellaneous\Miscellaneous',],
        'dcsoperation' => ['class' => 'app\modules\dcsoperation\dcsoperation',],
        'installation' => ['class' => 'app\modules\installation\Installation',],
        'backup' => ['class' => 'spanjeta\modules\backup\Module',],
        'hardwareconfigutation' => ['class' => 'app\modules\hardwareconfigutation\Hardwareconfiguration',],
        'general' => ['class' => 'app\modules\general\General',],
        'collection' => ['class' => 'app\modules\collection\collection',],
        'product' => ['class' => 'app\modules\product\Product',],
        'applicability' => ['class' => 'app\modules\applicability\Applicability',],
        'details' => ['class' => 'app\modules\details\Details'],
        'payment' => ['class' => 'app\modules\payment\Payment'],
        'restservices' => ['class' => 'app\modules\restservices\rest',],
        'bipl' => ['class' => 'app\modules\bipl\bipl',],
        'report' => ['class' => 'app\modules\report\report'],
        'complaint' => [ 'class' => 'app\modules\complaint\Complaint',],
        'notification' => ['class' => 'app\modules\notification\Notification',],
        'jasperreports' => [ 'class' => 'app\modules\jasperreports\JasperReports',],
        'verification' => [ 'class' => 'app\modules\verification\Verification',],
        'changelog' => [ 'class' => 'app\modules\changelog\ChangeLog',],
        'webservice' => [ 'class' => 'app\modules\webservice\Webservice',],
        'email' => ['class' => 'app\modules\email\email',],
        'transporter' => ['class' => 'app\modules\transporter\transporter',],
        'rmrd' => ['class' => 'app\modules\rmrd\Rmrd'],
        'stellapps' => ['class' => 'app\modules\stellapps\Stellapps',],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
