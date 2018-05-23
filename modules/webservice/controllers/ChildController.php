<?php

namespace app\modules\webservice\controllers;

use Yii;
use app\models\GeneralModel;

/**
 * Default controller for the `restservices` module
 */
class ChildController extends RestController {

    protected $generalModel;
    public $replace_array = [];

    public function init() {
        parent::init();
        $this->generalModel = new GeneralModel();
    }

    public function replaceColoumn($model) {
        foreach ($this->replace_array['replace_coloumn'] as $key => $val) {
            if (array_key_exists($key, $model)) {
                $model[$val] = $model[$key];
                unset($model[$key]);
            }
        }
        return $model;
    }

    public function addColoumn($model) {
        foreach ($this->replace_array['add_coloumn'] as $key => $val) {
            $model[$key] = $val;
        }
        return $model;
    }

}
