<?php

use yii\helpers\Html;
use webvimark\modules\UserManagement\components\GhostHtml;
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\web\View;
?>

<?php

$attribute = [
    //'vehicle_code',
        ['attribute' => 'union_code', 'value' => 'unionCode.union_name', 'visible' => true, 'filter' => false],
        ['attribute' => 'transporter_code',
        'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->transporter, 'transporter_name');
        },
        'visible' => true, 'filter' => false],
        ['attribute' => 'vendor_code', 'label' => Yii::t('app', 'Vendor Code'),
        'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->transporter, 'vendor_code');
        },
        'visible' => true],
        ['attribute' => 'billing_type_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->billingType, 'billing_type');
        }, 'vAlign' => 'middle', 'filter' => Yii::$app->dropdown->dropdownfilter('billing_type_code', $searchModel, 'billing_type_code', Yii::t('app', 'Select'))],
        ['attribute' => 'vehicle_type_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->vehicleType, 'vehicle_type_name');
        },
        'visible' => true, 'filter' => Yii::$app->dropdown->dropdownfilter('vehicle_type_code', $searchModel, 'vehicle_type_code')],
        ['attribute' => 'parsing_no'],
        ['attribute' => 'vehicle_use_type', 'value' => function($model) {
            return isset($model->vehicle_use_type) ? Yii::$app->dropdown->getRecords('vehicle_use_type')['data'][$model->vehicle_use_type] : '';
        }, 'filter' => Yii::$app->dropdown->dropdownfilterStatic('vehicle_use_type', $searchModel, 'vehicle_use_type', 'form-control', FALSE, Yii::t('app', 'Used For'))],
        ['attribute' => 'capacity_code',
        'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->capacityCode, 'value');
        },
        'visible' => true, 'filter' => false],
        ['attribute' => 'registration_no', 'visible' => false, 'filter' => false],
        ['attribute' => 'applicable_rto', 'visible' => false, 'filter' => false],
        ['attribute' => 'driver_name', 'visible' => true],
        ['attribute' => 'billing_with_capacity', 'value' => function($model) {
            return $model->billing_with_capacity == 1 ? Yii::t('app', 'Yes') : Yii::t('app', 'No');
        }, 'filter' => Yii::$app->dropdown->dropdownfilterStatic('is_single_farmer', $searchModel, 'billing_with_capacity'),],
        ['attribute' => 'driver_contact_no', 'visible' => false, 'filter' => false],
        ['attribute' => 'wef_date', 'value' => function($model) {
            return Yii::$app->controls->view_date($model->wef_date);
        }, 'visible' => false, 'filter' => false],
        ['attribute' => 'pollution_certificate', 'visible' => false, 'filter' => false],
        ['attribute' => 'expiry_date', 'visible' => false, 'filter' => false],
        ['attribute' => 'insurance', 'visible' => false, 'filter' => false],
        ['attribute' => 'rent', 'visible' => false, 'filter' => false],
        ['attribute' => 'average', 'visible' => false, 'filter' => false],
        ['attribute' => 'driving_license_number', 'visible' => false, 'filter' => false],
        ['attribute' => 'billing_method',
        'value' => function($model) {
            return Yii::$app->general->getStaticDropdownVal('billing_method', $model, 'billing_method');
        },
        'filter' => Yii::$app->dropdown->dropdownfilterStatic('billing_method', $searchModel, 'billing_method')],
];

$grid_option = [
    'id' => 'vehicle-list',
    'attributes' => $attribute,
    'active_column' => true,
    'actions' => [
        'view' => TRUE,
        'update' => true,
        'km-wise-rate' => function ($url, $model) {
            $options = ['data-name' => $model->vehicle_code, 'data-val' => $model->vehicle_code, 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Km Wise Rate', 'class' => ''];
            return GhostHtml::a('<i class="fa fa-road"></i>', ['/transporter/tbl-km-wise-rate/create', 'vehicle_code' => $model->vehicle_code, 'tr_code' => $model->transporter_code], $options);
        },
//        'mobile-oil-rate' => function ($url, $model) {
//            $options = ['data-name' => $model->vehicle_code, 'data-val' => $model->vehicle_code, 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Mobile Oil Rate', 'class' => ''];
//            return GhostHtml::a('<i class="fa fa-mobile"></i>', ['/transporter/tbl-mobile-oil-rate-master/create', 'vehicle_code' => $model->vehicle_code], $options);
//        },
//        'vehicle-billing-type' => function ($url, $model) {
//            $options = ['data-name' => $model->vehicle_code, 'data-val' => $model->vehicle_code, 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Vehicle Billing Type', 'class' => ''];
//            return GhostHtml::a('<i class="fa fa-money"></i>', ['/transporter/tbl-vehicle-billing-type/create', 'vehicle_code' => $model->vehicle_code], $options);
//        },
        'transporter-payment-head' => function ($url, $model) {
            $options = ['data-name' => $model->vehicle_code, 'data-val' => $model->vehicle_code, 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Vehicle Transporter Payment Head', 'class' => ''];
            return GhostHtml::a('<i class="fa fa-truck"></i>', ['/transporter/tbl-vehicle-transporter-head-mapping/create', 'vehicle_code' => $model->vehicle_code, 'tr_code' => $model->transporter_code], $options);
        },
//        'delete' => ['option' => 'vehicle_code,vehicle_code,tbl-vehicle-master/delete'],
        'active' => function ($url, $model) {
            $title = ($model->is_active == 1) ? Yii::t('app', 'Deactivate') : Yii::t('app', 'Activate');
            $icon = ($model->is_active == 1) ? 'fa fa-close' : 'fa fa-check';
            $url = ($model->is_active == 1) ? '/transporter/tbl-vehicle-master/vehicle-deactivate' : '/transporter/tbl-vehicle-master/vehicle-activate';
            $popupWindowMsg = 'Are you sure you want to ' . ($model->is_active == 1 ? 'Deactivate ' : 'Activate ') . $model->parsing_no;
            $options = [
                'data-toggle' => 'tooltip',
                'data-placement' => 'top',
                'data-original-title' => $title,
                'data-popup-message' => $popupWindowMsg,
                'class' => ' generalGridConfirmationPopup ',
                'data-post-url' => Url::to([$url, 'id' => $model->vehicle_code])
            ];
            return GhostHtml::a_alert('<i class="fa ' . $icon . '"></i>', [[$url, 'id' => $model->vehicle_code]], $options);
        }
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>