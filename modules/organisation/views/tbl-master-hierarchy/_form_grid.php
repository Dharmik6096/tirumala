<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use kartik\grid\GridView;
use webvimark\modules\UserManagement\components\GhostHtml;
?>
<?php
$attribute = [
        ['attribute' => 'union_code', 'value' => 'unionCode.union_name', 'visible' => false, 'filter' => false],
        ['attribute' => 'plant_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->plantCode, 'name');
        }, 'visible' => true, 'filter' => false],
        ['attribute' => 'plant_code', 'label' => Yii::t('app', 'PLANT') . ' Ref Code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->plantCode, 'ref_code');
        }, 'visible' => true, 'filter' => FALSE],
        ['attribute' => 'mcc_plant_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->mccPlantCode, 'name');
        }, 'visible' => true, 'filter' => false],
        ['attribute' => 'mcc_plant_code', 'label' => Yii::t('app', 'MCC') . ' Ref Code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->mccPlantCode, 'ref_code');
        }, 'visible' => true, 'filter' => FALSE],
        ['attribute' => 'bmc_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
        }, 'visible' => true, 'filter' => false],
        ['attribute' => 'bmc_code', 'label' => Yii::t('app', 'BMC') . ' Ref Code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->bmcCode, 'ref_code');
        }, 'visible' => true, 'filter' => FALSE],
        ['attribute' => 'route_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->routeMapping, 'route_name');
        }, 'visible' => true, 'filter' => false],
        ['attribute' => 'route_code', 'label' => Yii::t('app', 'Route') . ' Ref Code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->routeMapping, 'ref_code');
        }, 'visible' => true, 'filter' => false],
        ['attribute' => 'dcs_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name');
        }, 'visible' => true, 'filter' => false],
        ['attribute' => 'dcs_code', 'label' => Yii::t('app', 'DCS') . ' Ref Code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->dcsCode, 'ref_code');
        }, 'visible' => true, 'filter' => false],
        ['attribute' => 'member_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->memberCode, 'member_name');
        }, 'visible' => true, 'filter' => false],
        ['attribute' => 'member_code', 'label' => Yii::t('app', 'Member') . ' Ref Code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->memberCode, 'ref_code');
        }, 'visible' => true, 'filter' => false],
        ['attribute' => 'customer_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->customerCode, 'customer_name');
        }, 'visible' => true, 'filter' => false],
        ['attribute' => 'customer_code', 'label' => Yii::t('app', 'Customer') . ' Ref Code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->customerCode, 'ref_code');
        }, 'visible' => true, 'filter' => false],
        ['attribute' => 'ref_code1', 'visible' => true],
        ['attribute' => 'ref_code2', 'visible' => true],
        ['attribute' => 'ref_code3', 'visible' => true],
        ['attribute' => 'ref_code4', 'visible' => true],
        ['attribute' => 'ref_code5', 'visible' => true],
        ['attribute' => 'wef_date',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->wef_date);
        }],
        ['attribute' => 'created_at', 'value' => function($model) {
            return Yii::$app->controls->view_date($model->created_at, 'php:d-m-Y H:i:s');
        }, 'filter' => false, 'visible' => true],
];

$grid_option = [
    'id' => 'master-hierarchy-list',
    'attributes' => $attribute,
    'active_column' => TRUE,
    'actions' => [
        'view' => TRUE,
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>