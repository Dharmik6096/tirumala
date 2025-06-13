<?php

use app\modules\globalmaster\models\TblAnimalType;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use kartik\grid\GridView;
use webvimark\modules\UserManagement\components\GhostHtml;

$operator = ['=' => '=', '>' => '>', '<' => '<', '>=' => '>=', '<=' => '<='];
$milkType = new TblAnimalType();
$milk_type = $milkType->getAnimalMilkTypeArray();
?>

<?php
$attribute = [
    ['attribute' => 'dispatch_from'],
    [
        'attribute' => 'dispatch_from_code', 
        'label' => Yii::t('app', 'Dispatch Name'),
        'value' => function ($model) {
            $relModel = $model->milkVehicleEntryCode;
            $rel = Yii::$app->general->getDestRelation($model->dispatch_from);
            $att = strtolower($model->dispatch_from) == 'bmc' ? 'bmc_name' : (strtolower($model->dispatch_from) == 'vendor' ? 'customer_name' : (strtolower($model->dispatch_from) == 'party' ? 'party_name' : 'name'));
            if (!empty($rel))
                return Yii::$app->general->getforeignkey($relModel->{$rel . 'Source'}, $att) . '-' . strtoupper($model->dispatch_from_code);
        }, 'vAlign' => 'middle', 'filter' => false],
    [
        'attribute' => 'dispatch_from_code',
        'label' => (Yii::t('app', 'Dispatch SAP vendor Code')),
        'value' => function ($model) {
            $relModel = $model->milkVehicleEntryCode;
            $rel = Yii::$app->general->getDestRelation($model->dispatch_from);
            $att = 'sap_vendor_code';
            if (!empty($rel))
                return Yii::$app->general->getforeignkey($relModel->{$rel . 'Source'}, $att);
        },
        'vAlign' => 'middle',
        'filter' => true
    ],
    ['attribute' => 'receipt_at', 'label' => (Yii::t('app', 'Dest Type')),],
    [
        'attribute' => 'receipt_at_code', 
        'label' => (Yii::t('app', 'Dest Name')),
        'value' => function ($model) {
        $relModel = $model->milkVehicleEntryCode;
        $rel = Yii::$app->general->getDestRelation($model->receipt_at);
        $att = strtolower($model->receipt_at) == 'bmc' ? 'bmc_name' : (strtolower($model->receipt_at) == 'vendor' ? 'customer_name' : (strtolower($model->receipt_at) == 'party' ? 'party_name' : 'name'));
        if (!empty($rel))
            return Yii::$app->general->getforeignkey($relModel->{$rel . 'Dest'}, $att);
    }, 'vAlign' => 'middle', 'filter' => false],
    [
        'attribute' => 'receipt_at_code',
        'label' => (Yii::t('app', 'Dest SAP Vendor Code')),
        'value' => function ($model) {
            $relModel = $model->milkVehicleEntryCode;
            $rel = Yii::$app->general->getDestRelation($model->receipt_at);
            $att = 'sap_vendor_code';
            if (!empty($rel))
                return Yii::$app->general->getforeignkey($relModel->{$rel . 'Dest'}, $att);
        },
        'vAlign' => 'middle',
        'filter' => true
    ],
    [
        'attribute' => 'receipt_datetime',
        'value' => function ($model) {
            return Yii::$app->controls->view_date($model->receipt_datetime);
        }
    ],
    ['attribute' => 'trip_code'],
    [
        'attribute' => 'entry_type',
        'filter' => Yii::$app->dropdown->dropdownfilterStatic('entry_type', $searchModel, 'entry_type'),
        'value' => function ($model) {
            return $model->entry_type;
        },
    ],
    ['attribute' => 'grn_no', 'filter' => true],
    // ['attribute' => 'challan_no', 'filter' => false],
    // ['attribute' => 'milk_type_code', 'value' => function ($model) {
    //     return Yii::$app->general->getforeignkey($model->milkType, 'animal_type_name');
    // }, 'vAlign' => 'middle', 'filter' => Html::activeDropDownList($searchModel, 'milk_type_code', $milk_type, ['class' => 'form-control', 'prompt' => 'Select'])],
    // ['attribute' => 'milk_quality_type_code', 'value' => function ($model) {
    //     return Yii::$app->general->getforeignkey($model->milkQualityType, 'milk_quality_type_name');
    // }, 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'chamber_no'],
    ['attribute' => 'chamber_quantity', 'label' => Yii::t('app', 'Total Chamber Qty.'), 'filter' => Html::activeTextInput($searchModel, 'chamber_quantity', ['class' => 'form-control wd60']) . Html::activeDropDownList($searchModel, 'operator_chamber_quantity', $operator, ['class' => 'form-control'])],
    [
        'attribute' => 'fat',
        'label' => Yii::t('app', 'AVG Fat'),
        'value' => function ($model) {
            return number_format($model->fat, 2);
        },
        'filter' => Html::activeTextInput($searchModel, 'fat', ['class' => 'form-control wd60']) .
                    Html::activeDropDownList($searchModel, 'operator_fat', $operator, ['class' => 'form-control']),
    ],
    [
        'attribute' => 'snf',
        'label' => Yii::t('app', 'AVG SNF'),
        'value' => function ($model) {
            return number_format($model->snf, 2);
        },
        'filter' => Html::activeTextInput($searchModel, 'snf', ['class' => 'form-control wd60']) .
                    Html::activeDropDownList($searchModel, 'operator_snf', $operator, ['class' => 'form-control']),
    ],
    ['attribute' => 'status', 'value' => function ($model) {
        return $model->status == 2 ? 'SUCCESS' : 'ERROR';
    }, 'filter' => array(2 => 'SUCCESS', 3 => 'ERROR')],
];



$grid_option = [
    'id' => 'milk-vehicle-entry-list',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'edit' => function ($url, $model) {
            $id = $model->source_org_code . '_' . $model->source_org_type . '_' . $model->trip_code;
            $disable = ($model->status == '2') ? 'disabled' : '';
            $options = ['title' => Yii::t('app', 'Repush'), 'class' => $disable];
            return GhostHtml::a('<i class="fa fa-share-square-o"></i>', ['/clienterp/tbl-client-erp-api-log/repush', 'id' => $id], $options);
        },
        'log_view' => function ($url, $model) {
            $id = $model->source_org_code . '_' . $model->source_org_type . '_' . $model->trip_code;
            $options = ['data-val' => $model->milk_vehicle_entry_transaction_code, 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'View Logs'];
            return GhostHtml::a('<i class="fa fa-eye"></i>', ['/clienterp/tbl-client-erp-api-log/view', 'id' => $id, 'erp_process_name' => 2], $options);
        },
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
