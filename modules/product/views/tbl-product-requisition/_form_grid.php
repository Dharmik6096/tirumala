<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use app\modules\usermanagement\components\GhostHtml;
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
        ['attribute' => 'vendor_type', 'value' => function($model) {
            return !empty($model->vendor_type) && !empty(Yii::$app->dropdown->getRecords('requisition_type')['data'][$model->vendor_type]) ? Yii::$app->dropdown->getRecords('requisition_type')['data'][$model->vendor_type] : (!empty($model->vendor_type) ? $model->vendor_type : '');
        }, 'vAlign' => 'middle', 'filter' => Yii::$app->dropdown->dropdownfilterStatic('requisition_type', $searchModel, 'vendor_type'),],
        ['attribute' => 'vendor_code', 'label' => Yii::t('app', 'Code')],
        ['attribute' => 'customer_name', 'label' => Yii::t('app', 'Name'), 'value' => function($model) {
            return $model->getEntityName();
        }, 'vAlign' => 'middle'],
        ['attribute' => 'status', 'value' => function($model) {
            return !empty($model->status) && !empty(Yii::$app->dropdown->getRecords('requisition_status')['data'][$model->status]) ? Yii::$app->dropdown->getRecords('requisition_status')['data'][$model->status] : (!empty($model->status) ? $model->status : '');
        }, 'vAlign' => 'middle', 'filter' => Yii::$app->dropdown->dropdownfilterStatic('requisition_status', $searchModel, 'status'),],
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
];

$grid_option = [
    'id' => 'product-requisition-list',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'view' => true,
        //'delete' => ['option' => 'date,product_requisition_code,tbl-product-requisition/delete'],
        'transaction' => function ($url, $model) {
            $class = ($model->status == 'Draft') ? '' : 'disabled';
            $options = ['data-name' => $model->req_date, 'class' => $class, 'data-val' => $model->product_requisition_code, 'title' => Yii::t('app', 'Add Requisition Transaction')];
            return GhostHtml::a('<i class="fas fa-plus"></i>', ['/product/tbl-product-requisition-transaction/create', 'id' => $model->product_requisition_code], $options);
        },
        'reqaccept' => function ($url, $model) {
            // $class = $model->disableApprove()? 'disabled' : '';
            $class = in_array($model->status, ['Rejected', 'Draft', 'Dispatched']) ? 'disabled' : '';
            // $class = '';
            $options = ['data-name' => $model->req_date, 'class' => $class, 'data-val' => $model->product_requisition_code, 'title' => Yii::t('app', 'Accept Requisition')];
            return GhostHtml::a('<i class="glyphicon glyphicon-ok"></i>', ['/product/tbl-product-requisition-transaction/accept-requisition', 'id' => $model->product_requisition_code], $options);
        },
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>