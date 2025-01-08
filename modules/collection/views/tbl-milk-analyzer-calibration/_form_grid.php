<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;
use kartik\grid\GridView;

$operator = ['=' => '=', '>' => '>', '<' => '<', '>=' => '>=', '<=' => '<='];

$attribute = [
    ['attribute' => 'union_code', 'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'vAlign' => 'middle', 'filter' => false, 'visible' => FALSE],
    ['attribute' => 'plant_code', 'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->plantCode, 'name');
        }, 'vAlign' => 'middle', 'filter' => false, 'visible' => FALSE],
    ['attribute' => 'mcc_plant_code', 'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->mccPlantCode, 'name');
        }, 'vAlign' => 'middle', 'filter' => false, 'visible' => FALSE],
    ['attribute' => 'bmc_code', 'label' => Yii::t('app', 'BMC Code'), 'value' => 'bmc_code', 'vAlign' => 'middle', 'visible' => FALSE, 'filter' => false],
    ['attribute' => 'bmc_ref_code', 'label' => Yii::t('app', 'BMC') . ' ' . Yii::t('app', 'Ref Code'), 'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->bmcCode, 'ref_code');
        }, 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'bmc_code', 'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
        }, 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'dcs_code', 'label' => Yii::t('app', 'DCS Code'), 'value' => 'dcs_code', 'vAlign' => 'middle', 'visible' => FALSE, 'filter' => false],
    ['attribute' => 'dcs_ref_code', 'label' => Yii::t('app', 'DCS') . ' ' . Yii::t('app', 'Ref Code'), 'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->dcsCode, 'ref_code');
        }, 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'dcs_code', 'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name');
        }, 'vAlign' => 'middle', 'filter' => false],
    [
        'attribute' => 'date_time_of_collection',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => [
                'format' => 'dd-mm-yyyy',
                'autoclose' => true
            ]
        ],
        'value' => function ($model) {
            return Yii::$app->controls->view_date($model->date_time_of_collection);
        }
    ],
    [
        'attribute' => 'shift_code',
        'filter' => false,
        'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->shiftCode, 'shift');
        }
    ],
    [
        'attribute' => 'milk_type_code',
        'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->milkTypeCode, 'animal_type_name');
        }
    ],
    ['attribute' => 'manual_fat', 'value' => 'manual_fat', 'vAlign' => 'middle', 'vAlign' => 'middle', 'filter' => Html::activeTextInput($searchModel, 'manual_fat', ['class' => 'form-control wd60']) . Html::activeDropDownList($searchModel, 'manual_fat_operator', $operator, ['class' => 'form-control'])],
    ['attribute' => 'manual_snf', 'value' => 'manual_snf', 'vAlign' => 'middle', 'vAlign' => 'middle', 'filter' => Html::activeTextInput($searchModel, 'manual_snf', ['class' => 'form-control wd60']) . Html::activeDropDownList($searchModel, 'manual_snf_operator', $operator, ['class' => 'form-control'])],
    ['attribute' => 'actual_fat', 'value' => 'actual_fat', 'vAlign' => 'middle', 'vAlign' => 'middle', 'filter' => Html::activeTextInput($searchModel, 'actual_fat', ['class' => 'form-control wd60']) . Html::activeDropDownList($searchModel, 'actual_fat_operator', $operator, ['class' => 'form-control'])],
    ['attribute' => 'actual_snf', 'value' => 'actual_snf', 'vAlign' => 'middle', 'vAlign' => 'middle', 'filter' => Html::activeTextInput($searchModel, 'actual_snf', ['class' => 'form-control wd60']) . Html::activeDropDownList($searchModel, 'actual_snf_operator', $operator, ['class' => 'form-control'])],
    ['attribute' => 'remarks', 'filter' => false],
];

$grid_option = [
    'id' => 'tbl-milk-analyzer-calibration-grid',
    'attributes' => $attribute,
    'active_column' => false,
    'default_sorting' => FALSE
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
