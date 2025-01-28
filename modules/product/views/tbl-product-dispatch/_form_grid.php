<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use webvimark\modules\UserManagement\components\GhostHtml;
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
//        ['attribute' => 'route_code', 'value' => function($model) {
//            return Yii::$app->general->getforeignkey($model->routeCode, 'route_name');
//        }, 'visible' => true],
//        ['attribute' => 'vendor_type', 'value' => function($model) {
//            return isset($model->vendor_type) ? Yii::$app->dropdown->getRecords('requisition_type')['data'][$model->vendor_type] : '';
//        }, 'vAlign' => 'middle', 'filter' => Yii::$app->dropdown->dropdownfilterStatic('requisition_type', $searchModel, 'vendor_type'),],
//        ['attribute' => 'vendor_code', 'label' => Yii::t('app', 'Code')],
//        ['attribute' => 'customer_name', 'label' => Yii::t('app', 'Name'), 'value' => function($model) {
//            return $model->getEntityName();
//        }, 'vAlign' => 'middle'],
//        ['attribute' => 'reference_no'],
//        ['attribute' => 'challan_no'],
//        [
//        'attribute' => 'dispatch_date',
//        'filterType' => GridView::FILTER_DATE,
//        'filterWidgetOptions' => [
//            'pluginOptions' => ['format' => 'dd-mm-yyyy',
//                'autoclose' => true]
//        ],
//        'value' => function($model) {
//            return Yii::$app->controls->view_date($model->dispatch_date);
//        }],
//        ['attribute' => 'vehicle_no', 'value' => function($model) {
//            return Yii::$app->general->getforeignkey($model->vehicleCode, 'parsing_no');
//        }],
//        [
//        'attribute' => 'challan_date', 'visible' => false,
//        'filterType' => GridView::FILTER_DATE,
//        'filterWidgetOptions' => [
//            'pluginOptions' => ['format' => 'dd-mm-yyyy',
//                'autoclose' => true]
//        ],
//        'value' => function($model) {
//            return Yii::$app->controls->view_date($model->challan_date);
//        }],
//        [
//        'attribute' => 'challan_verified',
//        'vAlign' => 'middle',
//        'filter' => Yii::$app->dropdown->dropdownfilterStatic('is_type', $searchModel, 'challan_verified'),
//        'value' => function($model) {
//            return ($model->challan_verified == 1) ? Yii::t('app', 'Yes') : Yii::t('app', 'No');
//        }
//    ],
        ['label' => Yii::t('app', 'Company'), 'attribute' => 'union_code', 'value' => function($model) {
            return Yii::$app->general->getmultiforeignkey($model->productDispatchCode, ['unionCode'], 'union_name');
        }, 'filter' => false, 'visible' => FALSE],
        ['label' => Yii::t('app', 'Plant'), 'attribute' => 'plant_name', 'value' => function($model) {
            return Yii::$app->general->getmultiforeignkey($model->productDispatchCode, ['plantCode'], 'name');
        }, 'filter' => false, 'visible' => FALSE],
        ['label' => Yii::t('app', 'MCC'), 'attribute' => 'mcc_name', 'value' => function($model) {
            return Yii::$app->general->getmultiforeignkey($model->productDispatchCode, ['mccCode'], 'name');
        }, 'filter' => FALSE, 'visible' => FALSE],
        ['attribute' => 'route_code', 'value' => function($model) {
            return Yii::$app->general->getmultiforeignkey($model->productDispatchCode, ['routeCode'], 'route_name');
        }, 'visible' => true],
        ['label' => Yii::t('app', 'Code'), 'attribute' => 'vendor_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->productDispatchCode, 'vendor_code');
        }, 'visible' => true, 'filter' => true],
        ['attribute' => 'vendor_type', 'value' => function($model) {
            $vendor = Yii::$app->general->getforeignkey($model->productDispatchCode, 'vendor_type');
            return isset($vendor) ? Yii::$app->dropdown->getRecords('requisition_type')['data'][$vendor] : '';
        }, 'visible' => true, 'vAlign' => 'middle', 'filter' => false],
        ['attribute' => 'ref_code', 'label' => Yii::t('app', 'Ref Code'), 'value' => function($model) {
            return $model->getEntityRefCode();
        }, 'vAlign' => 'middle', 'filter' => false, 'visible' => FALSE],
        ['attribute' => 'ex_code', 'label' => Yii::t('app', 'Ex Code'), 'value' => function($model) {
            return $model->getEntityExCode();
        }, 'vAlign' => 'middle', 'filter' => false, 'visible' => true],
        ['attribute' => 'customer_name', 'label' => Yii::t('app', 'Name'), 'value' => function($model) {
            return $model->getEntityName();
        }, 'visible' => true, 'vAlign' => 'middle', 'filter' => true],
        ['label' => 'Mobile No', 'visible' => true, 'filter' => false,
        'value' => function($model) {
            $vendor = Yii::$app->general->getforeignkey($model->productDispatchCode, 'vendor_type');
            if ($vendor == 'BMC') {
                $detail = Yii::$app->general->getDefaultContactDetail(Yii::$app->general->getforeignkey($model->productDispatchCode, 'bmc_code'), 'bmc');
                isset($detail->mobile_no) ? $detail = $detail->mobile_no : $detail = '';
                return $detail;
            } else if ($vendor == 'DCS') {
                $detail = Yii::$app->general->getDefaultContactDetail(Yii::$app->general->getforeignkey($model->productDispatchCode, 'dcs_code'), 'society');
                isset($detail->mobile_no) ? $detail = $detail->mobile_no : $detail = '';
                return $detail;
            }
        }
    ],
        ['label' => Yii::t('app', 'Reference No'), 'attribute' => 'reference_no', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->productDispatchCode, 'reference_no');
        }, 'visible' => true, 'filter' => true],
        ['label' => Yii::t('app', 'Challan No'), 'attribute' => 'challan_no', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->productDispatchCode, 'challan_no');
        }, 'visible' => true, 'filter' => false],
        [
        'attribute' => 'dispatch_date',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date(Yii::$app->general->getforeignkey($model->productDispatchCode, 'dispatch_date'));
        }],
        ['label' => Yii::t('app', 'Vehicle No'), 'attribute' => 'vehicle_no', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->productDispatchCode, 'vehicle_no');
        }, 'visible' => true, 'filter' => false],
        [
        'attribute' => 'challan_date', 'visible' => true,
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date(Yii::$app->general->getforeignkey($model->productDispatchCode, 'challan_date'));
        }],
        [
        'attribute' => 'challan_verified',
        'vAlign' => 'middle',
        'value' => function($model) {
            return ( Yii::$app->general->getforeignkey($model->productDispatchCode, 'challan_verified') == 1) ? Yii::t('app', 'Yes') : Yii::t('app', 'No');
        }
    ],
        ['attribute' => 'product_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->productCode, 'product_name');
        }, 'vAlign' => 'middle', 'visible' => true],
        ['label' => Yii::t('app', 'SAP Code'), 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->productCode, 'ref_code');
        }, 'vAlign' => 'middle', 'visible' => true],
        [
        'attribute' => 'uom',
        'value' => function($model) {
            return $model->getUom($model->product_code);
        },
    ],
        ['attribute' => 'rate', 'visible' => true],
        ['attribute' => 'dispatch_qty', 'visible' => true],
        ['attribute' => 'discount_amount', 'visible' => true],
        ['attribute' => 'amount', 'visible' => true],
//        [
//        'attribute' => 'dispatch_date',
//        'vAlign' => 'middle',
//        'filterType' => GridView::FILTER_DATE,
//        'filterWidgetOptions' => [
//            'pluginOptions' => ['format' => 'dd-mm-yyyy',
//                'autoclose' => true]
//        ],
//        'value' => function($model) {
//            return Yii::$app->controls->view_date($model->dispatch_date);
//        }],
];

$grid_option = [
    'id' => 'product-requisition-list',
    'attributes' => $attribute,
    'active_column' => false,
//    'actions' => [
//        'view' => true,
//    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>