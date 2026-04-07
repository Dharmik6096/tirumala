<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use app\modules\usermanagement\components\GhostHtml;
?>

<?php

$attribute = [
//        ['attribute' => 'union_code', 'value' => function($model) {
//            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
//        }, 'filter' => false],
//        ['attribute' => 'plant_name', 'value' => function($model) {
//            return Yii::$app->general->getforeignkey($model->plantCode, 'name');
//        }, 'visible' => false],
//        ['attribute' => 'mcc_name', 'value' => function($model) {
//            return Yii::$app->general->getforeignkey($model->mccCode, 'name');
//        }, 'visible' => false],
//        ['attribute' => 'vendor_type', 'value' => function($model) {
//            return !empty($model->vendor_type) && !empty(Yii::$app->dropdown->getRecords('requisition_type')['data'][$model->vendor_type]) ? Yii::$app->dropdown->getRecords('requisition_type')['data'][$model->vendor_type] : (!empty($model->vendor_type) ? $model->vendor_type : '');
//        }, 'vAlign' => 'middle', 'filter' => Yii::$app->dropdown->dropdownfilterStatic('requisition_type', $searchModel, 'vendor_type'),],
//        ['attribute' => 'vendor_code', 'label' => Yii::t('app', 'Code')],
//        ['attribute' => 'ref_code', 'label' => Yii::t('app', 'Ref Code'), 'value' => function($model) {
//            return $model->getEntityRefCode();
//        }, 'vAlign' => 'middle', 'filter' => false],
//        ['attribute' => 'customer_name', 'label' => Yii::t('app', 'Name'), 'value' => function($model) {
//            return $model->getEntityName();
//        }, 'vAlign' => 'middle'],
//        ['attribute' => 'status', 'value' => function($model) {
//            return !empty($model->status) && !empty(Yii::$app->dropdown->getRecords('requisition_status')['data'][$model->status]) ? Yii::$app->dropdown->getRecords('requisition_status')['data'][$model->status] : (!empty($model->status) ? $model->status : '');
//        }, 'vAlign' => 'middle', 'filter' => Yii::$app->dropdown->dropdownfilterStatic('requisition_status', $searchModel, 'status'),],
//        [
//        'attribute' => 'req_date',
//        'filterType' => GridView::FILTER_DATE,
//        'filterWidgetOptions' => [
//            'pluginOptions' => ['format' => 'dd-mm-yyyy',
//                'autoclose' => true]
//        ],
//        'value' => function($model) {
//            return Yii::$app->controls->view_date($model->req_date);
//        }],
//   ['attribute' => 'description'],
        ['label' => Yii::t('app', 'Company'), 'attribute' => 'union_code', 'value' => function($model) {
            return Yii::$app->general->getmultiforeignkey($model->productRequisitionCode, ['unionCode'], 'union_name');
        }, 'filter' => false, 'visible' => FALSE],
        ['label' => Yii::t('app', 'Plant'), 'attribute' => 'plant_name', 'value' => function($model) {
            return Yii::$app->general->getmultiforeignkey($model->productRequisitionCode, ['plantCode'], 'name');
        }, 'filter' => false, 'visible' => FALSE],
        ['label' => Yii::t('app', 'MCC'), 'attribute' => 'mcc_name', 'value' => function($model) {
            return Yii::$app->general->getmultiforeignkey($model->productRequisitionCode, ['mccCode'], 'name');
        }, 'filter' => FALSE, 'visible' => FALSE],
        ['label' => Yii::t('app', 'Vendor Code'), 'attribute' => 'vendor_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->productRequisitionCode, 'vendor_code');
        }, 'filter' => true],
        ['attribute' => 'vendor_type', 'value' => function($model) {
            return !empty(Yii::$app->general->getforeignkey($model->productRequisitionCode, 'vendor_type')) && !empty(Yii::$app->dropdown->getRecords('requisition_type')['data'][Yii::$app->general->getforeignkey($model->productRequisitionCode, 'vendor_type')]) ? Yii::$app->dropdown->getRecords('requisition_type')['data'][Yii::$app->general->getforeignkey($model->productRequisitionCode, 'vendor_type')] : (!empty(Yii::$app->general->getforeignkey($model->productRequisitionCode, 'vendor_type')) ? Yii::$app->general->getforeignkey($model->productRequisitionCode, 'vendor_type') : '');
        }, 'vAlign' => 'middle', 'filter' => false],
        ['attribute' => 'ref_code', 'label' => Yii::t('app', 'Ref Code'), 'value' => function($model) {
            return $model->getEntityRefCode();
        }, 'vAlign' => 'middle', 'filter' => false, 'visible' => FALSE],
        ['attribute' => 'ex_code', 'label' => Yii::t('app', 'Ex Code'), 'value' => function($model) {
            return $model->getEntityExCode();
        }, 'vAlign' => 'middle', 'filter' => false],
        ['attribute' => 'dispatch_center_code', 'label' => Yii::t('app', 'Dispatch Center'), 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->dispatchCenter, 'dispatch_center_name');
        }, 'vAlign' => 'middle', 'filter' => false],
        ['attribute' => 'customer_name', 'label' => Yii::t('app', 'Name'), 'value' => function($model) {
            return $model->getEntityName();
        }, 'vAlign' => 'middle', 'filter' => true],
        ['label' => 'Mobile No', 'visible' => true, 'filter' => false,
        'value' => function($model) {
            $vendor = Yii::$app->general->getforeignkey($model->productRequisitionCode, 'vendor_type');
            if ($vendor == 'BMC') {
                $detail = Yii::$app->general->getDefaultContactDetail(Yii::$app->general->getforeignkey($model->productRequisitionCode, 'bmc_code'), 'bmc');
                isset($detail->mobile_no) ? $detail = $detail->mobile_no : $detail = '';
                return $detail;
            } else if ($vendor == 'DCS') {
                $detail = Yii::$app->general->getDefaultContactDetail(Yii::$app->general->getforeignkey($model->productRequisitionCode, 'dcs_code'), 'society');
                isset($detail->mobile_no) ? $detail = $detail->mobile_no : $detail = '';
                return $detail;
            }
        }
    ],
        [
        'attribute' => 'req_date',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_datetime(Yii::$app->general->getforeignkey($model->productRequisitionCode, 'req_date'), 'php:d-m-Y');
        }
    ],
        ['label' => Yii::t('app', 'Description'), 'attribute' => 'description', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->productRequisitionCode, 'description');
        }, 'filter' => true],
        ['attribute' => 'product_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->productCode, 'product_name');
        }, 'vAlign' => 'middle'],
        ['label' => Yii::t('app', 'SAP Code'), 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->productCode, 'ref_code');
        }, 'vAlign' => 'middle'],
        ['attribute' => 'quantity', 'vAlign' => 'middle'],
        ['attribute' => 'approved_quantity', 'vAlign' => 'middle'],
        ['attribute' => 'provisional_rate', 'vAlign' => 'middle'],
        ['attribute' => 'provisional_amount', 'vAlign' => 'middle'],
        [
        'attribute' => 'uom',
        'value' => function($model) {
            return $model->getUom($model->product_code);
        },
    ],
        [
        'attribute' => 'requisition_on_date',
        'vAlign' => 'middle',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->requisition_on_date);
        }],
        [
        'attribute' => 'created_at',
        'label' => 'Order receipt date time',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_datetime($model->created_at, 'php:d-m-Y H:i:s');
        }
    ],
        [
        'attribute' => 'is_approved',
        'vAlign' => 'middle',
        'filter' => Yii::$app->dropdown->dropdownfilterStatic('is_type', $searchModel, 'is_approved'),
        'value' => function($model) {
            return ($model->is_approved == 1) ? Yii::t('app', 'Yes') : Yii::t('app', 'No');
        }
    ],
        ['attribute' => 'status', 'value' => function($model) {
            return isset($model->status) ? Yii::$app->dropdown->getRecords('requisition_status')['data'][$model->status] : '';
        }, 'vAlign' => 'middle', 'filter' => Yii::$app->dropdown->dropdownfilterStatic('requisition_status', $searchModel, 'status'),],
        ['attribute' => 'x_col2', 'vAlign' => 'middle', 'filter' => false],
];

