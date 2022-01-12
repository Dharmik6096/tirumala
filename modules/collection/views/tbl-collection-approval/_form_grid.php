<?php

use yii\helpers\Html;
use webvimark\modules\UserManagement\components\GhostHtml;
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\web\View;
?>

<?php

$attribute = [
        [
        'attribute' => 'collection_type',
        'filter' => Yii::$app->dropdown->dropdownfilterStatic('milk_collection_type', $searchModel, 'collection_type'),
        'value' => function($model) {
            return isset(Yii::$app->dropdown->getRecords('milk_collection_type')['data'][$model->collection_type]) ? Yii::$app->dropdown->getRecords('milk_collection_type')['data'][$model->collection_type] : '';
        }],
        ['label' => 'Date', 'attribute' => 'date',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->date);
        }],
        ['attribute' => 'shift_code',
        'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->shiftCode, 'shift');
        }, 'filter' => false],
        ['attribute' => 'requested_by',
        'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->userAndroidCode, 'name');
        }],
        ['attribute' => 'approved_by', 'filter' => false, 'visible' => false],
        ['label' => 'Approve Date', 'attribute' => 'date',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->approve_date);
        }, 'filter' => false, 'visible' => false],
        ['label' => 'Allow Till Date', 'attribute' => 'date',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->allow_till_date);
        }, 'filter' => false, 'visible' => false],
        ['attribute' => 'valid_hours', 'filter' => false, 'visible' => false],
        ['attribute' => 'is_approve', 'filter' => false, 'visible' => false],
];

$grid_option = [
    'id' => 'collection-approval-grid',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'view' => true,
        'approve' => function ($url, $model) {
            $name = $model->uuid;
            $class = ($model->is_approve == 1) ? 'link-disable' : '';
            $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Approve', 'class' => '' . $class, 'data-val' => $model->uuid, 'data-name' => $name];
            return GhostHtml::a('<i class="fa fa-check"></i>', ['/collection/tbl-collection-approval/approve-collection', 'id' => $model->uuid], $options);
        },
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option, ['index'], true);
?>

