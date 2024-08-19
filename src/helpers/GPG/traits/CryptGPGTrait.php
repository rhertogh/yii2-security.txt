<?php

namespace rhertogh\Yii2SecurityTxt\helpers\GPG\traits;

use Crypt_GPG;
use Crypt_GPG_Exception;
use Crypt_GPG_KeyNotFoundException;
use Crypt_GPG_Signature;
use PEAR_Exception;
use Yii;
use yii\helpers\FileHelper;

trait CryptGPGTrait
{
    /**
     * @throws Crypt_GPG_Exception
     * @throws PEAR_Exception
     */
    protected static function signViaCryptGPG(string $message, string $privateKey): string
    {
        Yii::beginProfile('Generate PGP signature', __METHOD__);
        $gpg = static::getCryptGpg($gnupgHome);
        try {
            $keyInfo = $gpg->importKey($privateKey);
            $gpg->addSignKey($keyInfo['fingerprint']);
            $output = $gpg->sign($message, Crypt_GPG::SIGN_MODE_CLEAR);
        } finally {
            FileHelper::removeDirectory($gnupgHome);
        }
        Yii::endProfile('Generate PGP signature', __METHOD__);
        return $output;
    }

    /**
     * @throws Crypt_GPG_Exception
     * @throws PEAR_Exception
     */
    protected static function verifyViaCryptGPG(string $message, string $publicKey): string|false
    {
        Yii::beginProfile('Verify PGP signature', __METHOD__);
        $gpg = static::getCryptGpg($gnupgHome);
        try {
            $keyInfo = $gpg->importKey($publicKey);
            $gpg->addEncryptKey($keyInfo['fingerprint']);
            /** @var array{
             *     data: string,
             *     signatures: Crypt_GPG_Signature[],
             * } $info
             */
            $info = $gpg->decryptAndVerify($message);
        } catch (Crypt_GPG_KeyNotFoundException) {
            return false;
        } finally {
            FileHelper::removeDirectory($gnupgHome);
        }
        if (!$info['signatures'][0]->isValid()) {
            return false;
        }
        Yii::endProfile('Verify PGP signature', __METHOD__);
        return $info['data'];
    }

    protected static function getCryptGpg(&$gnupgHome = null): Crypt_GPG
    {
        if (empty($gnupgHome)) {
            $gnupgHome = Yii::getAlias('@runtime') . '/gnupg/' . uniqid(more_entropy: true);
            FileHelper::createDirectory($gnupgHome);
        }

        return new Crypt_GPG(['homedir' => $gnupgHome]);
    }
}
