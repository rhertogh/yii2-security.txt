<?php

namespace Yii2SecurityTxtTests\unit\module;

// phpcs:disable Generic.Files.LineLength.TooLong

use Lcobucci\Clock\FrozenClock;
use Psr\Clock\ClockInterface;
use rhertogh\Yii2SecurityTxt\controllers\web\SecurityTxtWellKnownController;
use rhertogh\Yii2SecurityTxt\SecurityTxtModule;
use Yii;
use yii\base\InvalidConfigException;
use yii\web\Controller;
use yii\web\Request;
use Yii2SecurityTxtTests\unit\TestCase;

// phpcs:enable Generic.Files.LineLength.TooLong

/**
 * @covers \rhertogh\Yii2SecurityTxt\SecurityTxtModule
 */
class SecurityTxtModuleTest extends TestCase
{
    public function testBootstrap(): void
    {
        $this->mockWebApplication();

        $result = Yii::$app->getUrlManager()->parseRequest(new Request(['url' => '.well-known/security.txt']));
        $route = $result[0];
        $this->assertSame('security.txt/well-known/security.txt', $route);
    }

    public function testBootstrapPrettyUrlDisabled(): void
    {
        $this->expectException(InvalidConfigException::class);
        $this->mockWebApplication([
            'components' => [
                'urlManager' => [
                    'enablePrettyUrl' => false,
                ],
            ],
        ]);
    }

    /**
     * @dataProvider arrayStringNullParserProvider
     */
    public function testGetParsedAcknowledgments($value, $expected): void
    {
        $this->mockWebApplication();
        $module = SecurityTxtModule::getInstance();
        $module->acknowledgments = $value;
        $result = $module->getParsedAcknowledgments();
        $this->assertSame($expected, $result);
    }

    /**
     * @dataProvider getParsedCanonicalProvider
     */
    public function testGetParsedCanonical($value, $expected): void
    {
        $this->mockWebApplication();
        $module = SecurityTxtModule::getInstance();
        $controllerConfig = $module->createController(SecurityTxtWellKnownController::CONTROLLER_NAME);
        /** @var Controller $controller */
        $controller = $controllerConfig[0];
        $controller->action = $controller->createAction(SecurityTxtWellKnownController::ACTION_NAME_SECURITY_TXT);
        Yii::$app->controller = $controller;

        $module->canonical = $value;
        $result = $module->getParsedCanonical();
        $this->assertSame($expected, $result);
    }

    public function getParsedCanonicalProvider()
    {
        return [
            'array' => [
                ['https://example.com/.well-known/security.txt', 'https://example2.com/.well-known/security.txt', ''],
                ['https://example.com/.well-known/security.txt', 'https://example2.com/.well-known/security.txt'],
            ],
            'string' => [
                'https://example.com/.well-known/security.txt',
                ['https://example.com/.well-known/security.txt'],
            ],
            'null' => [
                null,
                ['https://localhost/.well-known/security.txt']
            ],
        ];
    }

    /**
     * @dataProvider getParsedEmptyProvider
     */
    public function testGetParsedCanonicalEmpty($value): void
    {
        $this->mockWebApplication();
        $module = SecurityTxtModule::getInstance();
        $module->canonical = $value;
        $this->expectException(InvalidConfigException::class);
        $module->getParsedCanonical();
    }

    /**
     * @dataProvider arrayStringParserProvider
     */
    public function testGetParsedContact($value, $expected): void
    {
        $this->mockWebApplication();
        $module = SecurityTxtModule::getInstance();
        $module->contact = $value;
        $result = $module->getParsedContact();
        $this->assertSame($expected, $result);
    }

    public function testGetParsedContactNotSet(): void
    {
        $this->mockWebApplication();
        $module = SecurityTxtModule::getInstance();
        $this->expectException(InvalidConfigException::class);
        $module->getParsedContact();
    }

    /**
     * @dataProvider getParsedEmptyProvider
     */
    public function testGetParsedContactEmpty($value): void
    {
        $this->mockWebApplication();
        $module = SecurityTxtModule::getInstance();
        $module->contact = $value;
        $this->expectException(InvalidConfigException::class);
        $module->getParsedContact();
    }

    /**
     * @dataProvider arrayStringNullParserProvider
     */
    public function testGetParsedEncryption($value, $expected): void
    {
        $this->mockWebApplication();
        $module = SecurityTxtModule::getInstance();
        $module->encryption = $value;
        $result = $module->getParsedEncryption();
        $this->assertSame($expected, $result);
    }

