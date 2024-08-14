<?php

namespace Yii2SecurityTxtTests\unit\controllers\web\wellknown;


use gnupg;
use Lcobucci\Clock\FrozenClock;
use Psr\Clock\ClockInterface;
use rhertogh\Yii2SecurityTxt\controllers\web\SecurityTxtWellKnownController;
use rhertogh\Yii2SecurityTxt\controllers\web\wellknown\SecurityTxtAction;
use rhertogh\Yii2SecurityTxt\SecurityTxtModule;
use Yii;
use Yii2SecurityTxtTests\unit\TestCase;

/**
 * @covers rhertogh\Yii2SecurityTxt\controllers\web\wellknown\SecurityTxtAction
 */
class SecurityTxtActionTest extends TestCase
{
    protected function getMockController()
    {
        return new SecurityTxtWellKnownController('wellknown', SecurityTxtModule::getInstance());
    }

    public function testRunMinimal()
    {
        Yii::$container->set(ClockInterface::class, (new FrozenClock(new \DateTimeImmutable('2020-01-01 01:02:03'))));

        $this->mockWebApplication([
            'modules' => [
                'security.txt' => [
                    'contact' => 'security@example.com',
                ],
            ],
        ]);

        Yii::$app->controller = $this->getMockController();
        $action = new SecurityTxtAction('security.txt', Yii::$app->controller);
        $response = $action->run();

        $expected = <<<'TXT'
            Contact: security@example.com

            Canonical: https://localhost/security.txt/wellknown

            Expires: 2020-01-02T00:00:00+00:00

            TXT;

        $this->assertSame($expected, $response);

    }

    public function testRunFull()
    {
        Yii::$container->set(ClockInterface::class, (new FrozenClock(new \DateTimeImmutable('2020-01-01 01:02:03'))));

        $this->mockWebApplication([
            'modules' => [
                'security.txt' => [
                    'contact' => [
                        'admin@example.com',
                        'https://example.com/report',
                    ],
                    'policy' => 'https://example.com/report-policy',
                    'preferredLanguages' => ['en', 'es'],
                    'acknowledgments' => 'https://example.com/security-acknowledgments',
                    'canonical' => [
                        'https://example.com/.well-known/security.txt',
                    ],
                    'encryption' => 'dns:5d2d37ab76d47d36._openpgpkey.example.com?type=OPENPGPKEY',
                    'expires' => '+1 year midnight',
                    'hiring' => 'https://example.com/jobs',

                    'headerComment' => <<<TXT
                        This is a header comment.
                        It's included at the beginning of the security.txt file.
                        TXT,
                    'footerComment' => <<<TXT
                        This is a footer comment.
                        It's included at the end of the security.txt file.
                        TXT,
                    'fieldComments' => [
                        'acknowledgments' => 'Hall of Fame',
                        'hiring' => 'Want to join our awesome team?',
                    ],

                    'pgpPrivateKey' => getenv('YII2_SECURITY_TXT_PGP_PRIVATE_KEY'),
                ],
            ],
        ]);

        Yii::$app->controller = $this->getMockController();
        $action = new SecurityTxtAction('security.txt', Yii::$app->controller);
        $response = $action->run();

        $publicKey = getenv('YII2_SECURITY_TXT_PGP_PUBLIC_KEY');

        $gpg = new gnupg();
        $gpg->seterrormode(GNUPG_ERROR_EXCEPTION);
        $info = $gpg->import($publicKey);
        $gpg->addencryptkey($info['fingerprint']);
        $info = $gpg->verify($response,false,$plaintext);

        $this->assertEquals(0, $info[0]['summary']);

        $expected = <<<'TXT'
            # This is a header comment.
            # It's included at the beginning of the security.txt file.

            Policy: https://example.com/report-policy

            Contact: admin@example.com
            Contact: https://example.com/report

            PreferredLanguages: en, es

            Encryption: dns:5d2d37ab76d47d36._openpgpkey.example.com?type=OPENPGPKEY

            # Hall of Fame
            Acknowledgments: https://example.com/security-acknowledgments

            # Want to join our awesome team?
            Hiring: https://example.com/jobs

            Canonical: https://example.com/.well-known/security.txt

            Expires: 2021-01-01T00:00:00+00:00

            # This is a footer comment.
            # It's included at the end of the security.txt file.

            TXT;

        $this->assertSame($expected, $plaintext);

    }
}
