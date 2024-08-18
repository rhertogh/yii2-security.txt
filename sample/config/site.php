<?php

/// WARNING! This configuration is optimized for local development and should NOT be used in any other environment
/// (for both security and performance)!

use sample\components\UserComponent;
use sample\models\User as UserIdentity;
use sample\modules\api\ApiModule;
use yii\helpers\ArrayHelper;

// phpcs:disable Generic.Files.LineLength.TooLong  -- Sample documentation
return ArrayHelper::merge(require('main.php'), [

    'bootstrap' => [
        'debug',
        'security.txt',
    ],

    'controllerNamespace' => 'sample\\controllers\\web',

    'defaultRoute' => 'default/index',

    'modules' => [
        'security.txt' => [
            'class' => rhertogh\Yii2SecurityTxt\SecurityTxtModule::class,

            // security.txt Fields.
            'contact' => [ // Required, https://www.rfc-editor.org/rfc/rfc9116.html#name-contact.
                'admin@example.com',
                'https://example.com/report',
            ],
            'policy' => 'https://example.com/report-policy', // Optional, https://www.rfc-editor.org/rfc/rfc9116.html#name-policy.
            'preferredLanguages' => 'en', // Optional, https://www.rfc-editor.org/rfc/rfc9116.html#name-preferred-languages.
            'acknowledgments' => 'https://example.com/security-acknowledgments', // Optional, https://www.rfc-editor.org/rfc/rfc9116.html#name-acknowledgments.
            'canonical' => 'https://example.com/.well-known/security.txt', // Optional, https://www.rfc-editor.org/rfc/rfc9116.html#name-canonical.
            'encryption' => 'dns:5d2d37ab76d47d36._openpgpkey.example.com?type=OPENPGPKEY', // Optional, https://www.rfc-editor.org/rfc/rfc9116.html#name-encryption.
            'expires' => '+1 day midnight', // Optional, https://www.rfc-editor.org/rfc/rfc9116.html#name-expires.
            'hiring' => 'https://example.com/jobs', // Optional, https://www.rfc-editor.org/rfc/rfc9116.html#name-hiring.

            // Optional comments
            'headerComment' => <<<TXT
                This is a header comment.
                It's included at the beginning of the security.txt file.
                TXT,
            'footerComment' => <<<TXT
                This is a footer comment.
                It's included at the end of the security.txt file.
                TXT,
            'fieldComments' => [ // Specifies an optional comment per field (will be included before the field in the security.txt file.)
                'acknowledgments' => 'Hall of Fame',
                'hiring' => 'Want to join our awesome team?',
            ],

            // Optional, ASCII-armored PGP private key.
            // Note: Requires the Crypt_GPG package (https://packagist.org/packages/pear/crypt_gpg)
            // or the "gnupg" extension (https://www.php.net/manual/en/book.gnupg.php) to be installed.
            'pgpPrivateKey' => getenv('YII2_SECURITY_TXT_PGP_PRIVATE_KEY'),
        ],
        'debug' => [
            'class' => yii\debug\Module::class,
            'allowedIPs' => ['*'],
        ],
    ],

    'components' => [
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
        ],
        'request' => [
            'cookieValidationKey' => 'secret',
        ],
    ],
]);
