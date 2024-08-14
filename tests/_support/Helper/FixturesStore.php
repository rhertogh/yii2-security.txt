<?php

namespace Yii2SecurityTxtTests\Helper;

class FixturesStore extends \Codeception\Lib\Connector\Yii2\FixturesStore
{
    /**
     * @inheritdoc
     *
     * Overwritten in order to ignore default `yii\test\InitDbFixture`.
     */
    public function globalFixtures()
    {
        return []; // <- Don't return InitDbFixture as global fixture
    }
}
