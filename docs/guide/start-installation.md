Installing Yii2 security.txt
============================

This guide assumes you have already got Yii2 installed and running.
If not, [install Yii2](https://www.yiiframework.com/doc/guide/2.0/en/start-installation) first.

Prerequisites
-------------
If you haven't done so please read [What do you need to know before installing Yii2 security.txt](start-prerequisites.md)
before continuing the installation.

Installation
------------
The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

To install the latest stable version of Yii2 security.txt run:
```bash
composer require rhertogh/yii2-security.txt
```

Configuration
-------------

Once the extension is installed, simply modify your application configuration as follows:
```php title="Appplication Configuration (sample/config/main.php)"
return [
    'bootstrap' => [
        'security.txt',
        // ...
    ],
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

            // Optional, ASCII-armored PGP private key, please see [Signing security.txt](start-signing.md) for details.
            // Note: Requires the Crypt_GPG package (https://packagist.org/packages/pear/crypt_gpg)
            // or the "gnupg" extension (https://www.php.net/manual/en/book.gnupg.php) to be installed.
            'pgpPrivateKey' => getenv('YII2_SECURITY_TXT_PGP_PRIVATE_KEY'),
        ],
    ],
```

Signing security.txt
--------------------
While not required, it is recommended that the security.txt file is digitally signed using an OpenPGP cleartext signature.
Please see [Signing security.txt](start-signing.md) on how to enable signing the security.txt file.
