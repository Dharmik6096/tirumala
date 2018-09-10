<?php

namespace app\modules\vendorapi\controllers;

use yii\rest\ActiveController;
use yii\helpers\Json;
use Yii;
use app\models\GeneralModel;

/**
 * Default controller for the `restservices` module
 */
class RestController extends ActiveController {

    public $generalModel;

    public function init() {
        $this->generalModel = new GeneralModel();
    }

    public $modelClass = 'app\modules\vendorapi\models';

    public function actions() {
        return [];
    }

    protected function verbs() {
        return [
            '*' => ['POST'],
        ];
    }

    public function response($data) {
        if (count($data) <= 0)
            return ['success' => 'No Data Found'];
        else
            return ['success' => $data];
    }

    public function request() {
        return Json::decode(Yii::$app->request->getRawBody());
    }

}
