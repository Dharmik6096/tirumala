<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use app\modules\usermanagement\components\GhostHtml;
use yii\helpers\Url;

?>
<?php

$attribute = [
    ['attribute' => 'union_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'visible' => false],
    ['attribute' => 'asset_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->assetCode, 'asset_name');
        }],
    ['attribute' => 'sap_code'],
    ['attribute' => 'serial_number'],
    ['attribute' => 'manufacturer_serial_number'],
    ['attribute' => 'remain_qty', 'value' => function($model) {
            return ($model->status == 2) ? $model->qty : $model->remain_qty;
        }],
    ['attribute' => 'sloc_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->toStoreLocCode, 'sloc_code');
        }, 'filter' => true],
    ['attribute' => 'purchase_date', 'attribute' => 'purchase_date',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->assetDetail['purchase_date']);
        }],
    ['attribute' => 'put_to_use_date',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->put_to_use_date);
        }],
    ['attribute' => 'from_type', 'value' => function($model) {
            return ($model->from_type == 'VEN') ? $model->from_type : Yii::$app->general->getmultiforeignkey($model->fromStoreLocCode, ['storeLocType'], 'slt_name');
        }, 'filter' => false],
    ['attribute' => 'from_dest', 'value' => function($model) {
        // return $model->from_dest;
            return ($model->from_type == 'VEN') ? Yii::$app->general->getmultiforeignkey($model->assetDetail, ['manufacturerCode'], 'customer_name') : Yii::$app->general->getforeignkey($model->fromStoreLocCode, 'store_location_name');
        }, 'filter' => true],
    ['attribute' => 'to_type', 'value' => function($model) {
            return Yii::$app->general->getmultiforeignkey($model->toStoreLocCode, ['storeLocType'], 'slt_name');
        }, 'filter' => false],
    ['attribute' => 'to_dest', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->toStoreLocCode, 'store_location_name');
        }, 'filter' => false],
    ['attribute' => 'status', 'value' => function($model) {
            return isset($model->status) ? Yii::$app->dropdown->getRecords('asset_status')['data'][$model->status] : '';
        }, 'filter' => Yii::$app->dropdown->dropdownfilterStatic('asset_status', $searchModel, 'status')],
    ['attribute' => 'make', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->assetDetail, 'make');
        }],
    ['attribute' => 'current_status', 'value' => function($model) {
            return (isset($model['assetDetail']->current_status) && $model['assetDetail']->current_status != null) ? Yii::$app->dropdown->getRecords('asset_detail_status')['data'][$model['assetDetail']->current_status] : '';
        }, 'filter' => Yii::$app->dropdown->dropdownfilterStatic('asset_detail_status', $searchModel, 'current_status')],
    ['attribute' => 'remarks', 'filter' => false, 'visible' => false],
    ['attribute' => 'cluster_email', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->assetClusterVendorInfo, 'cluster_email');
        }, 'filter' => TRUE, 'visible' => false],
    ['attribute' => 'cluster_mobile', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->assetClusterVendorInfo, 'cluster_mobile');
        }, 'filter' => TRUE, 'visible' => false],
    ['attribute' => 'vendor_email', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->assetClusterVendorInfo, 'vendor_email');
        }, 'filter' => TRUE, 'visible' => false],
    ['attribute' => 'vendor_mobile', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->assetClusterVendorInfo, 'vendor_mobile');
        }, 'filter' => TRUE, 'visible' => false],
];

$grid_option = [
    'id' => 'asset-detail-list',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'view-detail' => function ($url, $model) {
            $url = Url::to(['tbl-asset-detail/view', 'id' => $model->asset_detail_code]);
            return GhostHtml::a('<i class="fa fa-eye"></i>', $url, ['data-bs-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'View']);
        },
        'edit' => function ($url, $model) {
            $url = Url::to(['tbl-asset-detail/update', 'id' => $model->asset_detail_code]);
            return GhostHtml::a('<i class="fa fa-pencil-alt"></i>', $url, ['data-bs-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Edit']);
        },
        'asset-detail-bom' => function ($url, $model) {
            $class = $model->assetCode['is_spare'] == 0 ? '' : 'disabled';
            $options = ['data-code' => $model->asset_detail_code, 'data-bs-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Asset Detail Bom', 'class' => $class,];
            return GhostHtml::a('<i class="fa fa-money-bill"></i>', ['/assetmanagement/tbl-asset-detail-bom/create', 'id' => $model->asset_detail_code], $options);
        },
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
