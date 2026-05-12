<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;
use app\modules\usermanagement\components\GhostHtml;
use kartik\grid\GridView;
?>

<?php

$grnWithoutStockEntry = Yii::$app->general->getUnionConfigResult(Yii::$app->session->get('Unions'), 'grn_without_stock_entry') == 1 ? TRUE : FALSE;
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
    ['attribute' => 'is_stock_posted', 'value' => function($model) {
            return Yii::$app->general->getStaticDropdownVal('boolean_value', $model, 'is_stock_posted');
        }, 'visible' => TRUE, 'filter' => false],
    ['label' => Yii::t('app', 'Created date'), 'attribute' => 'created_at',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->created_at);
        }],
    [
        'attribute' => 'created_by',
        'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->userCode, 'name');
        }, 'filter' => false],
    ['attribute' => 'data_post_status',
        'value' => function($model) {
            return isset(Yii::$app->dropdown->getRecords('data_post_status')['data'][$model->data_post_status]) ? Yii::$app->dropdown->getRecords('data_post_status')['data'][$model->data_post_status] : 'Pending';
        }, 'filter' => false, 'visible' => false],
    ['attribute' => 'response_msg', 'filter' => FALSE, 'visible' => false],
    ['attribute' => 'picked_datetime',
        'value' => function($model) {
            return Yii::$app->controls->view_datetime($model->picked_datetime, 'php:d-m-Y H:i:s');
        }, 'filter' => FALSE, 'visible' => false],
    ['attribute' => 'response_datetime',
    'value' => function($model) {
        return Yii::$app->controls->view_datetime($model->response_datetime, 'php:d-m-Y H:i:s');
    }, 'filter' => FALSE, 'visible' => false],
];
$gridId = 'inventory-grid';
$grid_option = [
    'id' => $gridId,
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'view' => true,
         'repush' => function ($url, $model) use ($grnWithoutStockEntry, $gridId) {
             if ($grnWithoutStockEntry) {
                 return Yii::$app->general->createRePushLink($url, $model, $gridId, 'inventory_transfer_code');
             }
             return '';
         },
//        'delete' => ['option' => 'inventory_transfer_no,inventory_transfer_code,tbl-inventory-transfer/delete'],
    ]
];
Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
