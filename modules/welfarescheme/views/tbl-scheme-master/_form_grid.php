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
        ['attribute' => 'union_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'visible' => false,],
        ['attribute' => 'scheme_name'],
        [
        'attribute' => 'start_date',
        'vAlign' => 'middle',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true,
                'filter' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->start_date);
        }],
        [
        'attribute' => 'end_date',
        'vAlign' => 'middle',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true,
                'filter' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->end_date);
        }],
        ['attribute' => 'remarks'],
];

$grid_option = [
    'id' => 'tbl-scheme-master-grid',
    'attributes' => $attribute,
    'active_column' => true,
    'actions' => [
        'view' => true,
        'edit' => function ($url, $model) {
            $disable = '';
            $options = ['title' => Yii::t('app', 'Edit'), 'class' => $disable];
            return GhostHtml::a('<i class="fa fa-pencil"></i>', ['/welfarescheme/tbl-scheme-master/update', 'id' => $model->scheme_id], $options);
        },
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
