<?php

use kartik\grid\GridView;
use yii\helpers\Html;

$attribute = [
    ['attribute' => 'complain_code', 'visible' => true, 'filter' => true],
    ['attribute' => 'contact_person'],
    ['attribute' => 'mobile_no'],
    [
        'attribute' => 'complain_datetime',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => [
                'format' => 'dd-mm-yyyy',
                'autoclose' => true
            ]
        ],
        'value' => function ($model) {
            return Yii::$app->controls->view_date($model->complain_datetime);
        }
    ],
    ['attribute' => 'complain_type_code', 'value' => 'complainFors.complain_type', 'filter' => false, 'visible' => false],
    [
        'attribute' => 'affects_data',
        'filter' => array('1' => 'Yes', '0' => 'No'),
        'visible' => false,
        'value' => function ($model) {
            return $model->affects_data == 1 ? 'Yes' : 'No';
        }
    ],
    [
        'attribute' => 'physical_damage',
        'filter' => array('1' => 'Yes', '0' => 'No'),
        'visible' => false,
        'value' => function ($model) {
            return $model->physical_damage == 1 ? 'Yes' : 'No';
        }
    ],
    ['attribute' => 'asset_code', 'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->asset, 'asset_name');
        }, 'visible' => true, 'filter' => false],
    ['attribute' => 'serial_number', 'visible' => true, 'filter' => false],
    ['attribute' => 'complain_status',
        'filter' => Yii::$app->dropdown->dropdownfilterStatic('complain_status', $searchModel, 'complain_status'),
        'format' => 'raw',
        'value' => function ($model) {
            $status = isset(Yii::$app->dropdown->getRecords('complain_status')['data'][$model->complain_status]) ? Yii::$app->dropdown->getRecords('complain_status')['data'][$model->complain_status] : '';
            return Html::a($status, ['/complaint/tbl-complain/view', 'id' => $model->complain_code]);
        }
    ],
    ['attribute' => 'remarks'],
    ['attribute' => 'collection_request_type'],
];

$grid_option = [
    'id' => 'complaint',
    'attributes' => $attribute,
    'active_column' => false,
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
