<?php

namespace app\components;

use yii;
use yii\base\Component;

class DisableField extends Component {

    public function getDisableFields($model) {
        $defaults = $this->processDisabledFieldsArray();
        $clientCode = Yii::$app->session->get('eiplCode');
        $modelClass = (new \ReflectionClass($model))->getShortName();
        $clientDisabledFields = $defaults[$clientCode][$modelClass] ?? $defaults['EIPLCOMMON'][$modelClass] ?? [];
        $disabledFieldsHtml = '';
        foreach ($clientDisabledFields as $attr) {
            $className = 'field-' . strtolower($modelClass) . '-' . $attr;
            $disabledFieldsHtml .= "$('.$className').addClass('disabled');";
        }
        Yii::$app->view->registerJs("
            $(document).ready(function() {
                $disabledFieldsHtml
            });
        ");
    }

    public static function processDisabledFieldsArray() {
        return [
            'EIPLCOMMON' => [],
            'DODLA' => [
                'TblBmcMilkDispatchTxn' => [
                    'water' ,'protein', 'density', 'lactose', 'freezing_point', 'hsn_code', 'seal_no_bottom', 'seal_no_broken', 'dip_open', 'dip_close', 'dip_diff',
                ],
            ],
        ];
    }

}
