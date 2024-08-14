<?php

namespace Yii2SecurityTxtTests\unit\controllers\_base;

use yii\web\Controller;
use Yii2SecurityTxtTests\unit\TestCase;

abstract class SecurityTxtBaseControllerTest extends TestCase
{
    /**
     * @return Controller
     */
    abstract protected function getMockController();

    /**
     * @return string[]
     */
    abstract protected function getExpectedActions();

    public function testActions()
    {
        $controller = $this->getMockController();
        $actions = $controller->actions();

        foreach ($this->getExpectedActions() as $expectedAction) {
            $this->assertArrayHasKey($expectedAction, $actions);
        }
    }
}
