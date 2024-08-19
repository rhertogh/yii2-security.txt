<?php

namespace rhertogh\Yii2SecurityTxt\helpers\GPG\traits;

use gnupg;
use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\FileHelper;

trait GnupgExtensionTrait
{
    /**
     * @throws InvalidConfigException
     */
    protected static function signViaGnupgExtension($message, $privateKey)
    {
        Yii::beginProfile('Generate PGP signature', __METHOD__);
        $gpg = static::getGnupg($gnupgHome);
        try {
            $gpg->seterrormode(GNUPG_ERROR_EXCEPTION);

            $keyInfo = $gpg->import($privateKey);
            if ($keyInfo === false) {
                throw new InvalidConfigException('Unable to import private key. Debug info: '
                    . var_export(static::generateGnupgDebugInfo($gpg), true));
            }
            $gpg->addsignkey($keyInfo['fingerprint']);

            $gpg->setsignmode(gnupg::SIG_MODE_CLEAR);
            $output = $gpg->sign($message);
        } finally {
            FileHelper::removeDirectory($gnupgHome);
        }
        if ($output === false) {
            throw new InvalidConfigException('Unable to sign the message. Debug info: '
                . var_export(static::generateGnupgDebugInfo($gpg), true));
        }
        Yii::endProfile('Generate PGP signature', __METHOD__);
        return $output;
    }

    /**
     * @throws InvalidConfigException
     */
    protected static function verifyViaGnupgExtension($message, $publicKey)
    {
        Yii::beginProfile('Verify PGP signature', __METHOD__);
        $gpg = static::getGnupg($gnupgHome);
        try {
            $gpg->seterrormode(GNUPG_ERROR_EXCEPTION);

            $keyInfo = $gpg->import($publicKey);
            if ($keyInfo === false) {
                throw new InvalidConfigException('Unable to import public key. Debug info: '
                    . var_export(static::generateGnupgDebugInfo($gpg), true));
            }
            $gpg->addencryptkey($keyInfo['fingerprint']);
            $info = $gpg->verify($message,false,$plaintext);
        } finally {
            FileHelper::removeDirectory($gnupgHome);
        }
        if ($info === false) {
            throw new InvalidConfigException('Unable to verify the message. Debug info: '
                . var_export(static::generateGnupgDebugInfo($gpg), true));
        }
        if ($info[0]['summary'] !== 0) {
            // Invalid signature
            return false;
        }

        Yii::endProfile('Verify PGP signature', __METHOD__);
        return $plaintext;
    }

    protected static function generateGnupgDebugInfo(gnupg $gpg)
    {
        $debugInfo = [
            'engineInfo' => $gpg->getengineinfo(),
            'errorInfo' => $gpg->geterrorinfo(),
        ];

        if (!is_writable($debugInfo['engineInfo']['home_dir'])) {
            $debugInfo['hint'] = 'The gnupg home directory (' . $debugInfo['engineInfo']['home_dir'] . ') is not writable.';
        }

        return $debugInfo;
    }

    protected static function getGnupg(&$gnupgHome = null): gnupg
    {
        if (empty($gnupgHome)) {
            $gnupgHome = Yii::getAlias('@runtime') . '/gnupg/' . uniqid(more_entropy: true);
            FileHelper::createDirectory($gnupgHome);
        }

        return new gnupg(['home_dir' => $gnupgHome]);
    }
}
