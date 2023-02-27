<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;
use webvimark\modules\UserManagement\components\GhostHtml;
use kartik\grid\GridView;
?>

<?php

$attribute = [
    ['attribute' => 'union_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'visible' => FALSE, 'filter' => false],
    ['attribute' => 'plant_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->plantCode, 'name');
        }, 'visible' => FALSE, 'filter' => false],
    ['attribute' => 'mcc_plant_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->mccPlantCode, 'name');
        }, 'visible' => FALSE, 'filter' => false],
    ['attribute' => 'bmc_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
        }, 'filter' => FALSE],
    ['attribute' => 'dcs_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name');
        }, 'filter' => false],
    ['label' => Yii::t('app', 'Member Code'), 'attribute' => 'member_code', 'value' => function($model) {
            return substr($model->member_code, -4);
        }, 'visible' => TRUE, 'filter' => false],
    ['attribute' => 'member_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->memberCode, 'member_name');
        }, 'filter' => TRUE],
    ['label' => Yii::t('app', 'Indent Date'), 'attribute' => 'indent_date',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->indent_date);
        }],
    ['attribute' => 'product_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->productCode, 'product_name');
        }, 'filter' => true],
    ['attribute' => 'qty'],
    ['attribute' => 'status', 'value' => function ($model) {
            return Yii::$app->general->getStaticDropdownVal('indent_approval_status', $model, 'status');
        }, 'filter' => Yii::$app->dropdown->dropdownfilterStatic('indent_approval_status', $searchModel, 'status'),],
    ['label' => Yii::t('app', 'Status Date'), 'attribute' => 'status_date',
        'value' => function($model) {
            return Yii::$app->controls->view_datetime($model->status_date);
        }, 'filter' => false],
    [
        'attribute' => 'status_by',
        'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->statusBy, 'name');
        }, 'filter' => false],
];

$grid_option = [
    'id' => 'indent-grid',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'view' => true,
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
