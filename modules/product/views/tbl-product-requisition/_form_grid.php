<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use webvimark\modules\UserManagement\components\GhostHtml;
?>

<?php

$attribute = [
        ['attribute' => 'union_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'filter' => false],
        ['attribute' => 'plant_name', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->plantCode, 'name');
        }, 'visible' => false],
        ['attribute' => 'mcc_name', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->mccCode, 'name');
        }, 'visible' => false],
//        ['attribute' => 'bmc_code', 'value' => function($model) {
//            return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
//        }, 'vAlign' => 'middle'],
    ['attribute' => 'vendor_type', 'value' => function($model) {
            return isset($model->vendor_type) ? Yii::$app->dropdown->getRecords('requisition_type')['data'][$model->vendor_type] : '';
        }, 'vAlign' => 'middle', 'filter' => Yii::$app->dropdown->dropdownfilterStatic('requisition_type', $searchModel, 'vendor_type'),],
//        ['attribute' => 'vendor_type', 'value' => function($model) {
//            return Yii::$app->general->getforeignkey($model->customerType, 'customer_desc');
//        }, 'vAlign' => 'middle'],
    ['attribute' => 'vendor_code', 'label' => Yii::t('app', 'Code')],
        ['attribute' => 'customer_name', 'label' => Yii::t('app', 'Name'), 'value' => function($model) {
            return $model->getEntityName();
//            return isset($model->vendor_type) ? Yii::$app->general->getCustomer($model, $model->vendor_type) : '';
        }, 'vAlign' => 'middle'],
        ['attribute' => 'status', 'value' => function($model) {
            return isset($model->status) ? Yii::$app->dropdown->getRecords('requisition_status')['data'][$model->status] : '';
        }, 'vAlign' => 'middle', 'filter' => Yii::$app->dropdown->dropdownfilterStatic('requisition_status', $searchModel, 'status'),],
//        [
//        'attribute' => 'status',
//        'vAlign' => 'middle',
//        'width' => '150px',
//        'filter' => Html::activeDropDownList($searchModel, 'status', $searchModel->reqStatus(), ['class' => 'form-control', 'prompt' => Yii::t('app', 'Select'), 'multiple' => true]),
//        'value' => function($model) {
//            return $model->getRequisitionStatus($model->status);
//        }],
    [
        'attribute' => 'req_date',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->req_date);
        }],
        ['attribute' => 'description'],
//        [
//        'attribute' => 'entry_type',
//        'filter' => Yii::$app->dropdown->dropdownfilterStatic('entry_type', $searchModel),
//        'value' => function($model) {
//            return Yii::$app->general->getEntryValue($model->entry_type);
//        }
//    ],
];

$grid_option = [
    'id' => 'product-requisition-list',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'view' => true,
        //'delete' => ['option' => 'date,product_requisition_code,tbl-product-requisition/delete'],
        'transaction' => function ($url, $model) {
            $class = ($model->status == 1) ? '' : 'disabled';
            $options = ['data-name' => $model->req_date, 'class' => $class, 'data-val' => $model->product_requisition_code, 'title' => Yii::t('app', 'Add Requisition Transaction')];
            return GhostHtml::a('<i class="glyphicon glyphicon-plus"></i>', ['/product/tbl-product-requisition-transaction/create', 'id' => $model->product_requisition_code], $options);
        },
        'reqaccept' => function ($url, $model) {
            // $class = $model->disableApprove()? 'disabled' : '';
            $class = ($model->status == 1) ? 'disabled' : '';
            // $class = '';
            $options = ['data-name' => $model->req_date, 'class' => $class, 'data-val' => $model->product_requisition_code, 'title' => Yii::t('app', 'Accept Requisition')];
            return GhostHtml::a('<i class="glyphicon glyphicon-ok"></i>', ['/product/tbl-product-requisition-transaction/accept-requisition', 'id' => $model->product_requisition_code], $options);
        },
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>