<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\web\View;

$operator = ['=' => '=', '>' => '>', '<' => '<', '>=' => '>=', '<=' => '<='];
?>
<?php

$attribute = [
    ['attribute' => 'union_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'vAlign' => 'middle', 'filter' => false, 'visible' => FALSE],
    ['attribute' => 'plant_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->plantCode, 'name');
        }, 'vAlign' => 'middle', 'filter' => false, 'visible' => FALSE],
    ['attribute' => 'mcc_plant_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->mccPlantCode, 'name');
        }, 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'bmc_code', 'label' => Yii::t('app', 'BMC Code'), 'value' => 'bmc_code', 'vAlign' => 'middle', 'visible' => FALSE, 'filter' => false],
    ['attribute' => 'bmc_ref_code', 'label' => (Yii::t('app', 'BMC Ref.Code')), 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->bmcCode, 'ref_code');
        }, 'vAlign' => 'middle'],
    ['attribute' => 'bmc_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
        }, 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'route_code', 'label' => Yii::t('app', 'Route Code'), 'visible' => FALSE, 'filter' => false],
    ['attribute' => 'route_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->routeCode, 'route_name');
        }, 'filter' => false],
    ['attribute' => 'customer_type', 'value' => 'customer_type', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->customerType, 'customer_desc');
        },],
    ['attribute' => 'customer_code', 'filter' => true],
    ['attribute' => 'customer_code', 'label' => Yii::t('app', 'Code Ex.'), 'value' => function($model) {
            return Yii::$app->general->getCustomer($model, $model->customer_type, TRUE);
        }, 'filter' => false],
    ['attribute' => 'ref_code', 'label' => Yii::t('app', 'Ref. Code'), 'value' => function($model) {
            return Yii::$app->general->getCustomer($model, $model->customer_type, false, false, TRUE);
        }],
    ['attribute' => 'customer_name', 'label' => Yii::t('app', 'Name'), 'value' => function($model) {
            return Yii::$app->general->getCustomer($model, $model->customer_type);
        }, 'filter' => true],
    [
        'attribute' => 'date_time_of_collection',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->date_time_of_collection);
        }],
    ['attribute' => 'shift_code', 'filter' => false, 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->shiftCode, 'shift');
        }
    ],
    ['attribute' => 'doc_no'],
    ['attribute' => 'sample_no', 'value' => 'sample_no', 'vAlign' => 'middle', 'filter' => true],
    ['attribute' => 'milk_type', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->milkTypeCode, 'animal_type_name');
        }
    ],
    ['attribute' => 'milk_quality_type', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->milkQualityTypeCode, 'milk_quality_type_name');
        }, 'visible' => false, 'filter' => false
    ],
    ['attribute' => 'qty', 'value' => 'qty', 'vAlign' => 'middle', 'vAlign' => 'middle', 'filter' => Html::activeTextInput($searchModel, 'qty', ['class' => 'form-control wd60']) . Html::activeDropDownList($searchModel, 'operator_qty', $operator, ['class' => 'form-control'])],
    ['attribute' => 'qty_mode',
        'filter' => FALSE,
        'value' => function ($model) {
            return isset($model->qty_mode) ? Yii::$app->dropdown->getRecords('p_ltr_kg')['data'][$model->qty_mode] : '';
        }, 'filter' => Yii::$app->dropdown->dropdownfilterStatic('p_ltr_kg', $searchModel, 'qty_mode'),],
    ['attribute' => 'converted_qty', 'value' => 'converted_qty', 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'qty_auto', 'value' => function($model) {
            return isset($model->qty_auto) ? Yii::$app->dropdown->getRecords('is_quality_auto')['data'][$model->qty_auto] : '';
        }, 'filter' => Yii::$app->dropdown->dropdownfilterStatic('is_quality_auto', $searchModel, 'qty_auto'),],
    ['attribute' => 'vehicle_no', 'visible' => FALSE],
    ['attribute' => 'cans', 'visible' => FALSE],
    ['attribute' => 'weight_datetime', 'value' => function($model) {
            return Yii::$app->controls->view_datetime($model->weight_datetime);
        }, 'filter' => false],
];

$grid_option = [
    'id' => 'tbl-weight-collection-grid',
    'attributes' => $attribute,
    'active_column' => false,
    'default_sorting' => FALSE
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
<?php
$script = "
$(document).ready(function(){
        setInterval(() => {
            console.log('test');
            $.pjax.reload({container: '#tbl-weight-collection-grid'});
        },120000);
});";
$this->registerJs($script, View::POS_END, 'weight-collection-list');
?>