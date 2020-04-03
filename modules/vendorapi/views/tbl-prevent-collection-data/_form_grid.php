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
        }, 'vAlign' => 'middle', 'filter' => false],
        ['attribute' => 'plant_code', 'label' => Yii::t('app', 'Plant Code'), 'visible' => false, 'filter' => false, 'vAlign' => 'middle'],
        ['attribute' => 'plant_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->plantCode, 'name');
        }, 'vAlign' => 'middle', 'filter' => false],
        ['attribute' => 'mcc_plant_code', 'label' => Yii::t('app', 'MCC Code'), 'vAlign' => 'middle', 'filter' => false],
        ['attribute' => 'mcc_plant_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->mccPlantCode, 'name');
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
    //['attribute' => 'date_time_of_collection', 'value' => function($model){ return Yii::$app->controls->view_date($model->date_time_of_collection); }, 'vAlign' => 'middle', 'filter'=>false],
    ['attribute' => 'from_shift', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->fromShiftCode, 'shift');
        }, 'vAlign' => 'middle', 'filter' => false],
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
    //['attribute' => 'date_time_of_collection', 'value' => function($model){ return Yii::$app->controls->view_date($model->date_time_of_collection); }, 'vAlign' => 'middle', 'filter'=>false],
    ['attribute' => 'to_shift', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->toShiftCode, 'shift');
        }, 'vAlign' => 'middle', 'filter' => false],
];

$grid_option = [
    'id' => 'prevent-collection-data',
    'attributes' => $attribute,
    'active_column' => false,
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>