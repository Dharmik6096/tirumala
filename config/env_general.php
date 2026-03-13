<?php
return [
    'bootstrap' => ['log', 'queue'],
    'components' => [
        'redis' => [
            'class' => 'yii\redis\Connection',
            'hostname' => '192.168.1.236',
            'port' => 6379,
            'database' => 0,
        ],
        'cache' => [
            'class' => 'yii\redis\Cache',
            'redis' => 'redis',
        ],
        'session' => [
            'class' => 'yii\redis\Session',
        ],
        'queue' => [
            'class' => \yii\queue\amqp\Queue::class,
            'host' => '192.168.1.236',
            'port' => 5672,
            'user' => 'mobile',
            'password' => 'mobile',
            'queueName' => 'inbox-queue',
        ],
    ],
];