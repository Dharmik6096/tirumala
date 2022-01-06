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
        }, 'visible' => true, 'filter' => false],
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
                return Yii::$app->general->getforeignkey($model->mccFromCode, 'ref_code');
            } else if ($model->from_type == 'BMC') {
                return Yii::$app->general->getforeignkey($model->bmcFromCode, 'ref_code');
            } else if ($model->from_type == 'DCS') {
                return Yii::$app->general->getforeignkey($model->dcsFromCode, 'ref_code');
            }
        }],
        ['attribute' => 'from_name', 'label' => 'From Name', 'value' => function($model) {
            if ($model->from_type == 'MCC') {
                return Yii::$app->general->getforeignkey($model->mccFromCode, 'name');
            } else if ($model->from_type == 'BMC') {
                return Yii::$app->general->getforeignkey($model->bmcFromCode, 'bmc_name');
            } else if ($model->from_type == 'DCS') {
                return Yii::$app->general->getforeignkey($model->dcsFromCode, 'dcs_name');
            }
        }],
        ['attribute' => 'to_type', 'visible' => true, 'filter' => false],
        ['attribute' => 'to_code', 'value' => function($model) {
            if ($model->to_type == 'MCC') {
                return Yii::$app->general->getforeignkey($model->mccToCode, 'ref_code');
            } else if ($model->to_type == 'BMC') {
                return Yii::$app->general->getforeignkey($model->bmcToCode, 'ref_code');
            } else if ($model->to_type == 'DCS') {
                return Yii::$app->general->getforeignkey($model->dcsToCode, 'ref_code');
            }
        }, 'filter' => false],
        ['attribute' => 'to_name', 'label' => 'To Name', 'value' => function($model) {
            if ($model->to_type == 'MCC') {
                return Yii::$app->general->getforeignkey($model->mccToCode, 'name');
            } else if ($model->to_type == 'BMC') {
                return Yii::$app->general->getforeignkey($model->bmcToCode, 'bmc_name');
            } else if ($model->to_type == 'DCS') {
                return Yii::$app->general->getforeignkey($model->dcsToCode, 'dcs_name');
            }
        }, 'filter' => false],
        ['attribute' => 'remarks'],
];

$grid_option = [
    'id' => 'inventory-grid',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'view' => true,
//        'delete' => ['option' => 'inventory_transfer_no,inventory_transfer_code,tbl-inventory-transfer/delete'],
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
