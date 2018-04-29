<?php

namespace app\modules\restservices\controllers;

use yii\rest\ActiveController;
use yii\helpers\Json;
use Yii;

/**
 * Default controller for the `restservices` module
 */
class RestController extends ActiveController {

    public $modelClass = 'app\modules\organisation\models\TblDcs';

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
