<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use webvimark\modules\UserManagement\components\GhostHtml;
use kartik\grid\GridView;
use yii\helpers\Html;

$attribute = [
    [
        'attribute' => 'union_code', 'filter' => false,
        'value' => function($model) {
            return (!empty($model->union_code) || isset($model->unionCode)) ? $model->unionCode->union_name : '-';
        }],
    ['attribute' => 'milk_type_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->milkTypeCode, 'animal_type_name');
        }, 'filter' => FALSE],
    ['attribute' => 'milk_quality_type_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->milkQualityCode, 'milk_quality_type_name');
        }, 'filter' => FALSE],
    ['attribute' => 'rate', 'filter' => false,],
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
];

$grid_option = [
    'id' => 'local-milk-rate-list',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'view_details' => function ($url, $model) {
            $class = '';
            $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'View', 'class' => $class];
            return GhostHtml::a('<i class="fa fa-eye"></i>', ['/dcsoperation/tbl-local-milk-rate/view', 'id' => $model->local_milk_rate_code], $options);
        },
        'applicabilty' => function ($url, $model) {
            $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Applicability'];
            return GhostHtml::a('<i class="fa fa-plus"></i>', ['/dcsoperation/tbl-local-milk-rate/local-milk-rate-applicability', 'id' => $model->local_milk_rate_code], $options);
        }
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
