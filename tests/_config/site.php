<?php

use yii\helpers\ArrayHelper;
use Yii2SecurityTxtTests\_helpers\NoHeadersResponse;
use Yii2SecurityTxtTests\_helpers\TestUserComponent;
use Yii2SecurityTxtTests\_helpers\TestUserModel;

return ArrayHelper::merge(require('main.php'), [

    'layout' => false,

    'bootstrap' => [
        'security.txt',
    ],

    'modules' => [
        'security.txt' => [
            'class' => rhertogh\Yii2SecurityTxt\SecurityTxtModule::class,
        ],
    ],

    'components' => [
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
        ],
        'request' => [
            'cookieValidationKey' => 'secret',
        ],
    ],

]);
