<?php

namespace Yii2SecurityTxtTests\unit\controllers\web;

use rhertogh\Yii2SecurityTxt\controllers\web\SecurityTxtWellKnownController;
use rhertogh\Yii2SecurityTxt\SecurityTxtModule;
use Yii2SecurityTxtTests\unit\controllers\_base\SecurityTxtBaseControllerTest;

/**
 * @covers rhertogh\Yii2SecurityTxt\controllers\web\SecurityTxtWellKnownController
 */
class SecurityTxtWellKnownControllerTest extends SecurityTxtBaseControllerTest
{
    protected function getMockController()
    {
        $this->mockWebApplication();
        return new SecurityTxtWellKnownController('well-known', SecurityTxtModule::getInstance());
    }

    /**
     * @inheritDoc
     */
    protected function getExpectedActions()
    {
        return [
            SecurityTxtWellKnownController::ACTION_NAME_SECURITY_TXT,
        ];
    }
}
