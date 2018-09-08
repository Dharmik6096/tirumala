<?php

namespace app\modules\vendorapi\controllers;

use yii\web\Controller;
use app\modules\restservices\controllers\RestController;
use app\modules\vendorapi\models\VendorModel;

/**
 * Default controller for the `vendorapi` module
 */
class VendorController extends RestController {

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionVendorServices() {
        $model = new VendorModel();
        return $model->manipulation($this->request());
    }

}
