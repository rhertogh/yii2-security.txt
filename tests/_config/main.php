<?php

// phpcs:disable Generic.Files.LineLength.TooLong -- Sample documentation
return [

    'id' => 'testapp',
    'name' => 'Yii2 security.txt test',
    'basePath' => dirname(__DIR__),
    'runtimePath' => dirname(__DIR__) . '/_runtime',

    'timeZone' => 'UTC',

    'vendorPath' => dirname(__DIR__, 2) . '/vendor',

    'bootstrap' => [
        'log',
    ],

    'components' => [
        'log' => [
            'traceLevel' => 10,
            'flushInterval' => 1,
            'targets' => [
                'file' => [
                    'class' => yii\log\FileTarget::class,
                    'exportInterval' => 1,
                    'levels' => ['error', 'warning', 'info', 'trace'],
                ],
            ],
        ],
    ],
];
