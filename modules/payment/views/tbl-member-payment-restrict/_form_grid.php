<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;
use kartik\grid\GridView;

?>
<?php

$attribute = [
        ['attribute' => 'union_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'vAlign' => 'middle', 'filter' => false, 'visible' => false],
        ['attribute' => 'plant_code', 'label' => Yii::t('app', 'Plant Code'), 'visible' => false, 'filter' => false, 'vAlign' => 'middle'],
        ['attribute' => 'plant_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->plantCode, 'name');
        }, 'vAlign' => 'middle', 'filter' => false, 'visible' => false],
        ['attribute' => 'mcc_plant_code', 'visible' => false, 'label' => Yii::t('app', 'MCC Code'), 'vAlign' => 'middle', 'filter' => false],
        ['attribute' => 'mcc_plant_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->mccPlantCode, 'name');
        }, 'vAlign' => 'middle', 'filter' => false],
        ['attribute' => 'bmc_code', 'label' => Yii::t('app', 'BMC Code'), 'vAlign' => 'middle'],
        ['attribute' => 'bmc_name', 'label' => Yii::t('app', 'BMC'), 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
        }, 'vAlign' => 'middle'],
        ['attribute' => 'dcs_code', 'label' => Yii::t('app', 'DCS Code'), 'vAlign' => 'middle'],
        ['attribute' => 'ex_code', 'label' => Yii::t('app', 'Code Ex.'), 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_code_ex');
        }, 'vAlign' => 'middle'],
        ['attribute' => 'dcs_name', 'label' => Yii::t('app', 'DCS'), 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name');
        }, 'vAlign' => 'middle'],
        [
        'attribute' => 'wef_date',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->wef_date);
        }],
];

$grid_option = [
    'id' => 'member-payment-restrict-grid',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'delete' => ['option' => 'member_payment_restrict_code,member_payment_restrict_code,tbl-member-payment-restrict/delete'],
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>