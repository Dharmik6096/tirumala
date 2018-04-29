<?php

namespace app\modules\rmrd\controllers;

use yii\web\Controller;

/**
 * Default controller for the `rmrd` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
}
