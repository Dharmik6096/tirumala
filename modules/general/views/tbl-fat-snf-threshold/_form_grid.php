<?php

use yii\helpers\Html;
use kartik\grid\GridView;
?>

<?php
$attribute = [
    //'threshold_code',
    ['attribute' => 'dcsCode.union_code', 'value' => function($model) {
            return Yii::$app->general->getUnionName($model);
    }, 'visible' => true, 'filter' => false],
    'dcsCode.dcs_name',
    'shift.shift',
    [
        'attribute' => 'wef_date',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
    return Yii::$app->controls->view_date($model->wef_date);
}],
    'minimum_fat',
    'maximum_fat',
    'minimum_snf',
    'maximum_snf',
        //'created_at',
        // 'created_by',
        // 'is_active',
        // 'updated_at',
        // 'updated_by',
];

$grid_option = [
    'id' => 'fat-snf-threshold-grid',
    'attributes' => $attribute,
    'active_column' => true,
    'actions' => [
        'view' => TRUE,
        'update' => true,
        'delete' => ['option' => 'threshold_code,threshold_code,tbl-fat-snf-threshold/delete'],
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>