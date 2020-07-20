<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use app\modules\globalmaster\models\TblAnimalType;
use webvimark\modules\UserManagement\components\GhostHtml;
?>

<?php

$attribute = [
    ['label' => Yii::t('app', 'Company'), 'attribute' => 'union_code', 'value' => function($model) {
            return Yii::$app->general->getmultiforeignkey($model->dcsMilkDispatch, ['unionCode'], 'union_name');
        }, 'filter' => false, 'visible' => FALSE],
    ['label' => Yii::t('app', 'Plant'), 'attribute' => 'plant_code', 'value' => function($model) {
            return Yii::$app->general->getmultiforeignkey($model->dcsMilkDispatch, ['plantCode'], 'name');
        }, 'filter' => false,],
    ['label' => Yii::t('app', 'MCC'), 'attribute' => 'mcc_plant_code', 'value' => function($model) {
            return Yii::$app->general->getmultiforeignkey($model->dcsMilkDispatch, ['mccPlantCode'], 'name');
        }, 'filter' => false],
    ['label' => Yii::t('app', 'BMC'), 'attribute' => 'bmc_code', 'value' => function($model) {
            return Yii::$app->general->getmultiforeignkey($model->dcsMilkDispatch, ['bmcCode'], 'bmc_name');
        }, 'filter' => false],
    ['label' => Yii::t('app', 'Dcs Code Ex'), 'attribute' => 'dcs_code',
        'value' => function($model) {
            return Yii::$app->general->getmultiforeignkey($model->dcsMilkDispatch, ['dcsCode'], 'dcs_code_ex');
        }, 'filter' => false],
    ['label' => Yii::t('app', 'DCS Code'),
        'attribute' => 'dcs_code',
        'value' => function($model) {
            return Yii::$app->general->getmultiforeignkey($model->dcsMilkDispatch, ['dcsCode'], 'dcs_code');
        },
        'filter' => FALSE],
    ['label' => Yii::t('app', 'DCS Name'), 'attribute' => 'dcs_code', 'value' => function($model) {
            return Yii::$app->general->getmultiforeignkey($model->dcsMilkDispatch, ['dcsCode'], 'dcs_name');
        }, 'filter' => false],
    ['label' => Yii::t('app', 'Dispatch Date'), 'attribute' => 'date_time_of_dispatch',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->dcsMilkDispatch->date_time_of_dispatch);
        }],
    ['label' => Yii::t('app', 'Shift'), 'attribute' => 'shift_code', 'value' => function($model) {
            return Yii::$app->general->getmultiforeignkey($model->dcsMilkDispatch, ['shiftCode'], 'shift');
        }, 'vAlign' => 'middle', 'filter' => Yii::$app->dropdown->dropdownfilter('shift', $searchModel, 'shift_code', Yii::t('app', 'Select'))],
    ['attribute' => 'milk_type_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->milkType, 'animal_type_name');
        }, 'filter' => false],
    ['attribute' => 'milk_quality_type_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->milkQualityType, 'milk_quality_type_name');
        }, 'filter' => false],
    ['attribute' => 'dispatch_qty', 'filter' => false],
    ['attribute' => 'avg_fat', 'filter' => false],
    ['attribute' => 'avg_snf', 'filter' => false],
    ['attribute' => 'avg_clr', 'filter' => false, 'visible' => FALSE],
    ['attribute' => 'qty_mode',
        'value' => function ($model) {
            return isset($model->qty_mode) ? Yii::$app->dropdown->getRecords('p_ltr_kg')['data'][$model->qty_mode] : '';
        }, 'filter' => false, 'visible' => FALSE],
    ['attribute' => 'total_amount', 'filter' => false],
    ['attribute' => 'nos_of_can', 'filter' => false],
//    ['attribute' => 'challan_no', 'value' => function($model) {
//        return $model->dcsMilkDispatch->challan_no;
//    }, 'filter' => false],
//    ['label' => Yii::t('app','Dest. Type'), 'attribute' => 'destination_type', 'value' => function ($model) {
//        return isset($model->dcsMilkDispatch->destination_type) ? Yii::$app->dropdown->getRecords('destination_type')['data'][$model->dcsMilkDispatch->destination_type] : '';
//    }, 'filter' => Yii::$app->dropdown->dropdownfilterStatic('destination_type', $searchModel, 'destination_type'),
//    ],
//    ['label' => Yii::t('app','Destination'),  'attribute' => 'destination_code', 'value' => function($model) {
//            $rel = Yii::$app->general->getDestRelation($model->dcsMilkDispatch->destination_type);
//            $att = $model->dcsMilkDispatch->destination_type == '0' ? 'bmc_name' : 'name';
//            if (!empty($rel))
//                return Yii::$app->general->getmultiforeignkey($model->dcsMilkDispatch,[$rel . 'Dest'], $att);
//        }, 'filter' => false],
    ['attribute' => 'dispatch_type', 'value' => function ($model) {
            return isset($model->dcsMilkDispatch->dispatch_type) ? Yii::$app->dropdown->getRecords('disp_in')['data'][$model->dcsMilkDispatch->dispatch_type] : '';
        }, 'filter' => Yii::$app->dropdown->dropdownfilterStatic('disp_in', $searchModel, 'dispatch_type'),
    ],
    ['attribute' => 'vehicle_no', 'value' => function($model) {
            return $model->dcsMilkDispatch->vehicle_no;
        }, 'filter' => false, 'visible' => FALSE],
    ['attribute' => 'vehicle_in_time', 'value' => function($model) {
            return $model->dcsMilkDispatch->vehicle_in_time;
        }, 'filter' => false, 'visible' => FALSE],
    ['attribute' => 'vehicle_out_time', 'value' => function($model) {
            return $model->dcsMilkDispatch->vehicle_out_time;
        }, 'filter' => false, 'visible' => FALSE],
    ['attribute' => 'remarks', 'value' => function($model) {
            return $model->dcsMilkDispatch->remarks;
        }, 'filter' => false, 'visible' => FALSE],
    ['attribute' => 'converted_qty', 'filter' => false, 'visible' => FALSE],
    ['attribute' => 'converted_qty_mode',
        'value' => function ($model) {
            return isset($model->converted_qty_mode) ? Yii::$app->dropdown->getRecords('p_ltr_kg')['data'][$model->converted_qty_mode] : '';
        }, 'filter' => false, 'visible' => FALSE],
    ['attribute' => 'water', 'filter' => false, 'visible' => FALSE],
];

$grid_option = [
    'id' => 'dcs-milk-dispatch-list',
    'attributes' => $attribute,
    'active_column' => FALSE,
    'default_sorting' => FALSE,
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
