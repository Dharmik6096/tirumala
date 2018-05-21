<?php

use yii\helpers\Html;
use kartik\grid\GridView;
?>

<?php
$attribute = [
    ['attribute' => 'dcsCode.union_code', 'value' => function($model) {
            return Yii::$app->general->getUnionName($model);
    }, 'visible' => true, 'filter' => false],
    'dcsCode.dcs_name',
    'shiftCode.shift',
    ['attribute' => 'start_time',
     'value' => 'start_time',
     'filter' => yii\widgets\MaskedInput::widget(['model'=>$searchModel,'attribute'=>'start_time','mask' => '99:99',])],
    ['attribute' => 'end_time',
     'value' => 'end_time',
     'filter' => yii\widgets\MaskedInput::widget(['model'=>$searchModel,'attribute'=>'end_time','mask' => '99:99',]),],
    [
    'attribute' => 'wef_date',
    'filterType'=>GridView::FILTER_DATE,
    'filterWidgetOptions'=>[
        'pluginOptions'=>['format'=>'dd-mm-yyyy',
            'autoclose'=>true]
    ],
    'value' => function($model) {
        return Yii::$app->controls->view_date($model->wef_date);
    }],
    [
        'attribute' => 'allow_after_collection',
        'filter' => Html::activeDropDownList($searchModel, 'allow_after_collection', [''=>'Select',1=>'Yes',0=>'No'],['class'=>'form-control']),
        'value' => function($model) {return ($model->allow_after_collection==1)?'Yes':'No';}
    ],
        // 'created_at',
        // 'created_by',
        // 'is_active',
        // 'updated_at',
        // 'updated_by',
];

$grid_option = [
    'id' => 'shift-time-grid',
    'attributes' => $attribute,
    'active_column' => true,
    'actions' => [
        'update' => true,
        'view' => true,
        'delete' => ['option' => 'shift_time_code,shift_time_code,tbl-shift-time/delete'],
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>