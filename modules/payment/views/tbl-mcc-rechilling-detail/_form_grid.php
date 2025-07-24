<?php

use kartik\grid\GridView;

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
    ['attribute' => 'chiller_info_code',
        'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->bmcChillerInfo, 'owner_name');
        },
        'vAlign' => 'middle',
        'filter' => false
    ],
    ['attribute' => 'chilling_date',
        'value' => function ($model) {
            return Yii::$app->controls->view_date($model->chilling_date);
        },'filter' => false
    ],
    ['attribute' => 'shift_code',
        'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->shiftCode, 'shift');
        },'filter' => false
    ],
    ['attribute' => 'qty', 'visible' => true, 'filter' => true],
    ['attribute' => 'rate', 'visible' => true, 'filter' => true],
    ['attribute' => 'amount', 'visible' => true],
];


$grid_option = [
    'id' => 'mcc-rechilling-detail-list-grid',
    'attributes' => $attribute,
    'active_column' => false,
    'default_sorting' => FALSE,
    'actions' => [
        'view' => FALSE,
        'update' => TRUE,
        'delete' => ['option' => 'bmcChillerInfo.owner_name,mcc_rechilling_detail_code,tbl-mcc-rechilling-detail/delete'],
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>