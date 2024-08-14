<?php

namespace rhertogh\Yii2SecurityTxt;

// phpcs:disable Generic.Files.LineLength.TooLong
use DateInterval;
use DateTimeImmutable;
use Exception;
use rhertogh\Yii2SecurityTxt\controllers\web\SecurityTxtWellKnownController;
use Yii;
use yii\base\BootstrapInterface;
use yii\base\InvalidConfigException;
use yii\base\Module;
use yii\helpers\Url;
use yii\web\UrlRule;

// phpcs:enable Generic.Files.LineLength.TooLong

/**
 * This is the main module class for the Yii2 SecurityTxtModule module.
 * To use it, include it as a module in the application configuration like the following:
 *
 * ~~~
 * return [
 *     'bootstrap' => ['security.txt'],
 *     'modules' => [
 *         'security.txt' => [
 *             'class' => 'rhertogh\Yii2SecurityTxt\SecurityTxtModule',
 *             // ... Please check docs/guide/start-installation.md further details
 *          ],
 *     ],
 * ]
 * ~~~
 *
 * @since 1.0.0
 */
class SecurityTxtModule extends Module implements BootstrapInterface
{
    public $controllerMap = [
        SecurityTxtWellKnownController::CONTROLLER_NAME => SecurityTxtWellKnownController::class,
    ];

    /**
     * The URL path to the security.txt endpoint.
     * If set to `null` the endpoint will be disabled.
     * Note: This path is defined in the
     *       [RFC 9116](https://www.rfc-editor.org/rfc/rfc9116.html#name-well-known-uris-registry)
     *       specification and should normally not be changed.
     * @since 1.0.0
     */
    public string|false|null $securityTxtPath = '.well-known/security.txt';

    public int|bool $cacheControl = true;

    public string|null $headerComment = null;
    public string|null $footerComment = null;

    public array $fieldComments = [];

    /**
     * Defines the "Acknowledgments" section in the security.txt
     *
     * @see https://www.rfc-editor.org/rfc/rfc9116.html#name-acknowledgments
     * @since 1.0.0
     */
    public array|string|null $acknowledgments = null;

    /**
     * Defines the "Canonical" section in the security.txt
     * If `null` (default value) the current URL will be used.
     *
     * @see https://www.rfc-editor.org/rfc/rfc9116.html#name-canonical
     * @since 1.0.0
     */
    public array|string|null $canonical = null;

    /**
     * Defines the "Contact" section in the security.txt
     *
     * @see https://www.rfc-editor.org/rfc/rfc9116.html#name-contact
     * @since 1.0.0
     */
    public array|string $contact;

    /**
     * Defines the "Encryption" section in the security.txt
     *
     * @see https://www.rfc-editor.org/rfc/rfc9116.html#name-encryption
     * @since 1.0.0
     */
    public array|string|null $encryption = null;

    /**
     * Defines the "Expires" section in the security.txt
     *
     * @see https://www.rfc-editor.org/rfc/rfc9116.html#name-expires
     * @see https://www.php.net/manual/en/datetime.formats.php
     * @since 1.0.0
     */
    public string|DateTimeImmutable|DateInterval $expires = '+1 day midnight';

    /**
     * Defines the "Hiring" section in the security.txt
     *
     * @see https://www.rfc-editor.org/rfc/rfc9116.html#name-hiring
     * @since 1.0.0
     */
    public array|string|null $hiring = null;

    /**
     * Defines the "Policy" section in the security.txt
     *
     * @see https://www.rfc-editor.org/rfc/rfc9116.html#name-policy
     * @since 1.0.0
     */
    public array|string|null $policy = null;

    /**
     * Defines the "Preferred languages" section in the security.txt
     *
     * @see https://www.rfc-editor.org/rfc/rfc9116.html#name-preferred-languages
     * @since 1.0.0
     */
    public array|string|null $preferredLanguages = null;

    public string|null $pgpPrivateKey = null;

    /**
     * @inheritdoc
     * @throws InvalidConfigException
     * @since 1.0.0
     */
    public function bootstrap($app): void
    {
        $urlManager = $app->getUrlManager();
        if (!$urlManager->enablePrettyUrl) {
            throw new InvalidConfigException('`enablePrettyUrl` must be enabled for application urlManager.');
        }

        if ($this->securityTxtPath) {
            $urlManager->addRules([
                Yii::createObject([
                    'class' => UrlRule::class,
                    'pattern' => $this->securityTxtPath,
                    'route' => $this->id
                        . '/' . SecurityTxtWellKnownController::CONTROLLER_NAME
                        . '/' . SecurityTxtWellKnownController::ACTION_NAME_SECURITY_TXT,
                ]),
            ]);
        }
    }

    /**
     * @return string[]
     * @since 1.0.0
     */
    public function getParsedAcknowledgments(): array
    {
        return $this->getCleanArray($this->acknowledgments);
    }

    /**
     * @return string[]
     * @throws InvalidConfigException
     * @since 1.0.0
     */
    public function getParsedCanonical(): array
    {
        $canonical = $this->canonical;
        if ($canonical === null) {
            $canonical = Url::canonical();
        }
        $canonical = $this->getCleanArray($canonical);
        if (empty($canonical)) {
            throw new InvalidConfigException(static::class . '::$canonical can not be empty.');
        }
        return $canonical;
    }

    /**
     * @return string[]
     * @throws InvalidConfigException
     * @since 1.0.0
     */
    public function getParsedContact(): array
    {
        if (empty($this->contact)) {
            throw new InvalidConfigException(static::class . '::$contact must be set.');
        }
        $contact = $this->getCleanArray($this->contact);
        if (empty($contact)) {
            throw new InvalidConfigException(static::class . '::$contact can not be empty.');
        }
        return $contact;
    }

    /**
     * @return string[]
     * @since 1.0.0
     */
    public function getParsedEncryption(): array
    {
        return $this->getCleanArray($this->encryption);
    }

    /**
     * @throws Exception
     * @since 1.0.0
     */
    public function getParsedExpires(): DateTimeImmutable
    {
        $expires = $this->expires;
        $now = Yii::$container->has('Psr\Clock\ClockInterface')
            ? Yii::$container->get('Psr\Clock\ClockInterface')->now()
            : new DateTimeImmutable();

        if (empty($expires)) {
            throw new InvalidConfigException(static::class . '::$expires can not be empty.');
        }
        if (is_string($expires)) {
            $expires = $now->modify($expires);
        } elseif ($expires instanceof DateInterval) {
            $expires = $now->add($expires);
        }
        if ($expires < $now) {
            throw new InvalidConfigException(static::class . '::$expires can not be in the past.');
        }
        return $expires;
    }

    /**
     * @return string[]
     * @since 1.0.0
     */
    public function getParsedHiring(): array
    {
        return $this->getCleanArray($this->hiring);
    }

    /**
     * @return string[]
     * @since 1.0.0
     */
    public function getParsedPolicy(): array
    {
        return $this->getCleanArray($this->policy);
    }

    /**
     * @since 1.0.0
     */
    public function getParsedPreferredLanguages(): string
    {
        return implode(', ', $this->getCleanArray($this->preferredLanguages));
    }

    /**
     * @since 1.0.0
     */
    protected function getCleanArray(array|string|null $value): array
    {
        if ($value === null) {
            return [];
        } elseif (is_string($value)) {
            $value = [$value];
        }
        return array_filter($value);
    }
}
