<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;
use webvimark\modules\UserManagement\components\GhostHtml;
use kartik\grid\GridView;
?>

<?php

$attribute = [
        ['attribute' => 'union_code', 'label' => 'Union', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'visible' => true],
        ['attribute' => 'inventory_transfer_no'],
        ['label' => Yii::t('app', 'Inventory Transfer Date'), 'attribute' => 'inventory_transfer_date',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->inventory_transfer_date);
        }],
        ['attribute' => 'from_type', 'visible' => true],
        ['attribute' => 'from_code', 'value' => function($model) {
            if ($model->from_type == 'MCC') {
                return Yii::$app->general->getforeignkey($model->mccPlantCode, 'ref_code');
            } else if ($model->from_type == 'BMC') {
                return Yii::$app->general->getforeignkey($model->bmcCode, 'ref_code');
            } else if ($model->from_type == 'DCS') {
                return Yii::$app->general->getforeignkey($model->dcsCode, 'ref_code');
            }
        }],
        ['attribute' => 'from_code', 'label' => 'From Name', 'value' => function($model) {
            if ($model->from_type == 'MCC') {
                return Yii::$app->general->getforeignkey($model->mccPlantCode, 'name');
            } else if ($model->from_type == 'BMC') {
                return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
            } else if ($model->from_type == 'DCS') {
                return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name');
            }
        }],
        ['attribute' => 'to_type', 'visible' => true],
        ['attribute' => 'to_code', 'value' => function($model) {
            if ($model->to_type == 'MCC') {
                return Yii::$app->general->getforeignkey($model->toMccPlantCode, 'ref_code');
            } else if ($model->to_type == 'BMC') {
                return Yii::$app->general->getforeignkey($model->toBmcCode, 'ref_code');
            } else if ($model->to_type == 'DCS') {
                return Yii::$app->general->getforeignkey($model->toDcsCode, 'ref_code');
            }
        }],
        ['attribute' => 'to_code', 'label' => 'To Name', 'value' => function($model) {
            if ($model->to_type == 'MCC') {
                return Yii::$app->general->getforeignkey($model->toMccPlantCode, 'name');
            } else if ($model->to_type == 'BMC') {
                return Yii::$app->general->getforeignkey($model->toBmcCode, 'bmc_name');
            } else if ($model->to_type == 'DCS') {
                return Yii::$app->general->getforeignkey($model->toDcsCode, 'dcs_name');
            }
        }],
        ['attribute' => 'remarks'],
];

$grid_option = [
    'id' => 'inventory-grid',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'view' => true,
        'delete' => ['option' => 'inventory_transfer_no,inventory_transfer_code,tbl-inventory-transfer/delete'],
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
