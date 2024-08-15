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
     * @dataProvider signProvider
     */
    public function testSign($GPGDriver)
    {
        GPGHelper::$driver = $GPGDriver;

        $privateKey = getenv('YII2_SECURITY_TXT_PGP_PRIVATE_KEY');
        $publicKey = getenv('YII2_SECURITY_TXT_PGP_PUBLIC_KEY');

        $message = <<<'TXT'
            test message

            TXT;

        $response = GPGHelper::sign($message, $privateKey);

        $gpg = new Crypt_GPG();
        $keyInfo = $gpg->importKey($publicKey);
        $gpg->addEncryptKey($keyInfo['fingerprint']);
        /** @var array{
         *     data: string,
         *     signatures: Crypt_GPG_Signature[],
         * } $info
         */
        $info = $gpg->decryptAndVerify($response);

        $this->assertTrue($info['signatures'][0]->isValid());
        $this->assertSame($message, $info['data']);
    }

    public function signProvider(): array
    {
        return [
            'GPGDriver:CryptGPG' => [GPGDriver::CryptGPG],
            'GPGDriver:GnupgExtension' => [GPGDriver::GnupgExtension],
        ];
    }
}
