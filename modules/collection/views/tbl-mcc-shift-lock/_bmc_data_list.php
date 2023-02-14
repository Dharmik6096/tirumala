<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;

$attribute = [
        ['attribute' => 'bmc_name', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
        }, 'vAlign' => 'middle', 'filter' => false],
        ['label' => Yii::t('app', 'Soc. Code'), 'visible' => TRUE, 'attribute' => 'dcs_code', 'filter' => false],
        ['label' => Yii::t('app', 'Old Soc. Code'), 'attribute' => 'dcs_code',
        'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_code_ex');
        }, 'filter' => false],
        ['label' => Yii::t('app', 'Ref. Code'), 'attribute' => 'ref_code',
        'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->dcsCode, 'ref_code');
        },],
        ['attribute' => 'dcs_code', 'label' => Yii::t('app', 'Society Name'),
        'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name');
        }, 'filter' => false],
        [
        'attribute' => 'date_time_of_collection', 'value' => function($model) {
            return Yii::$app->controls->view_date($model->date_time_of_collection);
        }, 'vAlign' => 'middle', 'filter' => false],
        ['attribute' => 'shift_code', 'value' => 'shiftCode.shift', 'vAlign' => 'middle', 'filter' => false],
        ['attribute' => 'qty', 'value' => 'qty', 'vAlign' => 'middle', 'filter' => false],
        ['attribute' => 'fat', 'value' => 'fat', 'vAlign' => 'middle', 'filter' => false],
        ['attribute' => 'snf', 'value' => 'snf', 'vAlign' => 'middle', 'filter' => false],
        ['attribute' => 'amount', 'value' => 'amount', 'vAlign' => 'middle', 'filter' => false],
        ['attribute' => 'sample_no', 'value' => 'sample_no', 'vAlign' => 'middle', 'filter' => false],
        ['attribute' => 'milk_type_code', 'value' => function($model) {
            return isset($model->milkType) ? $model->milkType->animal_type_name : '';
        }, 'vAlign' => 'middle', 'filter' => false],
        ['attribute' => 'milk_quality_type_code', 'value' => function($model) {
            return isset($model->milkQualityType) ? $model->milkQualityType->milk_quality_type_name : '';
        }, 'vAlign' => 'middle', 'filter' => false],
];

$grid_option = [
    'id' => 'bmc-collection',
    'attributes' => $attribute,
    'active_column' => false,
];

Yii::$app->grid->bind($bmcdataProvider, $bmcsearchModel, $grid_option);
?>
