<?php

namespace rhertogh\Yii2SecurityTxt\helpers\GPG;

use Crypt_GPG;
use Crypt_GPG_Exception;
use PEAR_Exception;
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

    /** @var GPGDriver|null Specifies the library to use.
     * If `null` it will auto-detect which library to use in the following order: CryptGPG, GnupgExtension
     */
    public static GPGDriver|null $driver = null;

    /**
     * Signs a message.
     *
     * @throws InvalidConfigException
     * @throws Crypt_GPG_Exception
     * @throws PEAR_Exception
     */
    public static function sign(string $message, string $privateKey): string
    {
        return match (static::determineDriver()) {
            GPGDriver::CryptGPG => static::signViaCryptGPG($message, $privateKey),
            GPGDriver::GnupgExtension => static::signViaGnupgExtension($message, $privateKey),
        };
    }

    /**
     * Verifies a message and returns the content or `false` if the signature is invalid.
     *
     * @throws InvalidConfigException
     * @throws Crypt_GPG_Exception
     * @throws PEAR_Exception
     */
    public static function verify(string $message, string $publicKey): string|false
    {
        return match (static::determineDriver()) {
            GPGDriver::CryptGPG => static::verifyViaCryptGPG($message, $publicKey),
            GPGDriver::GnupgExtension => static::verifyViaGnupgExtension($message, $publicKey),
        };
    }

    /**
     * @throws InvalidConfigException
     */
    protected static function determineDriver(): GPGDriver
    {
        $driver = static::$driver;

        if ($driver === null) {
            if (class_exists(Crypt_GPG::class)) {
                $driver = GPGDriver::CryptGPG;
            } elseif (extension_loaded('gnupg')) {
                $driver = GPGDriver::GnupgExtension;
            } else {
                throw new InvalidConfigException('Either the Crypt_GPG package (https://packagist.org/packages/pear/crypt_gpg)'
                    . ' or the "gnupg" extension (https://www.php.net/manual/en/book.gnupg.php) must be installed.');
            }
        } else {
            if ($driver === GPGDriver::CryptGPG) {
                if (!class_exists(Crypt_GPG::class)) {
                    throw new InvalidConfigException('When using the Crypt_GPG package (https://packagist.org/packages/pear/crypt_gpg) it must be installed.');
                }
            } elseif ($driver === GPGDriver::GnupgExtension) {
                if (!extension_loaded('gnupg')) {
                    throw new InvalidConfigException('When using the "gnupg" extension (https://www.php.net/manual/en/book.gnupg.php) it must be installed.');
                }
            } else {
                throw new \LogicException('Unknown GPGDriver "' . $driver->name . '".');
            }
        }

        return $driver;
    }
}
