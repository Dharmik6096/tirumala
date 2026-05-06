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
            $disabledFieldsHtml .= "$('.$className').addClass('disabled no_pointer');";
        }
        Yii::$app->view->registerJs("
            $(document).ready(function() {
                $disabledFieldsHtml
            });
        ");
    }

    public static function processDisabledFieldsArray() {
        return [
            'EIPLCOMMON' => [
                'TblProductSale' => [
                    'invoice_date'
                ],
                'TblPlantDispatchTxn' => [
                    'product_mrp', 'distributor_landing_rate', 'sachiv_price', 'member_price'
                ],
            ],
            'DODLA' => [
                'TblBmcMilkDispatchTxn' => [
                    'water', 'protein', 'density', 'lactose', 'freezing_point', 'hsn_code', 'dip_open', 'dip_close', 'dip_diff',
                ],
            ],
            'BANAS' => [
                'TblProductSale' => [],
                'TblPlantDispatchTxn' => [],
            ],
            'CARGILL' => [
                'TblProductSale' => [],
            ],
            'KOTMALE' => [
                'TblProductSale' => [],
            ],
        ];
    }

}
