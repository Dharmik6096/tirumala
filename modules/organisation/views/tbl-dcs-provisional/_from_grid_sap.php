<?php

use app\modules\usermanagement\components\GhostHtml;

$attribute = [
    ['attribute' => 'union_code', 'value' => function ($model) {
        return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
    }, 'filter' => FALSE, 'visible' => FALSE],
    ['attribute' => 'plant_code', 'value' => function ($model) {
        return Yii::$app->general->getforeignkey($model->plantCode, 'name');
    }, 'filter' => FALSE, 'visible' => FALSE],
    ['attribute' => 'mcc_plant_code', 'value' => function ($model) {
        return Yii::$app->general->getforeignkey($model->mccPlantCode, 'name');
    }, 'filter' => FALSE],
    ['attribute' => 'bmc_code', 'value' => function ($model) {
        return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
    }, 'filter' => FALSE],
    ['attribute' => 'route_code', 'filter' => false, 'label' => Yii::t('app', 'Route Code')],
    ['attribute' => 'route_code', 'value' => function ($model) {
        return Yii::$app->general->getforeignkey($model->routeMapping, 'route_name');
    }, 'filter' => FALSE],
    ['attribute' => 'route_code', 'value' => function ($model) {
        return Yii::$app->general->getforeignkey($model->routeMapping, 'ref_code');
    }, 'filter' => FALSE, 'label' => 'Ref - Route Code'],
    ['attribute' => 'dcs_name', 'filter' => false],
    ['attribute' => 'district_code', 'value' => function ($model) {
        return Yii::$app->general->getforeignkey($model->districtCode, 'district_name');
    }, 'filter' => FALSE],
    ['attribute' => 'state_code', 'filter' => false],
    ['attribute' => 'pincode', 'filter' => false],
    ['attribute' => 'mobile_no', 'filter' => false],
    ['attribute' => 'aadhaar_no', 'filter' => false],

    ['attribute' => 'pan_no', 'filter' => false],
    ['attribute' => 'address', 'filter' => FALSE],
    // Bank Detail
    ['attribute' => 'bank_code', 'label' => 'Bank', 'value' => function ($model) {
        return Yii::$app->general->getforeignkey($model->bankCode, 'bank_name');
    }, 'filter' => false],
    ['attribute' => 'branch_code', 'label' => 'Branch', 'value' => function ($model) {
        return Yii::$app->general->getforeignkey($model->branchCode, 'branch_name');
    }, 'filter' => false],
    ['attribute' => 'bank_account_no', 'label' => 'Bank Account No', 'filter' => false],
    ['attribute' => 'ifsc', 'label' => 'IFSC', 'filter' => false],
    ['label' => 'Employee', 'value' => function ($model) {
        return Yii::$app->general->getforeignkey($model->userCode, 'name');
    }, 'filter' => FALSE],
    ['label' => 'Start Date', 'attribute' => 'created_at', 'value' => function ($model) {
        return $model->created_at ? date('d-m-Y', strtotime($model->created_at)) : 'NA';
    }, 'filter' => false],
    [
        'attribute' => 'data_post_status',
        'value' => function ($model) {
            return isset(Yii::$app->dropdown->getRecords('send_status')['data'][$model->data_post_status]) ? Yii::$app->dropdown->getRecords('send_status')['data'][$model->data_post_status] : 'Pending';
        },
        'filter' => false,
        'visible' => false
    ],
    [
        'attribute' => 'picked_datetime',
        'value' => function ($model) {
            return Yii::$app->controls->view_datetime($model->picked_datetime, 'php:d-m-Y H:i:s');
        },
        'filter' => FALSE,
        'visible' => false
    ],
    [
        'attribute' => 'response_datetime',
        'value' => function ($model) {
            return Yii::$app->controls->view_datetime($model->response_datetime, 'php:d-m-Y H:i:s');
        },
        'filter' => FALSE,
        'visible' => false
    ],
    ['attribute' => 'resp_desc', 'filter' => FALSE, 'visible' => false],
    ['attribute' => 'response_msg', 'filter' => FALSE],
];
$gridId = 'sap-dcs-provisional-list';
$grid_option = [
    'id' => $gridId,
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'update' => function ($url, $model) {
            $url = ['/organisation/tbl-dcs-provisional/update-sap-error-data', 'id' => $model->dcs_provisional_code];
            return GhostHtml::a('<i class="fa fa-pencil"></i>', $url, ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Edit', 'class' => '', 'data-val' => $model->dcs_provisional_code, 'data-name' => $model->dcs_name]);
        }
    ]
];
Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
