<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use kartik\grid\GridView;
?>

<?php

$attribute = [
    ['attribute' => 'union_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'vAlign' => 'middle', 'filter' => false, 'visible' => false],
    ['attribute' => 'plant_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->plantCode, 'name');
        }, 'vAlign' => 'middle', 'filter' => false, 'visible' => false],
    ['attribute' => 'mcc_plant_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->mccPlantCode, 'name');
        }, 'vAlign' => 'middle', 'filter' => false, 'visible' => false],
    ['attribute' => 'transporter_code',
        'label' => Yii::t('app', 'Transporter'),
        'value' => function($model) {
            return Yii::$app->general->getmultiforeignkey($model->vehicleCode, ['transporter'], 'transporter_name');
        }, 'vAlign' => 'middle', 'filter' => false, 'visible' => false],
    ['attribute' => 'bmc_code',
        'label' => Yii::t('app', 'BMC Code'),
        'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'bmc_ref_code', 'label' => (Yii::t('app', 'BMC Ref.Code')), 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->bmcCode, 'ref_code');
        }, 'vAlign' => 'middle'],
    ['attribute' => 'bmc_name',
        'label' => Yii::t('app', 'BMC Name'),
        'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
        }, 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'destination_code',],
    ['attribute' => 'destination_type', 'value' => function($model) {
            $rel = Yii::$app->general->getDestRelation($model->destination_type);
            $att = strtolower($model->destination_type) == 'bmc' ? 'bmc_name' : strtolower($model->destination_type) == 'party' ? 'party_name' : (strtolower($model->destination_type) == 'vendor' ? 'customer_name' : 'name');
            if (!empty($rel))
                return Yii::$app->general->getforeignkey($model->{$rel . 'Dest'}, $att) . '-' . strtoupper($model->destination_type);
        },],
    [
        'attribute' => 'transaction_date',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->transaction_date);
        }],
    [
        'attribute' => 'from_date',
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->from_date);
        }],
    ['attribute' => 'from_shift_code',
        'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->fromShiftCode, 'shift');
        }],
    // ['attribute' => 'qty', 'label' => 'QTY',
    //     'value' => function($model) {
    //         return Yii::$app->general->getforeignkey($model->bmcMilkDispatchTxn, 'dispatch_qty');
    //     }],
    // ['attribute' => 'fat', 'label' => 'FAT',
    //     'value' => function($model) {
    //         return Yii::$app->general->getforeignkey($model->bmcMilkDispatchTxn, 'fat');
    //     }],
    // ['attribute' => 'snf', 'label' => 'SNF',
    //     'value' => function($model) {
    //         return Yii::$app->general->getforeignkey($model->bmcMilkDispatchTxn, 'snf');
    //     }],
    // ['attribute' => 'balance_qty', 'label' => 'Balance Qty',
    //     'value' => function($model) {
    //         return Yii::$app->general->getforeignkey($model->bmcMilkDispatchTxn, 'balance_qty');
    //     }],
    // ['attribute' => 'milk_type_code', 'label' => 'Milk Type',
    //     'value' => function($model) {
    //         return Yii::$app->general->getmultiforeignkey($model->bmcMilkDispatchTxn, ['milkType'], 'animal_type_name');
    //     }],
    [
        'attribute' => 'to_date',
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->to_date);
        }],
    ['attribute' => 'to_shift_code',
        'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->toShiftCode, 'shift');
        }],
    ['attribute' => 'parsing_no',
        'label' => Yii::t('app', 'Vehicle No.'),
        'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->vehicleCode, 'parsing_no');
        }],
    ['attribute' => 'trip_code'],
    ['attribute' => 'challan_no'],
    ['attribute' => 'driver_name'],
    ['attribute' => 'driver_contact_no'],
    [
        'attribute' => 'vehicle_in_time',
        'value' => function($model) {
            return Yii::$app->controls->view_time($model->vehicle_in_time);
        }],
    [
        'attribute' => 'vehicle_out_time',
        'value' => function($model) {
            return Yii::$app->controls->view_time($model->vehicle_out_time);
        }],
    // ['attribute' => 'gross_weight'],
    // ['attribute' => 'tare_weight'],
    ['attribute' => 'remarks'],
    [
        'attribute' => 'is_last_destination',
        'value' => function($model) {
            return $model->is_last_destination == 1 ? 'Yes' : 'No';
        }, 'filter' => false],
    ['attribute' => 'bmc_code', 'label' => Yii::t('app', 'Channel'), 'value' => function($model) {
            return Yii::$app->general->getmultiforeignkey($model->bmcCode, ['channelMaster'], 'channel_desc');
        }, 'visible' => true, 'filter' => false],
];

$grid_option = [
    'id' => 'bmc-milk-dispatch-list',
    'attributes' => $attribute,
    'active_column' => FALSE,
    'actions' => [
        'view' => TRUE,
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
