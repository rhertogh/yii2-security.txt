<?php

/// WARNING! This configuration is optimized for local development and should NOT be used in any other environment
/// (for both security and performance)!

// phpcs:disable Generic.Files.LineLength.TooLong -- Sample documentation
return [

    'id' => 'Yii2SecurityTxt',
    'name' => 'Yii2 security.txt sample',
    'basePath' => dirname(__DIR__),

    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],

    'timeZone' => 'UTC',

    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',

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
