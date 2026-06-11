<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use webvimark\modules\UserManagement\components\GhostHtml;

$attribute = [
    ['attribute' => 'union_code', 'value' => function($model) {
        return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
    }, 'vAlign' => 'middle', 'filter' => false, 'visible' => false],
    ['attribute' => 'plant_code', 'value' => function($model) {
        return Yii::$app->general->getforeignkey($model->plantCode, 'name');
    }, 'vAlign' => 'middle', 'filter' => false, 'visible' => false],
    ['attribute' => 'mcc_plant_code', 'value' => function($model) {
        return Yii::$app->general->getforeignkey($model->mccPlantCode, 'name');
    }, 'vAlign' => 'middle', 'filter' => false, 'visible' => false],
    ['attribute' => 'bmc_code', 'value' => function($model) {
        return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
    }, 'vAlign' => 'middle', 'filter' => false, 'visible' => false],
    ['attribute' => 'dcs_code',
        'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name');
        }, 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'narration', 'vAlign' => 'middle'],
    ['attribute' => 'narration_local', 'vAlign' => 'middle'],
    ['attribute' => 'narration_type_code', 'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->narrationTypeCode, 'narration_type');
        },
        'vAlign' => 'middle',
    ]
];

$grid_option = [
    'id' => 'narration-grid',
    'attributes' => $attribute,
    'active_column' => true,
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