$grid_option = [
    'id' => 'product-requisition-list',
    'attributes' => $attribute,
    'active_column' => false,
//    'actions' => [
//        'view' => true,
//        //'delete' => ['option' => 'date,product_requisition_code,tbl-product-requisition/delete'],
//        'transaction' => function ($url, $model) {
//            $class = ($model->status == 'Draft') ? '' : 'disabled';
//            $options = ['data-name' => $model->req_date, 'class' => $class, 'data-val' => $model->product_requisition_code, 'title' => Yii::t('app', 'Add Requisition Transaction')];
//            return GhostHtml::a('<i class="glyphicon glyphicon-plus"></i>', ['/product/tbl-product-requisition-transaction/create', 'id' => $model->product_requisition_code], $options);
//        },
//        'reqaccept' => function ($url, $model) {
//            // $class = $model->disableApprove()? 'disabled' : '';
//            $class = in_array($model->status, ['Rejected', 'Draft', 'Dispatched']) ? 'disabled' : '';
//            // $class = '';
//            $options = ['data-name' => $model->req_date, 'class' => $class, 'data-val' => $model->product_requisition_code, 'title' => Yii::t('app', 'Accept Requisition')];
//            return GhostHtml::a('<i class="glyphicon glyphicon-ok"></i>', ['/product/tbl-product-requisition-transaction/accept-requisition', 'id' => $model->product_requisition_code], $options);
//        },
//        'product-dispatch' => function ($url, $model) {
//            $options = ['data-val' => $model->product_requisition_code, 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Product Dispatch'];
//            return GhostHtml::a('<i class="fa fa-user-circle-o"></i>', ['/product/tbl-product-dispatch/create', 'id' => $model->product_requisition_code], $options);
//        },
//    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>