    /**
     * @dataProvider getParsedExpiresProvider
     */
    public function testGetParsedExpires($value, $expected): void
    {
        $this->mockWebApplication();
        Yii::$container->set(ClockInterface::class, (new FrozenClock(new \DateTimeImmutable('2020-01-01 01:02:03'))));
        $module = SecurityTxtModule::getInstance();
        $module->expires = $value;

        if (is_string($expected) && is_a($expected, \Exception::class, true)) {
            $this->expectException($expected);
        }

        $result = $module->getParsedExpires();
        $this->assertEquals($expected, $result);
    }

    public function getParsedExpiresProvider()
    {
        return [
            'empty string' => ['', InvalidConfigException::class],
            '+1 day' => ['+1 day', new \DateTimeImmutable('2020-01-02 01:02:03')],
            '+1 day midnight' => ['+1 day midnight', new \DateTimeImmutable('2020-01-02 00:00:00')],
            '+1 week' => ['+1 week midnight', new \DateTimeImmutable('2020-01-08 00:00:00')],
            '2021-02-03' => ['2021-02-03', new \DateTimeImmutable('2021-02-03 01:02:03')],
            '2021-02-03 midnight' => ['2021-02-03 midnight', new \DateTimeImmutable('2021-02-03 00:00:00')],
            '-1 day' => ['-1 day', InvalidConfigException::class],
            '1999-01-01' => ['2021-02-03', new \DateTimeImmutable('2021-02-03 01:02:03')],

            'DateInterval:P1D' => [new \DateInterval('P1D'), new \DateTimeImmutable('2020-01-02 01:02:03')],
            'DateInterval:P1W' => [new \DateInterval('P1W'), new \DateTimeImmutable('2020-01-08 01:02:03')],

            'DateTimeImmutable:2021-02-03 01:02:03' => [new \DateTimeImmutable('2021-02-03 01:02:03'), new \DateTimeImmutable('2021-02-03 01:02:03')],
            'DateTimeImmutable:2021-02-03 midnight' => [new \DateTimeImmutable('2021-02-03 midnight'), new \DateTimeImmutable('2021-02-03 00:00:00')],
            'DateTimeImmutable:1999-01-01' => ['1999-01-01', InvalidConfigException::class],
        ];
    }

    public function testGetParsedExpiresWithoutClockOverride(): void
    {
        $this->mockWebApplication();
        $module = SecurityTxtModule::getInstance();
        $result = $module->getParsedExpires();
        $this->assertEquals( new \DateTimeImmutable('+1 day midnight'), $result);
    }

    /**
     * @dataProvider arrayStringNullParserProvider
     */
    public function testGetParsedHiring($value, $expected): void
    {
        $this->mockWebApplication();
        $module = SecurityTxtModule::getInstance();
        $module->hiring = $value;
        $result = $module->getParsedHiring();
        $this->assertSame($expected, $result);
    }

    /**
     * @dataProvider arrayStringNullParserProvider
     */
    public function testGetParsedPolicy($value, $expected): void
    {
        $this->mockWebApplication();
        $module = SecurityTxtModule::getInstance();
        $module->policy = $value;
        $result = $module->getParsedPolicy();
        $this->assertSame($expected, $result);
    }

    /**
     * @dataProvider arrayStringNullParserProvider
     */
    public function testGetParsedPreferredLanguages($value, $expected): void
    {
        // Using generic arrayStringNullParserProvider, so convert the expected value to a string.
        $expected = implode(', ', $expected);

        $this->mockWebApplication();
        $module = SecurityTxtModule::getInstance();
        $module->preferredLanguages = $value;
        $result = $module->getParsedPreferredLanguages();
        $this->assertSame($expected, $result);
    }

    public function arrayStringParserProvider(): array
    {
        return [
            'array' => [['test', ''], ['test']],
            'string' => ['test', ['test']],
        ];
    }

    public function getParsedEmptyProvider(): array
    {
        return [
            'empty array' => [[], []],
            'empty string array' => [[''], []],
            'empty string' => ['', []],
        ];
    }

    public function arrayStringNullParserProvider(): array
    {
        return [
            ...$this->arrayStringParserProvider(),
            ...$this->getParsedEmptyProvider(),
            'null' => [null, []],
        ];
    }

}
