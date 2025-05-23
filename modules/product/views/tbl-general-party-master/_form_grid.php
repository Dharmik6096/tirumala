<?php

use kartik\grid\GridView;
use webvimark\modules\UserManagement\components\GhostHtml;

$attribute = [
    ['attribute' => 'union_code', 'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'vAlign' => 'middle', 'filter' => false, 'visible' => false],
    ['attribute' => 'plant_code', 'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->plantCode, 'name');
        }, 'vAlign' => 'middle', 'filter' => false, 'visible' => true],
    ['attribute' => 'mcc_plant_code', 'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->mccPlantCode, 'name');
        }, 'vAlign' => 'middle', 'filter' => false, 'visible' => true],
    ['attribute' => 'bmc_code',
        'label' => Yii::t('app', 'BMC Code'),
        'vAlign' => 'middle',
        'filter' => false
    ],
    ['attribute' => 'bmc_name',
        'label' => Yii::t('app', 'BMC Name'),
        'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
        },
        'vAlign' => 'middle',
        'filter' => false
    ],
    ['attribute' => 'party_type', 'visible' => true, 'filter' => true],
    ['attribute' => 'party_code', 'visible' => true, 'filter' => true],
    ['attribute' => 'ref_code', 'visible' => true, 'filter' => true],
    ['attribute' => 'party_name', 'visible' => true, 'filter' => true],

];


$grid_option = [
    'id' => 'general-party-master-list-grid',
    'attributes' => $attribute,
    'active_column' => false,
    'default_sorting' => FALSE,
    'actions' => [
        'view' => FALSE,
        'update' => TRUE,
        'update' => function ($url, $model) {
            $class = ($model->party_type == 'EMPLOYEE') ? '' : 'disabled';
            $options = ['data-code' => $model->general_party_master_code, 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Update', 'class' => $class,];
            return GhostHtml::a('<i class="fa fa-pencil"></i>', ['/product/tbl-general-party-master/update', 'id' => $model->general_party_master_code], $options);
        },
        'delete' => ['option' => 'general_party_master_code,general_party_master_code,tbl-general-party-master/delete'],
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
