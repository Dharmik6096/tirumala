<?php

use yii\helpers\Html;
use webvimark\modules\UserManagement\components\GhostHtml;
use kartik\grid\GridView;
?>

<?php

$attribute = [
        [
        'attribute' => 'wef_date',
        'vAlign' => 'middle',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true,
                'filter' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->wef_date);
        }],
        ['attribute' => 'min_pouring_day'],
        ['attribute' => 'min_pouring_qty'],
        ['attribute' => 'scheme_value'],
];

$grid_option = [
    'id' => 'tbl-scheme-criteria-grid',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'edit' => function ($url, $model) {
            $class = '';
            $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Edit', 'class' => 'edit-record' . $class, 'data-val' => $model->scheme_criteria_id, 'data-name' => $model->scheme_criteria_id, 'title' => Yii::t('app', 'Edit')];
            return GhostHtml::a_alert('<i class="fa fa-pencil"></i>', ['/welfarescheme/tbl-scheme-criteria/update-criteria'], $options);
        },
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>

