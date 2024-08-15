<?php

namespace rhertogh\Yii2SecurityTxt\helpers\GPG\traits;

use Crypt_GPG;
use Crypt_GPG_Exception;
use PEAR_Exception;
use Yii;

trait CryptGPGTrait
{
    /**
     * @throws Crypt_GPG_Exception
     * @throws PEAR_Exception
     */
    protected static function signViaCryptGPG(string $message, string $privateKey)
    {
        Yii::beginProfile('Generate PGP signature', __METHOD__);
        $gpg = new Crypt_GPG(static::cryptGpgDefaultOptions());
        $keyInfo = $gpg->importKey($privateKey);
        $gpg->addSignKey($keyInfo['fingerprint']);
        $output = $gpg->sign($message, Crypt_GPG::SIGN_MODE_CLEAR);
        Yii::endProfile('Generate PGP signature', __METHOD__);
        return $output;
    }

    protected static function cryptGpgDefaultOptions()
    {
        $gnupgHome = getenv('GNUPGHOME');
        return $gnupgHome ? ['homedir' => $gnupgHome] : [];
    }
}
