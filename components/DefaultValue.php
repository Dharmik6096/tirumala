<?php

namespace app\components;

use yii;
use yii\base\Component;

class DefaultValue extends Component {

    public function getDefaults(&$model, $eiplcode = '') {
        $defaults = $this->processDefaultsArray();
        $clientCode = $eiplcode ?: Yii::$app->session->get('eiplCode');
        $modelClass = (new \ReflectionClass($model))->getShortName();
        $clientDefaults = $defaults[$clientCode][$modelClass] ?: $defaults['EIPLCOMMON'][$modelClass] ?: [];

        foreach ($clientDefaults as $attr => $val) {
            $model->$attr = $val;
        }
    }

    public static function processDefaultsArray() {
        return [
            'EIPLCOMMON' => [
                'TblDcs' => [
                // Common defaults
                ],
            ],
            'ABT' => [
                'TblDcs' => [
                    'vendor' => 'EIPL',
                    'dpu_type' => 0,
                    'machine_owned' => 2
                ],
            ],
        ];
    }

}
