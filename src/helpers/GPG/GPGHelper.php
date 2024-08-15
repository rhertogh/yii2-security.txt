<?php

namespace rhertogh\Yii2SecurityTxt\helpers\GPG;

use Crypt_GPG;
use rhertogh\Yii2SecurityTxt\helpers\GPG\enums\GPGDriver;
use rhertogh\Yii2SecurityTxt\helpers\GPG\traits\CryptGPGTrait;
use rhertogh\Yii2SecurityTxt\helpers\GPG\traits\GnupgExtensionTrait;
use yii\base\InvalidConfigException;

/**
 * A helper class for the GNU Privacy Guard.
 * It can run with either the Crypt_GPG package or the "gnupg" extension.
 *
 * @link https://packagist.org/packages/pear/crypt_gpg
 * @link https://www.php.net/manual/en/book.gnupg.php
 */
class GPGHelper
{
    use CryptGPGTrait;
    use GnupgExtensionTrait;

    public static GPGDriver|null $driver = null;

    /**
     * Sign a message.
     *
     * @throws InvalidConfigException
     */
    public static function sign(string $message, string $privateKey): string
    {
        $driver = static::$driver;

        if (
            $driver === GPGDriver::CryptGPG
            || $driver === null && class_exists(Crypt_GPG::class)
        ) {
            $output = static::signViaCryptGPG($message, $privateKey);
        } elseif (
            $driver === GPGDriver::GnupgExtension
            || $driver === null && extension_loaded('gnupg')
        ) {
            $output = static::signViaGnupgExtension($message, $privateKey);
        } else {
            throw new InvalidConfigException('Either the Crypt_GPG package (https://packagist.org/packages/pear/crypt_gpg)'
                . ' or the "gnupg" extension (https://www.php.net/manual/en/book.gnupg.php) must be installed.');
        }

        return $output;
    }

    /**
     * Verify a message.
     *
     * @throws InvalidConfigException
     */
    public static function verify(string $message, string $privateKey): string
    {
        if (class_exists(Crypt_GPG::class)) {
            $output = static::signViaCryptGPG($message, $privateKey);
        } elseif (extension_loaded('gnupg')) {
            $output = static::signViaGnupgExtension($message, $privateKey);
        } else {
            throw new InvalidConfigException('Either the Crypt_GPG package (https://packagist.org/packages/pear/crypt_gpg)'
                . ' or the "gnupg" extension (https://www.php.net/manual/en/book.gnupg.php) must be installed.');
        }

        return $output;
    }
}
