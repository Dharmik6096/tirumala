<?php

use kartik\grid\GridView;

$attribute = [
    'master_transfer_code',
    ['attribute' => 'union_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'filter' => false, 'visible' => FALSE],
    ['attribute' => 'master_type', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->requestType, 'master_type_text');
        }, 'visible' => true],
    ['attribute' => 'transfer_type', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->requestType, 'transfer_type_text');
        }, 'visible' => true],
    ['attribute' => 'plant_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->plantCode, 'name');
        }, 'filter' => false],
    ['attribute' => 'old_member_code', 'label' => Yii::t('app', 'Member Code')],
//    ['attribute' => 'old_member_code', 'label' => Yii::t('app', 'Vendor Code'),
//        'value' => function($model) {
//            return Yii::$app->general->getforeignkey($model->oldMemberCode, 'vendor_code');
//        }, 'filter' => false
//    ],
    ['attribute' => 'old_member_code', 'label' => Yii::t('app', 'Member'),
        'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->oldMemberCode, 'member_name');
        }, 'filter' => false
    ],
    // ['attribute' => 'new_member_code', 'label' => Yii::t('app', 'New Member Code'), 'visible' => FALSE],
    ['attribute' => 'old_dcs_code', 'label' => Yii::t('app', 'DCS Code')],
    ['attribute' => 'old_dcs_code', 'label' => Yii::t('app', 'DCS'),
        'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->oldDcsCode, 'dcs_name');
        }, 'filter' => false],
    ['attribute' => 'new_dcs_code', 'label' => Yii::t('app', 'New DCS Code')],
    ['attribute' => 'new_dcs_code', 'label' => Yii::t('app', 'New DCS'),
        'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->newDcsCode, 'dcs_name');
        }, 'filter' => false],
    ['attribute' => 'old_bmc_code', 'label' => Yii::t('app', 'BMC Code')],
    ['attribute' => 'old_bmc_code', 'label' => Yii::t('app', 'BMC'),
        'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->oldBmcCode, 'bmc_name');
        },],
    ['attribute' => 'new_bmc_code', 'label' => Yii::t('app', 'New BMC Code')],
    ['attribute' => 'new_bmc_code', 'label' => Yii::t('app', 'New BMC'),
        'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->newBmcCode, 'bmc_name');
        },],
    ['attribute' => 'old_mcc_plant_code', 'label' => Yii::t('app', 'MCC Code'),
//        'value' => function($model) {
//            return Yii::$app->general->getforeignkey($model->oldMccPlantCode, 'sloc_code');
//        },
    ],
    ['attribute' => 'old_mcc_plant_code', 'label' => Yii::t('app', 'MCC'),
        'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->oldMccPlantCode, 'name');
        }, 'filter' => false],
    ['attribute' => 'new_mcc_plant_code', 'label' => Yii::t('app', 'New MCC Code'),
//        'value' => function($model) {
//            return Yii::$app->general->getforeignkey($model->newMccPlantCode, 'sloc_code');
//        },
    ],
    ['attribute' => 'new_mcc_plant_code', 'label' => Yii::t('app', 'New MCC'),
        'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->newMccPlantCode, 'name');
        }, 'filter' => false],
    ['attribute' => 'old_route_code', 'label' => Yii::t('app', 'Route Code')],
    ['attribute' => 'old_route_code', 'label' => Yii::t('app', 'Route'),
        'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->oldRouteCode, 'route_name');
        }, 'filter' => false],
    ['attribute' => 'new_route_code', 'label' => Yii::t('app', 'New Route Code')],
    ['attribute' => 'new_route_code', 'label' => Yii::t('app', 'New Route'),
        'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->newRouteCode, 'route_name');
        }, 'filter' => false],
    ['attribute' => 'wef_date',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
    return Yii::$app->controls->view_date($model->wef_date);
}],
    [
        'attribute' => 'status',
        'filter' => Yii::$app->dropdown->dropdownfilterStatic('file_status', $searchModel, 'status'),
        'value' => function($model) {
            return isset(Yii::$app->dropdown->getRecords('file_status')['data'][$model->status]) ? Yii::$app->dropdown->getRecords('file_status')['data'][$model->status] : '';
        }],
];

$grid_option = [
    'id' => 'transfer-request-list',
    'attributes' => $attribute,
    'active_column' => FALSE,
    'actions' => [
        'view' => true,
        'delete' => ['option' => 'master_transfer_code,master_transfer_code,tbl-master-transfer/delete,checkDelete()']
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
