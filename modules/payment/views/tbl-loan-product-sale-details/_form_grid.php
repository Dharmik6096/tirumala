<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;
use app\components\GeneralFunctions;
use kartik\grid\GridView;
use webvimark\modules\UserManagement\components\GhostHtml;
?>



<?php

$attribute = [
        ['attribute' => 'union_code', 'value' => 'unionCode.union_name', 'filter' => false, 'visible' => false],
        ['attribute' => 'dcs_code', 'value' => 'dcs_code', 'label' => Yii::t('app', 'DCS Code')],
        ['attribute' => 'dcs_name', 'value' => 'dcsCode.dcs_name', 'label' => Yii::t('app', 'DCS Name')],
        ['attribute' => 'member_code', 'value' => 'member_code', 'label' => Yii::t('app', 'Member Code')],
        ['attribute' => 'member_name', 'value' => 'memberCode.member_name', 'label' => Yii::t('app', 'Member Name')],
        ['attribute' => 'product_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->productCode, 'product_name');
        }],
        ['attribute' => 'sale_date_time',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->sale_date_time);
        }],
        ['attribute' => 'amount'],
        ['attribute' => 'entry_type'],
        ['attribute' => 'send_status',
        'filter' => Yii::$app->dropdown->dropdownfilterStatic('send_status', $searchModel, 'send_status'),
        'value' => function($model) {
            return isset(Yii::$app->dropdown->getRecords('send_status')['data'][$model->send_status]) ? Yii::$app->dropdown->getRecords('send_status')['data'][$model->send_status] : '';
        },
    ],
        ['attribute' => 'response_datetime',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->response_datetime);
        }],
        ['attribute' => 'picked_datetime',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->picked_datetime);
        }],
        ['attribute' => 'resp_desc'],
        ['attribute' => 'data_inserted_from', 'filter' => false, 'visible' => false],
        ['attribute' => 'txfarmer_id', 'filter' => false, 'visible' => false],
        ['attribute' => 'received_timestamp',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->received_timestamp);
        }],
];

$grid_option = [
    'id' => 'union-credit-limit-grid',
    'attributes' => $attribute,
    'active_column' => false,
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>