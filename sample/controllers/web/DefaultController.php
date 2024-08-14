<?php

namespace sample\controllers\web;

use yii\helpers\Html;
use yii\web\Controller;

class DefaultController extends Controller
{
    /**
     * Just a link from the root to .well-known/security.txt
     * @return string
     */
    public function actionIndex()
    {
        return 'See <a href=".well-known/security.txt">.well-known/security.txt</a>';
    }
}
