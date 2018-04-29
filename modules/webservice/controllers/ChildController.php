<?php

namespace app\modules\webservice\controllers;

use Yii;
use app\models\GeneralModel;

/**
 * Default controller for the `restservices` module
 */
class ChildController extends RestController {

    protected $generalModel;

    public function init() {
        parent::init();
        $this->generalModel = new GeneralModel();
    }

}
