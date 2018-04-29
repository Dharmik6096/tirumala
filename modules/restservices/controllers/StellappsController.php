<?php

namespace app\modules\restservices\controllers;

use app\modules\restservices\controllers\RestController;
use app\modules\restservices\models\StellappsModel;

/**
 * Default controller for the `restservices` module
 */
class StellappsController extends RestController {

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionStellappsServices() {
        $model = new StellappsModel();
        return $model->manipulation($this->request());
    }

}
