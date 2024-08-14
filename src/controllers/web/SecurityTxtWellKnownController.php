<?php

namespace rhertogh\Yii2SecurityTxt\controllers\web;

use rhertogh\Yii2SecurityTxt\controllers\web\wellknown\SecurityTxtAction;
use rhertogh\Yii2SecurityTxt\SecurityTxtModule;
use yii\web\Controller;

/**
 * @property SecurityTxtModule $module
 * @since 1.0.0
 */
class SecurityTxtWellKnownController extends Controller
{
    public const CONTROLLER_NAME = 'well-known';
    public const ACTION_NAME_SECURITY_TXT = 'security.txt';

    /**
     * @inheritDoc
     */
    public function actions()
    {
        return [
            static::ACTION_NAME_SECURITY_TXT => SecurityTxtAction::class,
        ];
    }
}
