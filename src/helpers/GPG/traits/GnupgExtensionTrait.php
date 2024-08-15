<?php

namespace rhertogh\Yii2SecurityTxt\helpers\GPG\traits;

use gnupg;
use Yii;
use yii\base\InvalidConfigException;

trait GnupgExtensionTrait
{
    /**
     * @throws InvalidConfigException
     */
    protected static function signViaGnupgExtension($message, $privateKey)
    {
        Yii::beginProfile('Generate PGP signature', __METHOD__);
        $gpg = new gnupg();
        $gpg->seterrormode(GNUPG_ERROR_EXCEPTION);

        $keyInfo = $gpg->import($privateKey);
        if ($keyInfo === false) {
            throw new InvalidConfigException('Unable to import private key. Debug info: '
                . var_export(static::generateGnupgDebugInfo($gpg), true));
        }
        $gpg->addsignkey($keyInfo['fingerprint']);
        $gpg->setsignmode(gnupg::SIG_MODE_CLEAR);
        $output = $gpg->sign($message);
        if ($output === false) {
            throw new InvalidConfigException('Unable to sign the message. Debug info: '
                . var_export(static::generateGnupgDebugInfo($gpg), true));
        }
        Yii::endProfile('Generate PGP signature', __METHOD__);
        return $output;
    }

    protected static function generateGnupgDebugInfo(gnupg $gpg)
    {
        $debugInfo = [
            'engineInfo' => $gpg->getengineinfo(),
            'errorInfo' => $gpg->geterrorinfo(),
        ];

        if (empty($debugInfo['engineInfo']['home_dir'])) {
            $debugInfo['hint'] = 'The gnupg home directory is not set, it can be set via the GNUPGHOME environment variable.';
        } elseif (!is_writable($debugInfo['engineInfo']['home_dir'])) {
            $debugInfo['hint'] = 'The gnupg home directory (' . $debugInfo['engineInfo']['home_dir'] . ') might not be writable. '
                . 'Hint: can be changed via the GNUPGHOME environment variable.';
        }

        return $debugInfo;
    }
}
