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
        ['attribute' => 'bmc_code', 'label' => Yii::t('app', 'BMC') . ' Ref Code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->bmcCode, 'ref_code');
        }, 'filter' => TRUE],
        ['attribute' => 'bmc_name', 'label' => Yii::t('app', 'BMC'), 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
        }, 'filter' => TRUE],
        ['attribute' => 'dcs_code', 'label' => Yii::t('app', 'DCS') . ' Ref Code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->dcsCode, 'ref_code');
        }, 'filter' => TRUE],
        ['attribute' => 'dcs_name', 'label' => Yii::t('app', 'DCS'), 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name');
        }, 'filter' => TRUE],
        ['attribute' => 'member_name', 'label' => Yii::t('app', 'Member'), 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->memberCode, 'member_name');
        }, 'visible' => TRUE, 'filter' => TRUE],
        ['attribute' => 'member_code', 'label' => Yii::t('app', 'Member Code Ex'), 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->memberCode, 'ex_member_code');
        }, 'visible' => TRUE, 'filter' => TRUE],
                
        ['attribute' => 'sap_farmer_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->memberCode, 'sap_farmer_code');
        }, 'visible' => TRUE, 'filter' => TRUE],
                
        ['attribute' => 'customer_code', 'label' => Yii::t('app', 'Customer') . ' Ref Code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->customerCode, 'ref_code');
        }, 'visible' => TRUE, 'filter' => TRUE],
        ['attribute' => 'customer_name', 'label' => Yii::t('app', 'Customer'), 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->customerCode, 'customer_name');
        }, 'visible' => TRUE, 'filter' => TRUE],
        ['attribute' => 'warehouse_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->warehouseCode, 'store_location_name');
        }, 'filter' => false],
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
        ['attribute' => 'rate'],
        ['attribute' => 'amount'],
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
        ['attribute' => 'approve_qty', 'visible' => TRUE, 'filter' => TRUE],
        ['attribute' => 'dispatch_qty'],
        ['attribute' => 'dispatch_qty',
        'label' => Yii::t('app', 'Dispatch Amount'),
        'value' => function($model) {
            return (!empty($model->rate) && !empty($model->dispatch_qty)) ? $model->rate * $model->dispatch_qty : '0';
        }, 'filter' => false],
        ['attribute' => 'received_qty', 'visible' => TRUE, 'filter' => TRUE],
        ['attribute' => 'rejected_qty', 'visible' => TRUE, 'filter' => TRUE],
        ['attribute' => 'created_at', 'filter' => false, 'visible' => FALSE],
        ['attribute' => 'created_by', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->userCode, 'name');
        }, 'filter' => false, 'visible' => FALSE],
];

$grid_option = [
    'id' => 'indent-grid',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'view' => true,
//        'delete' => ['option' => 'indent_code,indent_code,tbl-indent-master/delete,checkStatus()'],
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
