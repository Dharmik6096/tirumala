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
        }, 'visible' => false, 'filter' => false,],
        ['attribute' => 'scheme_id'],
        ['attribute' => 'scheme_name'],
        [
        'attribute' => 'start_date',
        'vAlign' => 'middle',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->start_date);
        }, 'filter' => false,],
        [
        'attribute' => 'end_date',
        'vAlign' => 'middle',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->end_date);
        }, 'filter' => false,],
        ['attribute' => 'remarks'],
//criteria details
    ['label' => ' WEF Date', 'attribute' => 'wef_date',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function ($model) {
            return Yii::$app->controls->view_date(Yii::$app->general->getforeignkey($model->defaultCriteriaDetail, 'wef_date'));
        }, 'filter' => false],
        ['attribute' => 'min_pouring_day', 'value' => 'defaultCriteriaDetail.min_pouring_day', 'filter' => false],
        ['attribute' => 'min_pouring_qty', 'value' => 'defaultCriteriaDetail.min_pouring_qty', 'filter' => false],
        ['attribute' => 'scheme_value', 'value' => 'defaultCriteriaDetail.scheme_value', 'filter' => false],
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
        'scheme-criteria' => function ($url, $model) {
            $options = ['data-name' => $model->scheme_name, 'data-val' => $model->scheme_id, 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Scheme Criteria'];
            return GhostHtml::a('<i class="fa fa-plus-square"></i>', ['/welfarescheme/tbl-scheme-master/scheme-criteria', 'id' => $model->scheme_id], $options);
        },
        'approval-stages' => function ($url, $model) {
            $options = ['title' => Yii::t('app', 'Approval Stages')];
            return GhostHtml::a('<i class="fa fa-list-ol"></i>', ['/welfarescheme/tbl-scheme-master/approval-stages', 'id' => $model->scheme_id], $options);
        },
        'scheme-document-mapping' => function ($url, $model) {
            $options = ['data-name' => $model->scheme_name, 'data-val' => $model->scheme_id, 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Scheme Document Mapping'];
            return GhostHtml::a('<i class="fa fa-link"></i>', ['/welfarescheme/tbl-scheme-master/scheme-document-mapping', 'id' => $model->scheme_id], $options);
        },
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
