<?php

namespace Yii2SecurityTxtTests\functional;

use Codeception\Util\HttpCode;
use gnupg;
use Lcobucci\Clock\FrozenClock;
use Psr\Clock\ClockInterface;
use Yii;
use Yii2SecurityTxtTests\ApiTester;
use Yii2SecurityTxtTests\functional\_base\BaseGrantCest;

/**
 * Ensure we can't access the test API without authorization
 */
class WellKnownCest
{
    public function securityTxtTest(ApiTester $I)
    {
        Yii::$container->set(ClockInterface::class, (new FrozenClock(new \DateTimeImmutable('2020-01-01 01:02:03'))));

        $I->amOnPage('.well-known/security.txt');
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->haveHttpHeader('Content-Type', 'plain/text; charset=UTF-8');
        $I->haveHttpHeader('cache-control', 'public, max-age=123');

        $response = $I->grabPageSource();

        $publicKey = getenv('YII2_SECURITY_TXT_PGP_PUBLIC_KEY');

        $gpg = new gnupg();
        $gpg->seterrormode(GNUPG_ERROR_EXCEPTION);
        $info = $gpg->import($publicKey);
        $gpg->addencryptkey($info['fingerprint']);
        $info = $gpg->verify($response,false,$plaintext);

        $I->assertSame(0, $info[0]['summary']);

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

        $I->assertSame($expected, $plaintext);
    }
}
