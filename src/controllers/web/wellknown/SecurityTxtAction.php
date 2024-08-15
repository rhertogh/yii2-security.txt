<?php

namespace rhertogh\Yii2SecurityTxt\controllers\web\wellknown;

use Exception;
use rhertogh\Yii2SecurityTxt\controllers\web\SecurityTxtWellKnownController;
use rhertogh\Yii2SecurityTxt\helpers\GPG\GPGHelper;
use Yii;
use yii\base\Action;
use yii\base\InvalidConfigException;
use yii\web\Response;

/**
 * @property SecurityTxtWellKnownController $controller
 * @since 1.0.0
 */
class SecurityTxtAction extends Action
{
    /**
     *
     * @throws InvalidConfigException
     * @throws Exception
     * @since 1.0.0
     */
    public function run()
    {
        Yii::beginProfile('Generate security.txt', __METHOD__);

        $module = $this->controller->module;

        $expires = $module->getParsedExpires();

        $fields = [
            'policy' => $module->getParsedPolicy(),
            'contact' => $module->getParsedContact(),
            'preferredLanguages' => $module->getParsedPreferredLanguages(),
            'encryption' => $module->getParsedEncryption(),
            'acknowledgments' => $module->getParsedAcknowledgments(),
            'hiring' => $module->getParsedHiring(),
            'canonical' => $module->getParsedCanonical(),
            'expires' => $expires,
        ];

        $output = '';
        if ($module->headerComment) {
            $output .= $this->generateCommentBlock($module->headerComment) . PHP_EOL;
        }

        foreach ($fields as $fieldName => $fieldValue) {
            $fieldOutput = $this->generateFieldBlock(ucfirst($fieldName), $fieldValue);
            if ($fieldOutput) {
                if (!empty($module->fieldComments[$fieldName])) {
                    $output .= $this->generateCommentBlock($module->fieldComments[$fieldName]);
                }
                $output .= $fieldOutput . PHP_EOL;
            }
        }

        if ($module->footerComment) {
            $output .= $this->generateCommentBlock($module->footerComment) . PHP_EOL;
        }

        $output = substr($output, 0, - strlen(PHP_EOL));

        if ($module->pgpPrivateKey) {
            $output = GPGHelper::sign($output, $module->pgpPrivateKey);
        }

        Yii::$app->response->format = Response::FORMAT_RAW;
        Yii::$app->response->headers->set('Content-Type', 'text/plain; charset=utf-8');

        if ($module->cacheControl) {
            if (is_int($module->cacheControl)) {
                $maxAge = $module->cacheControl;
            } else {
                $maxAge = $expires->getTimestamp() - time();
            }
            Yii::$app->response->headers->set('Cache-Control', 'public, max-age=' . $maxAge);
        }

        Yii::endProfile('Generate security.txt', __METHOD__);
        return $output;
    }

    /**
     * @since 1.0.0
     */
    protected function generateFieldBlock(string $fieldName, array|string|\DateTimeImmutable $fieldValue): string
    {
        if (is_array($fieldValue)) {
            return implode(array_map(fn($val) => $this->generateFieldBlock($fieldName, $val),$fieldValue));
        }

        if ($fieldValue instanceof \DateTimeImmutable){
            $fieldValue = $fieldValue->setTimezone(new \DateTimeZone('UTC'))->format(\DateTime::RFC3339);
        }

        if (is_string($fieldValue)) {
            if ($fieldValue) {
                return $fieldName . ': ' . $fieldValue . PHP_EOL;
            } else {
                return '';
            }
        }

        throw new InvalidConfigException('Unknown type for "' . $fieldName . '": ' . get_debug_type($fieldValue));
    }

    /**
     * @since 1.0.0
     */
    protected function generateCommentBlock(string $comment): string
    {
        return '# ' . str_replace("\n", "\n# ", $comment) . PHP_EOL;
    }
}
