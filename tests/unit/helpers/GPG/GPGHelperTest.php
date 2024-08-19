<?php

namespace Yii2SecurityTxtTests\unit\helpers\GPG;


use Crypt_GPG;
use Crypt_GPG_Signature;
use rhertogh\Yii2SecurityTxt\helpers\GPG\enums\GPGDriver;
use rhertogh\Yii2SecurityTxt\helpers\GPG\GPGHelper;
use Yii2SecurityTxtTests\unit\TestCase;

/**
 * @covers rhertogh\Yii2SecurityTxt\helpers\GPG\GPGHelper
 * @covers rhertogh\Yii2SecurityTxt\helpers\GPG\traits\CryptGPGTrait
 * @covers rhertogh\Yii2SecurityTxt\helpers\GPG\traits\GnupgExtensionTrait
 */
class GPGHelperTest extends TestCase
{
    /**
     * @dataProvider signVerifyProvider
     */
    public function testSignVerify($signDriver, $verifyDriver)
    {
        $privateKey = getenv('YII2_SECURITY_TXT_PGP_PRIVATE_KEY');
        $publicKey = getenv('YII2_SECURITY_TXT_PGP_PUBLIC_KEY');
        $otherPublicKey = getenv('YII2_SECURITY_TXT_PGP_OTHER_PUBLIC_KEY');

        $message = <<<'TXT'
            test message

            TXT;

        GPGHelper::$driver = $signDriver;
        $signed = GPGHelper::sign($message, $privateKey);

        GPGHelper::$driver = $verifyDriver;
        $verified = GPGHelper::verify($signed, $publicKey);
        $this->assertSame($message, $verified);
        $this->assertFalse(GPGHelper::verify($signed, $otherPublicKey));
    }

    public function signVerifyProvider(): array
    {
        return [
            'CryptGPG:CryptGPG' => [GPGDriver::CryptGPG, GPGDriver::CryptGPG],
            'CryptGPG:GnupgExtension' => [GPGDriver::CryptGPG, GPGDriver::GnupgExtension],
            'GnupgExtension:GnupgExtension' => [GPGDriver::GnupgExtension, GPGDriver::GnupgExtension],
            'GnupgExtension:CryptGPG' => [GPGDriver::GnupgExtension, GPGDriver::CryptGPG],
        ];
    }
}
