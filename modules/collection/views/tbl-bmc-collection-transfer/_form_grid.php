<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;
use kartik\grid\GridView;

?>
<?php

$attribute = [
    ['attribute' => 'union_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'vAlign' => 'middle', 'filter' => false, 'visible' => false],
    ['attribute' => 'from_plant_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->plantCode, 'name');
        }, 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'from_mcc_plant_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->mccPlantCode, 'name');
        }, 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'route_name', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->routeCode, 'route_name');
        }, 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'to_plant_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->toPlantCode, 'name');
        }, 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'to_mcc_plant_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->toMccPlantCode, 'name');
        }, 'vAlign' => 'middle', 'filter' => false],
    [
        'attribute' => 'from_date',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->from_date);
        }],
    ['attribute' => 'from_shift_code', 'value' => 'fromShiftCode.shift', 'vAlign' => 'middle', 'filter' => false],
    [
        'attribute' => 'to_date',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->to_date);
        }],
    ['attribute' => 'to_shift_code', 'value' => 'toShiftCode.shift', 'vAlign' => 'middle', 'filter' => false],
];

$grid_option = [
    'id' => 'bmc-collection-',
    'attributes' => $attribute,
    'active_column' => false,
    'default_sorting' => FALSE,
//    'actions' => [
//        'view' => TRUE,
//        'update' => true,
//    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>