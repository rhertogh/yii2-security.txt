<?php

/// WARNING! This configuration is optimized for local development and should NOT be used in any other environment
/// (for both security and performance)!

use sample\components\UserComponent;
use sample\models\User as UserIdentity;
use sample\modules\api\ApiModule;
use yii\helpers\ArrayHelper;
use yii\web\Request;

// phpcs:disable Generic.Files.LineLength.TooLong  -- Sample documentation
return ArrayHelper::merge(require('site.php'), [

    'modules' => [
        'security.txt' => [
            'contact' => [
                'admin@example.com',
                'https://example.com/report',
            ],
            'policy' => 'https://example.com/report-policy',
            'preferredLanguages' => ['en', 'es'],
            'acknowledgments' => 'https://example.com/security-acknowledgments',
            'canonical' => [
                'https://example.com/.well-known/security.txt',
            ],
            'encryption' => 'dns:5d2d37ab76d47d36._openpgpkey.example.com?type=OPENPGPKEY',
            'expires' => '+1 year midnight',
            'hiring' => 'https://example.com/jobs',

            'headerComment' => <<<TXT
                        This is a header comment.
                        It's included at the beginning of the security.txt file.
                        TXT,
            'footerComment' => <<<TXT
                        This is a footer comment.
                        It's included at the end of the security.txt file.
                        TXT,
            'fieldComments' => [
                'acknowledgments' => 'Hall of Fame',
                'hiring' => 'Want to join our awesome team?',
            ],

            'pgpPrivateKey' => getenv('YII2_SECURITY_TXT_PGP_PRIVATE_KEY'),

            'cacheControl' => 123,
        ],
    ],
]);
