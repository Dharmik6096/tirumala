<?php

namespace app\components;

use yii;
use yii\base\Component;

class AttributeComponent extends Component {

    public function getAttributes($clientCode, $model) {
        $defaults = $this->processClientConfigArray();
        $modelClass = (new \ReflectionClass($model))->getShortName();
        return $defaults[$clientCode][$modelClass] ?? $defaults['EIPLCOMMON'][$modelClass] ?? [];
    }

    public static function processClientConfigArray() {
        return [
            'EIPLCOMMON' => [
                'TblProductSale' => [
                    'min_date' => date('d-m-Y'),
                    'readonly' => TRUE,
                    'class' => 'no_pointer'
                ],
            ],
            'BANAS' => [
                'TblProductSale' => [
                    'min_date' => '',
                    'readonly' => false,
                    'class' => ''
                ],
            ],
        ];
    }

}
